// sekcja demo na stronie głównej

import React, { FC, useEffect } from 'react';

import { useGetCmsSectionArticle, useGetCmsSectionArticles } from 'api';

import { HtmlBlockMultipleArticle, HtmlBlockSingleArticle } from './components';

interface IProps {
  sectionId: string;
  articleId?: string;
  withBreadCrumbs?: boolean;
}

export const HtmlBlock: FC<IProps> = ({ sectionId, articleId, withBreadCrumbs }) => {
  // pobranie bloku html
  const { data: singleArticleData, refetch: refetchSingleData } = useGetCmsSectionArticle(
    sectionId,
    articleId || '',
    {
      enabled: false
    }
  );

  // pobranie bloku html
  const { data: multipleArticleData, refetch: refetchMultipleData } = useGetCmsSectionArticles(
    sectionId,
    { fetchArticleFields: true },
    {
      enabled: false
    }
  );

  useEffect(() => {
    if (articleId) {
      refetchSingleData();

      return;
    }

    refetchMultipleData();
  }, [articleId, sectionId]);

  return (
    <>
      {articleId ? (
        <HtmlBlockSingleArticle content={singleArticleData} withBreadCrumbs={withBreadCrumbs} />
      ) : (
        <HtmlBlockMultipleArticle content={multipleArticleData} />
      )}
    </>
  );
};

export default HtmlBlock;
