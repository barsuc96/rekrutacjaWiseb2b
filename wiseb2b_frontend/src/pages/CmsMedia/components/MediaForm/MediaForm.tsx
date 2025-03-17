import React, { FC, useEffect, useState } from 'react';
import Dropzone from 'react-dropzone';
import { Plus, X } from 'react-bootstrap-icons';
import classnames from 'classnames';
import { Trans, useTranslation } from 'react-i18next';
import { useFormik } from 'formik';
import Textarea from '@mui/material/TextareaAutosize';
import * as Yup from 'yup';

import { useGetCmsMediaItem, usePostCmsMedia, usePutCmsMedia } from 'api/endpoints';

import { Input, Button, FormElement } from 'components/controls';

import styles from 'theme/pages/Cms/components/MediaForm.module.scss';

// typ danych wejściowych
interface IProps {
  activeFile: number | null;
  onClose: () => void;
  refetchMediaList: () => void;
}

const SectionForm: FC<IProps> = ({ activeFile, onClose, refetchMediaList }) => {
  const { t } = useTranslation();

  // typ media
  const [fileType, setFileType] = useState('');

  // pobieranie danych dla zdjęcia
  const { data, refetch: refetchMediaItem } = useGetCmsMediaItem(activeFile || 0, {
    enabled: false
  });

  // dodanie zdjęcia
  const { mutate: createImage } = usePostCmsMedia({
    onSuccess: () => {
      onClose();
      refetchMediaList();
    }
  });

  // edycja zdjęcia
  const { mutate: updateImage } = usePutCmsMedia(activeFile || 0, {
    onSuccess: () => {
      onClose();
      refetchMediaList();
    }
  });

  useEffect(() => {
    if (activeFile) {
      refetchMediaItem();
    }
  }, [activeFile]);

  // obsługa formularza
  const { values, errors, setFieldValue, handleSubmit } = useFormik({
    initialValues: {
      name: data?.name || '',
      description: data?.description || '',
      base64: '',
      url: data?.url || null
    },
    onSubmit: (values) => {
      activeFile ? updateImage(values) : createImage(values);
    },
    validationSchema: Yup.object().shape({
      name: Yup.string().required(),
      base64: Yup.string().required()
    }),
    validateOnChange: false,
    enableReinitialize: true
  });

  const handleDropzone = async (file: File[]) => {
    const reader = new FileReader();
    reader.readAsDataURL(file[0]);
    reader.onload = function () {
      setFieldValue('base64', reader.result);

      if (!activeFile) {
        setFieldValue('name', file[0].name);
      }

      setFileType(file[0].type);
    };
  };

  const handleRemove = () => {
    setFieldValue('base64', '');
    setFieldValue('url', null);

    if (!activeFile) {
      setFieldValue('name', '');
    }
  };

  return (
    <div
      className={classnames(styles.componentWrapper, 'StylePath-Pages-Cms-components-SectionForm')}>
      <form onSubmit={handleSubmit}>
        <div className={styles.inputs}>
          <FormElement>
            {values?.url || values.base64 ? (
              <div className={styles.fileWrapper}>
                <div>
                  {fileType.includes('video') ? (
                    <video width="200" controls>
                      <source src={values?.url || values.base64} type={fileType} />
                    </video>
                  ) : (
                    <img src={values?.url || values.base64} alt={values.name} />
                  )}
                  <button title={t('Usuń')} onClick={handleRemove}>
                    <X size={24} />
                  </button>
                </div>
              </div>
            ) : (
              <Dropzone onDrop={(acceptedFiles) => handleDropzone(acceptedFiles)}>
                {({ getRootProps, getInputProps }) => (
                  <section className={styles.dropzone}>
                    <div {...getRootProps()}>
                      <input {...getInputProps()} />
                      <p>
                        <Trans>Dodaj plik</Trans>
                        <Plus size={24} />
                      </p>
                    </div>
                  </section>
                )}
              </Dropzone>
            )}
          </FormElement>
          <FormElement>
            <label>
              <Trans>Nazwa</Trans>
            </label>
            <Input
              value={values.name}
              onChange={(value) => setFieldValue('name', value)}
              error={errors.name}
              disabled={!!activeFile}
            />
          </FormElement>
          <FormElement>
            <label>
              <Trans>Opis</Trans>
            </label>
            <Textarea
              value={values.description}
              onChange={({ target: { value } }) => setFieldValue('description', value)}
              minRows={5}
              disabled={!!activeFile}
            />
          </FormElement>
        </div>
        <div className={styles.actions}>
          <Button color="secondary" ghost onClick={onClose}>
            <Trans>Anuluj</Trans>
          </Button>
          <Button color="primary" htmlType="submit" disabled={!values.base64}>
            <Trans>Zapisz</Trans>
          </Button>
        </div>
      </form>
    </div>
  );
};

export default SectionForm;
