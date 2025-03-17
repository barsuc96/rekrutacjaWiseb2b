import React, { useEffect, useState, useMemo } from 'react';
import { reduxActions, useDispatch } from 'store';
import { useParams } from 'react-router-dom';
import { Trans, useTranslation } from 'react-i18next';
import classnames from 'classnames';

import {
  useGetCmsSection,
  useGetCmsSectionFields,
  useGetCmsSectionField,
  usePutCmsSection,
  usePostCmsSectionField,
  usePutCmsSectionField
} from 'api';
import { ICmsSectionItemFields } from 'api/types';
import Table, { IColumn } from 'components/controls/Table';
import { Radio, PageTitle } from 'components/controls';

import { SectionForm } from 'pages/CmsSections/components';
import { SectionFieldForm } from './components';

import styles from 'theme/pages/Cms/Cms.module.scss';

const SectionItem = () => {
  // ID artykułu (przekształcony na int)
  const { id: sectionIdParam } = useParams();
  const dispatch = useDispatch();
  const { t } = useTranslation();

  const sectionId = useMemo(() => parseInt(sectionIdParam || ''), [sectionIdParam]);

  // parametry zapytania o listę sekcji
  const [queryParams, setQueryParams] = useState({
    page: 1,
    limit: 20,
    search_keyword: ''
  });

  // aktywne pole sekcji
  const [activeSectionField, setActiveSectionField] = useState<number>(0);

  // pobranie listy sekcji
  const { data: sectionData } = useGetCmsSection(sectionId);
  const { data: sectionFieldsData, refetch: refetchSectionFieldsData } =
    useGetCmsSectionFields(sectionId);
  const { data: sectionField, refetch: refetchSectionField } = useGetCmsSectionField(
    sectionId,
    activeSectionField,
    {
      enabled: false
    }
  );

  // edytowanie sekcji
  const { mutate: updateSection, isLoading: isUpdatingSection } = usePutCmsSection(sectionId);

  // dodawanie pól sekcji
  const { mutateAsync: createSectionField, isLoading: isCreatingSectionField } =
    usePostCmsSectionField(sectionId);

  // edytowanie pól sekcji
  const { mutateAsync: updateSectionField, isLoading: isUpdatingSectionField } =
    usePutCmsSectionField(sectionId, activeSectionField);

  // Ustawienie breadcrumbs'ów (przy renderowaniu strony)
  useEffect(() => {
    dispatch(
      reduxActions.setBreadcrumbs([
        {
          name: t('Dashboard'),
          path: '/dashboard'
        },
        {
          name: t('Lista sekcji'),
          path: '/dashboard/cms/sections'
        },
        {
          name: sectionData?.name || '',
          path: undefined
        }
      ])
    );
  }, [sectionData]);

  useEffect(() => {
    if (activeSectionField) {
      refetchSectionField();
    }
  }, [activeSectionField]);

  const columns: IColumn<ICmsSectionItemFields>[] = useMemo(
    () => [
      {
        title: '',
        key: 'radio',
        align: 'left',
        renderCell: (item) => (
          <Radio
            checked={activeSectionField === item.id}
            onClick={() => setActiveSectionField(item.id)}
          />
        )
      },
      {
        title: <Trans>Symbol</Trans>,
        dataIndex: 'symbol',
        align: 'center',
        renderCell: (item) => (
          <span className={classnames({ [styles.activeRow]: item.id === activeSectionField })}>
            {item.symbol}
          </span>
        )
      },
      {
        title: <Trans>Nazwa</Trans>,
        dataIndex: 'label',
        align: 'center',
        renderCell: (item) => (
          <span className={classnames({ [styles.activeRow]: item.id === activeSectionField })}>
            {item.label}
          </span>
        )
      },
      {
        title: <Trans>Typ</Trans>,
        dataIndex: 'type',
        align: 'center',
        renderCell: (item) => (
          <span className={classnames({ [styles.activeRow]: item.id === activeSectionField })}>
            {item.type}
          </span>
        )
      }
    ],
    [activeSectionField]
  );

  return (
    <div
      className={classnames(styles.wrapperComponent, 'StylePath-Pages-Cms-components-SectionItem')}>
      <div className={styles.top}>
        <div>
          <PageTitle
            title={
              <div className={styles.titleWrapper}>
                <div className={styles.text}>
                  <Trans>Edytuj sekcję</Trans>
                  {!!sectionData && ' - ' + sectionData.name}
                </div>
              </div>
            }
          />
          <SectionForm
            activeSection={sectionId}
            data={sectionData}
            isLoading={isUpdatingSection}
            mutate={updateSection}
          />
        </div>
      </div>

      <h2>
        <Trans>{activeSectionField ? 'Edytuj pola sekcji' : 'Stwórz pola sekcji'}</Trans>
      </h2>
      <div className={styles.bottom}>
        <div>
          <Table<ICmsSectionItemFields>
            columns={columns}
            dataSource={sectionFieldsData?.items || []}
            rowKey="symbol"
            pagination={{
              page: queryParams.page || 1,
              pagesCount: sectionFieldsData?.total_pages || 1,
              onChange: (page) => setQueryParams((prevState) => ({ ...prevState, page }))
            }}
          />
          <div className={styles.newSectionField}>
            <Radio checked={activeSectionField === 0} onClick={() => setActiveSectionField(0)} />{' '}
            <div className={classnames({ [styles.activeRow]: activeSectionField === 0 })}>
              <Trans>Nowe pole sekcji</Trans>
            </div>
          </div>
        </div>
        <div>
          <SectionFieldForm
            data={sectionField}
            activeSection={sectionId}
            activeSectionField={activeSectionField}
            refetchSectionFieldsData={refetchSectionFieldsData}
            mutate={activeSectionField ? updateSectionField : createSectionField}
            isLoading={isCreatingSectionField || isUpdatingSectionField}
          />
        </div>
      </div>
    </div>
  );
};

export default SectionItem;
