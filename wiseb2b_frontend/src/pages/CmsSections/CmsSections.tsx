import React, { useState, useMemo, useEffect } from 'react';
import { reduxActions, useDispatch } from 'store';
import qs from 'query-string';
import { Trans, useTranslation } from 'react-i18next';
import classnames from 'classnames';
import { Check2 } from 'react-bootstrap-icons';

import Table, { IColumn } from 'components/controls/Table';
import { Button, Modal, PageTitle, SearchInput } from 'components/controls';
import { useAppNavigate } from 'hooks';
import { useGetCmsSections, usePostCmsSection } from 'api';
import { ICmsSectionItem } from 'api/types';
import { SectionForm, DeleteSectionButton } from './components';

import styles from 'theme/pages/Cms/Cms.module.scss';

const CmsSections = () => {
  const navigate = useAppNavigate();
  const dispatch = useDispatch();
  const { t } = useTranslation();

  // czy jest aktywny modal dodawania sekcji
  const [isAddSectionModal, setIsAddSectionModal] = useState<boolean>(false);

  // parametry zapytania o listę sekcji
  const [querySectionParams, setQuerySectionParams] = useState({
    page: 1,
    limit: 10,
    search_keyword: '',
    ...qs.parseUrl(window.location.href, { parseNumbers: true }).query
  });

  // pobranie listy sekcji
  const { data: sectionData, refetch: refetchSectionData } = useGetCmsSections(querySectionParams);

  // tworzenie nowej sekcji
  const { mutate: createSection, isLoading: isCreatingSection } = usePostCmsSection({
    onSuccess: () => {
      refetchSectionData();
      setIsAddSectionModal(false);
    }
  });

  // Ustawienie breadcrumbs'ów (przy renderowaniu strony)
  useEffect(() => {
    dispatch(
      reduxActions.setBreadcrumbs([
        {
          name: t('Dashboard'),
          path: '/managment_panel'
        },
        {
          name: t('Lista sekcji'),
          path: undefined
        }
      ])
    );
  }, []);

  // Zmiana url'a przy zmianie parametrów zapytania do API
  useEffect(() => {
    navigate(
      `/managment_panel/cms/sections?${qs.stringify(querySectionParams, { skipEmptyString: true })}`
    );
  }, [querySectionParams]);

  const sectionColumns: IColumn<ICmsSectionItem>[] = useMemo(
    () => [
      {
        title: <Trans>LP</Trans>,
        align: 'center',
        width: 50,
        renderCell: (item) => item.lp
      },
      {
        title: <Trans>Symbol</Trans>,
        dataIndex: 'symbol',
        align: 'center',
        width: 300,
        renderCell: (item) => (
          <button
            className={styles.link}
            onClick={() => navigate(`/managment_panel/cms/sections/${item.id}`)}>
            {item.symbol}
          </button>
        )
      },
      {
        title: <Trans>Nazwa</Trans>,
        dataIndex: 'name',
        align: 'center'
      },
      {
        title: <Trans>Aktywny</Trans>,
        dataIndex: 'is_active',
        align: 'center',
        width: 200,
        renderCell: (item) => !!item.is_active && <Check2 />
      },
      {
        title: <Trans>Opcje</Trans>,
        key: 'actions',
        align: 'center',
        width: 80,
        renderCell: (item) => <DeleteSectionButton id={item.id} refetch={refetchSectionData} />
      }
    ],
    []
  );

  return (
    <div className={classnames(styles.componentWrapper, 'StylePath-Pages-Clients')}>
      <div className={styles.tableWrapper}>
        <PageTitle
          title={
            <div className={styles.titleWrapper}>
              <div className={styles.text}>
                <Trans>Lista sekcji</Trans>{' '}
                <span className="thin">({sectionData?.total_count})</span>
              </div>
            </div>
          }
        />
        <div className={styles.filtersWrapper}>
          <SearchInput
            placeholder={`${t('Szukaj sekcji')}...`}
            value={querySectionParams.search_keyword}
            onChange={(value) =>
              setQuerySectionParams((prevState) => ({ ...prevState, search_keyword: value }))
            }
          />

          <Button onClick={() => setIsAddSectionModal(true)}>
            <Trans>Utwórz sekcję</Trans>
          </Button>
        </div>
        <Table<ICmsSectionItem>
          columns={sectionColumns}
          dataSource={sectionData?.items || []}
          rowKey="symbol"
          pagination={{
            page: querySectionParams.page || 1,
            pagesCount: sectionData?.total_pages || 1,
            onChange: (page) => setQuerySectionParams((prevState) => ({ ...prevState, page }))
          }}
        />
      </div>
      {isAddSectionModal && (
        <Modal title={t('Utwórz sekcję')}>
          <SectionForm
            mutate={createSection}
            isLoading={isCreatingSection}
            onClose={() => setIsAddSectionModal(false)}
          />
        </Modal>
      )}
    </div>
  );
};

export default CmsSections;
