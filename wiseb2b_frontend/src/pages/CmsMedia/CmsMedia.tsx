import React, { useState, useMemo, useEffect } from 'react';
import qs from 'query-string';
import { Trans, useTranslation } from 'react-i18next';
import classnames from 'classnames';

import { useAppNavigate } from 'hooks';
import { Button, PageTitle, SearchInput, Modal } from 'components/controls';
import Table, { IColumn } from 'components/controls/Table';
import { useGetCmsMedia } from 'api';
import { ICmsMediaItem } from 'api/types';

import { MediaForm, DeleteMediaButton } from './components';

import styles from 'theme/pages/Cms/Cms.module.scss';

interface IQueryMediaParams {
  page: number;
  limit: number;
  search_keyword: string;
}

const CmsMedia = () => {
  const navigate = useAppNavigate();
  const { t } = useTranslation();

  // czy jest aktywny modal dodawania sekcji
  const [isAddMediaModal, setIsAddMediaModal] = useState<boolean>(false);

  // aktualnie wybrany plik
  const [activeFile, setActiveFile] = useState<number | null>(null);

  // parametry zapytania o listę artykułów
  const [queryMediaParams, setQueryMediaParams] = useState<IQueryMediaParams>({
    page: 1,
    limit: 10,
    search_keyword: '',
    ...qs.parseUrl(window.location.href, { parseNumbers: true }).query
  });

  // pobranie listy artykułów
  const { data: mediaData, refetch: refetchMediaData } = useGetCmsMedia(queryMediaParams);

  // Zmiana url'a przy zmianie parametrów zapytania do API
  useEffect(() => {
    navigate(
      `/managment_panel/cms/media?${qs.stringify(queryMediaParams, { skipEmptyString: true })}`
    );
  }, [queryMediaParams]);

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
        width: 150,
        renderCell: (item) => (
          <div className={styles.imageWrapper}>
            {item.type.includes('video') ? (
              <video width="150" controls>
                <source src={item.url} type={item.type} />
              </video>
            ) : (
              <img src={item.url} alt={item.name} />
            )}
          </div>
        )
      },
      {
        title: <Trans>Nazwa</Trans>,
        dataIndex: 'name',
        align: 'center',
        width: 100,
        renderCell: (item) => (
          <button
            className={styles.link}
            onClick={() => {
              setIsAddMediaModal(true);
              setActiveFile(item.id);
            }}>
            {item.name}
          </button>
        )
      },
      {
        title: <Trans>Url</Trans>,
        dataIndex: 'url',
        align: 'center',
        width: 300
      },
      {
        title: <Trans>Opcje</Trans>,
        align: 'center',
        renderCell: (item) => <DeleteMediaButton id={item.id} refetch={refetchMediaData} />,
        width: 50
      }
    ],
    [mediaData]
  );

  return (
    <div className={classnames(styles.componentWrapper, 'StylePath-Pages-Cms')}>
      <div className={styles.tableWrapper}>
        <PageTitle
          title={
            <div className={styles.titleWrapper}>
              <div className={styles.text}>
                <Trans>Lista mediów</Trans> <span className="thin">({mediaData?.total_count})</span>
              </div>
            </div>
          }
        />
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

          <Button onClick={() => setIsAddMediaModal(true)}>
            <Trans>Dodaj media</Trans>
          </Button>
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
      </div>
      {isAddMediaModal && (
        <Modal title={t(activeFile ? 'Edytuj media' : 'Dodaj media')}>
          <MediaForm
            activeFile={activeFile}
            onClose={() => {
              setIsAddMediaModal(false);
              setActiveFile(null);
            }}
            refetchMediaList={refetchMediaData}
          />
        </Modal>
      )}
    </div>
  );
};

export default CmsMedia;
