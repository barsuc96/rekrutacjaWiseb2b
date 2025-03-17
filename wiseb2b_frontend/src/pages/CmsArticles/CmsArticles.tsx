import React, { useState, useMemo, useEffect } from 'react';
import { reduxActions, useDispatch } from 'store';
import qs from 'query-string';
import { Trans, useTranslation } from 'react-i18next';
import classnames from 'classnames';
import { Check2 } from 'react-bootstrap-icons';

import Table, { IColumn } from 'components/controls/Table';
import { useAppNavigate } from 'hooks';
import { Button, PageTitle, SearchInput, Select } from 'components/controls';
import { useGetCmsSections, useGetCmsArticles } from 'api';
import { ICmsArticleItem, ICmsSectionResponse } from 'api/types';
import { DeleteArticleButton } from './components';

import styles from 'theme/pages/Cms/Cms.module.scss';

interface IQueryArticleParams {
  page: number;
  limit: number;
  search_keyword: string;
  section_id?: number | null;
}

const CmsArticles = () => {
  const navigate = useAppNavigate();
  const dispatch = useDispatch();
  const { t } = useTranslation();

  // parametry zapytania o listę artykułów
  const [queryArticleParams, setQueryArticleParams] = useState<IQueryArticleParams>({
    page: 1,
    limit: 10,
    search_keyword: '',
    ...qs.parseUrl(window.location.href, { parseNumbers: true }).query
  });

  // pobranie listy sekcji
  const { data: sectionData } = useGetCmsSections({
    page: 1,
    limit: 999
  });

  // pobranie listy artykułów
  const { data: articleData, refetch: refetchArticleData } = useGetCmsArticles(queryArticleParams);

  // Ustawienie breadcrumbs'ów (przy renderowaniu strony)
  useEffect(() => {
    dispatch(
      reduxActions.setBreadcrumbs([
        {
          name: t('Dashboard'),
          path: '/managment_panel'
        },
        {
          name: t('Lista artykułów'),
          path: undefined
        }
      ])
    );
  }, []);

  // Zmiana url'a przy zmianie parametrów zapytania do API
  useEffect(() => {
    navigate(
      `/managment_panel/cms/articles?${qs.stringify(queryArticleParams, { skipEmptyString: true })}`
    );
  }, [queryArticleParams]);

  const articleColumns: IColumn<ICmsArticleItem>[] = useMemo(
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
            onClick={() => navigate(`/managment_panel/cms/articles/${item.id}`)}>
            {item.symbol}
          </button>
        )
      },
      {
        title: <Trans>Nazwa</Trans>,
        dataIndex: 'title',
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
        renderCell: (item) => (
          <DeleteArticleButton articleId={item.id} refetch={refetchArticleData} />
        )
      }
    ],
    [articleData]
  );

  // opcje selektora sekcji
  const sectionSelectOptions = useMemo(
    () =>
      sectionData?.items.map((section) => ({
        value: section.id,
        label: (
          <>
            <span>
              {section.symbol} - {section.name}
            </span>
          </>
        ),
        item: section
      })) || [],
    [sectionData]
  );

  return (
    <div className={classnames(styles.componentWrapper, 'StylePath-Pages-Clients')}>
      <div className={styles.tableWrapper}>
        <PageTitle
          title={
            <div className={styles.titleWrapper}>
              <div className={styles.text}>
                <Trans>Lista artykułów</Trans>{' '}
                <span className="thin">({articleData?.total_count})</span>
              </div>
            </div>
          }
        />
        <div className={styles.filtersWrapper}>
          <div className={styles.selectWrapper}>
            <SearchInput
              placeholder={`${t('Szukaj artykułu')}...`}
              value={queryArticleParams.search_keyword}
              onChange={(value) =>
                setQueryArticleParams((prevState) => ({ ...prevState, search_keyword: value }))
              }
            />
            <div className={styles.sectionSelectWrapper}>
              <Select<ICmsSectionResponse>
                variant="bordered"
                placeholder={t('Sekcja')}
                value={queryArticleParams.section_id}
                onChange={(item) => {
                  setQueryArticleParams({
                    page: 1,
                    limit: 10,
                    search_keyword: '',
                    section_id: item?.id || null
                  });
                }}
                options={sectionSelectOptions}
                clearable
              />
            </div>
          </div>

          <Button onClick={() => navigate('/managment_panel/cms/articles/create')}>
            <Trans>Utwórz artykuł</Trans>
          </Button>
        </div>
        <Table<ICmsArticleItem>
          columns={articleColumns}
          dataSource={articleData?.items || []}
          rowKey="symbol"
          pagination={{
            page: queryArticleParams.page || 1,
            pagesCount: articleData?.total_pages || 1,
            onChange: (page) => setQueryArticleParams((prevState) => ({ ...prevState, page }))
          }}
        />
      </div>
    </div>
  );
};

export default CmsArticles;
