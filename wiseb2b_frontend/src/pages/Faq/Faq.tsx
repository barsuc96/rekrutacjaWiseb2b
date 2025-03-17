// strona FAQ

import React, { useEffect, useMemo, useState } from 'react';
import classnames from 'classnames';
import { Trans, useTranslation } from 'react-i18next';

import { reduxActions, useDispatch } from 'store';
import { useGetFaqCategories } from 'api';
import { IFaqRequest } from 'api/types';
import { SearchInput, PageTitle } from 'components/controls';
import { FaqData } from './components';

import styles from 'theme/pages/Faq/Faq.module.scss';

const DashboardFaq = () => {
  const { t } = useTranslation();
  const dispatch = useDispatch();

  // ID;ki rozwiniętych pytań
  const [expandedQuestionIds, setExpandedQuestionIds] = useState<number[]>([]);

  // parametry zapytania o listę faq do API
  const [queryParams, setQueryParams] = useState<IFaqRequest>({
    page: 1,
    limit: 999
  });

  const { data: faqCategoriesData } = useGetFaqCategories({ page: 1, limit: 999 });

  useEffect(() => {
    dispatch(
      reduxActions.setBreadcrumbs([
        { name: t('Dashboard'), path: '/dashboard' },
        { name: t('FAQ') }
      ])
    );
  }, []);

  const currentCategory = useMemo(
    () => faqCategoriesData?.items.find((category) => category.id === queryParams.category_id),
    [queryParams.category_id, faqCategoriesData]
  );

  return (
    <div className={classnames(styles.wrapperComponent, 'StylePath-Pages-Faq')}>
      <PageTitle title={t('FAQ')} />

      <div className={styles.content}>
        <Trans>Jak możemy ci pomóc?</Trans>

        <div className={styles.filtersWrapper}>
          <SearchInput
            placeholder={`${t('Szukaj wpisując frazę')}...`}
            value={queryParams.search_keyword}
            onChange={(value) =>
              setQueryParams((prevState) => ({
                ...prevState,
                search_keyword: value
              }))
            }
          />
        </div>

        {!queryParams.search_keyword && (
          <div className={styles.categories}>
            {faqCategoriesData?.items.map((item) => (
              <div
                key={item.id}
                className={classnames(styles.category, {
                  [styles.active]: queryParams.category_id === item.id
                })}
                onClick={() =>
                  setQueryParams((prevState) => ({
                    ...prevState,
                    category_id: prevState.category_id !== item.id ? item.id : undefined
                  }))
                }>
                <img src={item.icon_url} alt={item.name} /> {item.name}
              </div>
            ))}
          </div>
        )}

        <div className={styles.questions}>
          <div className={styles.title}>
            <Trans>Najczęściej zadawane pytania</Trans>{' '}
            {!!currentCategory && `${t('z działu')} “${currentCategory.name}”`}
          </div>

          {currentCategory?.id && (
            <FaqData
              queryParams={queryParams}
              currentCategoryId={currentCategory?.id}
              expandedQuestionIds={expandedQuestionIds}
              setExpandedQuestionIds={setExpandedQuestionIds}
            />
          )}
        </div>
      </div>
    </div>
  );
};

export default DashboardFaq;
