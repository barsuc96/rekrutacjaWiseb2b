import React, { FC, useState, useEffect } from 'react';
import classnames from 'classnames';

import { useGetCmsArticle, usePutCmsArticle } from 'api';
import { Loader } from 'components/controls';

import { ICmsSectionItem } from 'api/types';
import { ArticleForm } from '../ArticleForm';

import styles from 'theme/pages/Cms/Cms.module.scss';

// typ danych wejściowych
interface IProps {
  activeArticle: number;
  sectionList: ICmsSectionItem[];
}

const EditArticle: FC<IProps> = ({ activeArticle, sectionList }) => {
  // parametry zapytania API
  const [queryParams, setQueryParams] = useState({
    language: ''
  });

  //pobieranie danych artykułu
  const {
    data: articleData,
    isLoading: isArticleLoading,
    refetch: refetchArticleData
  } = useGetCmsArticle(activeArticle, queryParams);

  // edycja danych artykułu
  const { mutateAsync: updateArticle, isLoading: isUpdatingArticle } =
    usePutCmsArticle(activeArticle);

  useEffect(() => {
    if (queryParams.language) {
      refetchArticleData();
    }
  }, [queryParams.language]);

  return (
    <div
      className={classnames(styles.wrapperComponent, 'StylePath-Pages-Cms-components-ArticleItem')}>
      {isArticleLoading ? (
        <Loader />
      ) : (
        <ArticleForm
          mutate={updateArticle}
          sectionList={sectionList}
          isLoading={isUpdatingArticle}
          data={articleData}
          setQueryParams={setQueryParams}
        />
      )}
    </div>
  );
};

export default EditArticle;
