import React, { FC, PropsWithChildren, useState } from 'react';
import { Link45deg } from 'react-bootstrap-icons';
import { useFormik } from 'formik';
import { merge, omit } from 'lodash';
import { Trans, useTranslation } from 'react-i18next';
import classnames from 'classnames';

import {
  useGetExports,
  useGetExport,
  usePostExport,
  usePostExportGenerate,
  usePutExport
} from 'api';
import { IExportListItem, IExportRequest } from 'api/types';
import { Input, Select, Button, Modal, DatePicker, Checkbox, Radio } from 'components/controls';
import Attributes from '../Attributes';
import RemoveExport from '../RemoveExport';

import styles from 'theme/pages/Export/components/ExportForm/ExportForm.module.scss';

interface IProps {
  categoryId?: number;
}

const ExportForm: FC<IProps> = ({ categoryId }) => {
  const { t } = useTranslation();

  // aktualnie wybrany export
  const [currentExportId, setCurrentExportId] = useState<number | null>(null);

  // czy jest widoczny modal usuwania wxportu
  const [isRemoveExportModal, setIsRemoveExportModal] = useState(false);

  const { data: exportsData, refetch: refetchExportsData } = useGetExports({
    page: 1,
    limit: 999
  });

  const { data: currentExportData } = useGetExport(currentExportId || 0, {
    enabled: !!currentExportId
  });

  // tworzenie nowego exportu
  const { mutate: createExport, isLoading: isPostExportsLoading } = usePostExport({
    onSuccess: (data) => {
      setCurrentExportId(data.data.id);
      refetchExportsData();
    },
    onError: (error) => {
      setErrors(
        error.error_fields?.reduce((output, item) => {
          const errorItem = item.property_path
            .split('.')
            .reverse()
            .reduce((acc, pathPart, currentIndex) => {
              return { [pathPart]: currentIndex === 0 ? item.message : acc };
            }, {});

          return merge(output, errorItem);
        }, {}) || {}
      );
    }
  });

  // aktualizacja istniejącego exportu
  const { mutate: updateExport, isLoading: isUpdateExportLoading } = usePutExport(
    currentExportId || 0,
    {
      onSuccess: () => {
        refetchExportsData();
      },
      onError: (error) => {
        setErrors(
          error.error_fields?.reduce((output, item) => {
            const errorItem = item.property_path
              .split('.')
              .reverse()
              .reduce((acc, pathPart, currentIndex) => {
                return { [pathPart]: currentIndex === 0 ? item.message : acc };
              }, {});

            return merge(output, errorItem);
          }, {}) || {}
        );
      }
    }
  );

  // wygenerowanie pliku exportu
  const { mutate: generateExport, isLoading: isExportGenerating } = usePostExportGenerate({
    onSuccess: ({ data }) => {
      const { file_name, url } = data;
      const a = document.createElement('a');
      a.download = file_name;
      a.href = url;
      a.click();
    },
    onError: (error) => {
      setErrors(
        error.error_fields?.reduce((output, item) => {
          const errorItem = item.property_path
            .split('.')
            .reverse()
            .reduce((acc, pathPart, currentIndex) => {
              return { [pathPart]: currentIndex === 0 ? item.message : acc };
            }, {});

          return merge(output, errorItem);
        }, {}) || {}
      );
    }
  });

  const { values, errors, setFieldValue, handleSubmit, setErrors } = useFormik<
    Partial<IExportRequest>
  >({
    initialValues: currentExportData
      ? {
          ...currentExportData,
          attributes: currentExportData.attributes.map((attribute) => ({
            id: attribute.id,
            field_name: attribute.field_name,
            name: attribute.name,
            chosen: true
          }))
        }
      : {},
    enableReinitialize: true,
    validateOnChange: false,
    onSubmit: (values) => {
      const requestData = {
        ...values,
        period:
          values.period && typeof values.period === 'string'
            ? parseInt(values.period)
            : values.period,
        category_id: categoryId,
        id: undefined
      };
      currentExportId ? updateExport(requestData) : createExport(requestData);
    }
  });

  const Error: FC<PropsWithChildren> = ({ children }) => (
    <div className={styles.error}>{children}</div>
  );

  const Title: FC<PropsWithChildren> = ({ children }) => (
    <div className={styles.title}>{children}</div>
  );

  return (
    <div
      className={classnames(
        styles.wrapperComponent,
        'StylePath-Pages-Export-components-ExportForm'
      )}>
      <div className={styles.settingsSelector}>
        <Title>
          <Trans>Zapisane ustawienia</Trans>
        </Title>
        <Select<IExportListItem>
          options={
            exportsData?.items.map((item) => ({
              value: item.id,
              label: item.name,
              item
            })) || []
          }
          value={currentExportId}
          onChange={(item) => setCurrentExportId(item?.id || null)}
          placeholder={t('Nowe ustawienia')}
        />

        <div className={styles.buttonWrapper}>
          <Button
            color="danger"
            disabled={!currentExportId}
            onClick={() => setIsRemoveExportModal(true)}>
            <Trans>Usuń ustawienia</Trans>
          </Button>
        </div>
      </div>
      <div className={styles.attributes}>
        <Title>
          <Trans>Wybierz atrybuty do exportu</Trans>
        </Title>
        <Attributes
          onChange={(selectedAttributes) => setFieldValue('attributes', selectedAttributes)}
          chosenFields={values.attributes?.map((item) => item.id) || []}
          error={errors.attributes}
        />
      </div>
      <div className={styles.options}>
        <div>
          <Title>
            <Trans>Typ pliku</Trans>
          </Title>

          <label>
            <Radio checked={values.type === 'XLS'} onClick={() => setFieldValue('type', 'XLS')} />
            XLS
          </label>
          <label>
            <Radio checked={values.type === 'CSV'} onClick={() => setFieldValue('type', 'CSV')} />
            CSV
          </label>
          <label>
            <Radio checked={values.type === 'XML'} onClick={() => setFieldValue('type', 'XML')} />
            XML
          </label>
          {errors.type && <Error>{errors.type}</Error>}

          {values.type === 'CSV' && (
            <div className={styles.separator}>
              <label>
                <Trans>separator</Trans>:
              </label>
              <Input
                onChange={(value) => setFieldValue('csv_separator', value)}
                value={values.csv_separator || ''}
              />
              {errors.csv_separator && <Error>{errors.csv_separator}</Error>}
            </div>
          )}
        </div>

        <div>
          <Title>
            <Trans>Generowanie cykliczne</Trans>
          </Title>
          <label>
            <Checkbox
              checked={!!values.periodic}
              onClick={() => setFieldValue('periodic', !values.periodic)}
            />{' '}
            <Trans>Okresowe generowanie oferty</Trans>
          </label>
          {values.periodic && (
            <>
              <div className={styles.period}>
                <label>
                  <Trans>Odstęp w dniach</Trans>:
                </label>
                <Input
                  onChange={(value) => setFieldValue('period', value)}
                  value={values.period?.toString()}
                />
                {errors.period && <Error>{errors.period}</Error>}
              </div>
              <div className={styles.start_date}>
                <label>
                  <Trans>Data startu</Trans>:
                </label>
                <DatePicker
                  onChange={(date) => setFieldValue('start_date', date)}
                  date={values.start_date}
                />
                {errors.start_date && <Error>{errors.start_date}</Error>}
              </div>
              <label>
                <Checkbox
                  checked={!!values.send_on_mail}
                  onClick={() => setFieldValue('send_on_mail', !values.send_on_mail)}
                />{' '}
                <Trans>Wyślij ofertę na adres email</Trans>
              </label>
            </>
          )}
        </div>
      </div>
      <div className={styles.name}>
        <Title>
          <Trans>Nazwa</Trans>
        </Title>

        <Input onChange={(value) => setFieldValue('name', value)} value={values.name} />
        {errors.name && <Error>{errors.name}</Error>}
      </div>
      <div className={styles.footer}>
        <div className={styles.linkWrapper}>
          <Link45deg />
          <div className={styles.linkBox}>
            {currentExportData?.url ? (
              <a href={currentExportData?.url} rel="noreferrer noopener" target="_blank">
                {currentExportData?.url}
              </a>
            ) : (
              <Trans>Link będzie dostępny po wygenerowaniu pliku</Trans>
            )}
          </div>
        </div>

        <div className={styles.actions}>
          <Button
            onClick={() => handleSubmit()}
            loading={isPostExportsLoading || isUpdateExportLoading}>
            <Trans>Zapisz ustawienia</Trans>
          </Button>

          <Button
            onClick={() => generateExport({ ...omit(values, ['id']), category_id: categoryId })}
            loading={isExportGenerating}>
            <Trans>Exportuj dane</Trans>
          </Button>
        </div>
      </div>

      {isRemoveExportModal && !!currentExportData && (
        <Modal
          onClose={() => setIsRemoveExportModal(false)}
          title={`${t('Usuwanie exportu')} ${currentExportData.name}`}>
          <RemoveExport
            data={currentExportData}
            onCancel={() => setIsRemoveExportModal(false)}
            onSuccess={() => {
              refetchExportsData();
              setCurrentExportId(null);
            }}
          />
        </Modal>
      )}
    </div>
  );
};

export default ExportForm;
