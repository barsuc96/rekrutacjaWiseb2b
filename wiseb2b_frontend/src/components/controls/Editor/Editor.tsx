// Edytor tekstu html

import React, { FC, PropsWithChildren, useRef, useState, useMemo, useEffect } from 'react';
import { Editor as TinyEditor } from '@tinymce/tinymce-react';
import { useTranslation, Trans } from 'react-i18next';

import { useGetCmsMedia, usePostCmsMedia } from 'api/endpoints';
import { ICmsMediaItem } from 'api/types';
import Table, { IColumn } from 'components/controls/Table';
import { Modal, SearchInput } from 'components/controls';

import styles from 'theme/pages/Cms/Cms.module.scss';

// typ danych wejściowych
interface IProps {
  value: string;
  onChange: (arg: string) => void;
}

const Editor: FC<PropsWithChildren<IProps>> = ({ value, onChange }) => {
  const { t } = useTranslation();

  const editorRef = useRef(null);

  const [showModal, setShowModal] = useState(false);

  // parametry zapytania o listę mediów
  const [queryMediaParams, setQueryMediaParams] = useState({
    page: 1,
    limit: 5,
    search_keyword: ''
  });

  // pobranie listy mediów
  const { data: mediaData, refetch: refetchMediaData } = useGetCmsMedia(queryMediaParams, {
    enabled: false
  });

  const { mutateAsync: addMedia } = usePostCmsMedia();

  useEffect(() => {
    if (showModal) {
      refetchMediaData();
    }
  }, [showModal, mediaData]);

  const mediaColumns: IColumn<ICmsMediaItem>[] = useMemo(
    () => [
      {
        title: <Trans>LP</Trans>,
        dataIndex: 'lp',
        align: 'center',
        width: 50
      },
      {
        title: '',
        dataIndex: 'url',
        align: 'center',
        width: 80,
        renderCell: (item) => (
          <div className={styles.imageWrapper}>
            <img src={item.url} alt={item.name} />
          </div>
        )
      },
      {
        title: <Trans>Nazwa</Trans>,
        dataIndex: 'name',
        align: 'center',
        width: 300,
        renderCell: (item) => (
          <button className={styles.link} onClick={() => handleInsertFile(item.url)}>
            {item.name}
          </button>
        )
      }
    ],
    [mediaData]
  );

  const handleInsertFile = (url: string) => {
    const img = document.getElementById('uploadImage') as HTMLInputElement;
    if (img) {
      img.value = url;
      const event = new Event('change');
      img.dispatchEvent(event);
    }
  };

  const image_upload_handler = (blobInfo: any): Promise<string> =>
    new Promise((resolve, reject) => {
      const name = blobInfo.blob().name;
      const base64 = blobInfo.base64();

      addMedia({
        name,
        base64,
        description: ''
      })
        .then((response) => {
          resolve(response.data.url || '');
        })
        .catch((e) => {
          reject(e);
        });
    });

  return (
    <>
      <input type="text" id="uploadImage" name="city" style={{ display: 'none' }} />
      <TinyEditor
        ref={editorRef}
        apiKey={process.env.REACT_APP_EDITOR_API_KEY}
        value={value}
        onEditorChange={onChange}
        init={{
          height: 500,
          menubar: false,
          plugins: [
            'advlist',
            'autolink',
            'lists',
            'link',
            'image',
            'charmap',
            'preview',
            'anchor',
            'searchreplace',
            'visualblocks',
            'code',
            'fullscreen',
            'insertdatetime',
            'media',
            'table',
            'code',
            'help',
            'wordcount'
          ],
          toolbar:
            'undo redo | accordion accordionremove | blocks fontfamily fontsize | bold italic underline strikethrough | align numlist bullist | link image | table media | lineheight outdent indent| forecolor backcolor removeformat | charmap emoticons | code fullscreen preview | save print | pagebreak anchor codesample | ltr rtl',
          content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
          image_title: true,
          automatic_uploads: true,
          file_picker_types: 'image',
          images_upload_handler: image_upload_handler,
          file_picker_callback(callback) {
            setShowModal(!showModal);
            const input = document.getElementById('uploadImage');
            if (input) {
              input.onchange = () => {
                const el = document.getElementById('uploadImage') as HTMLInputElement;
                if (el.value) {
                  callback(el.value);
                  setShowModal(false);
                }
              };
            }
          }
        }}
      />
      {showModal && (
        <Modal title={t('Galeria zdjęć')} onClose={() => setShowModal(false)}>
          <div className={styles.filtersWrapper}>
            <div className={styles.selectWrapper}>
              <SearchInput
                placeholder={`${t('Szukaj')}...`}
                value={queryMediaParams.search_keyword}
                onChange={(value) =>
                  setQueryMediaParams((prevState) => ({ ...prevState, search_keyword: value }))
                }
              />
            </div>
          </div>

          <Table<ICmsMediaItem>
            columns={mediaColumns}
            dataSource={mediaData?.items || []}
            rowKey="name"
            pagination={{
              page: queryMediaParams.page || 1,
              pagesCount: mediaData?.total_pages || 1,
              onChange: (page) => setQueryMediaParams((prevState) => ({ ...prevState, page }))
            }}
          />
        </Modal>
      )}
    </>
  );
};

export default Editor;
