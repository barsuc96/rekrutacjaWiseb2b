import React, { useMemo } from 'react';
import { useParams } from 'react-router-dom';
import classnames from 'classnames';

import { useGetCmsSections, usePostCmsArticle } from 'api';

import { EditArticle, ArticleForm } from './components';

import styles from 'theme/pages/Cms/Cms.module.scss';

const CmsArticle = () => {
  // ID artykułu (przekształcony na int)
  const { id: articleIdParam } = useParams();
  const articleId = useMemo(() => parseInt(articleIdParam || ''), [articleIdParam]);

  // pobranie listy sekcji
  const { data: sectionData } = useGetCmsSections({
    page: 1,
    limit: 999
  });

  // tworzenie nowego artykułu
  const { mutateAsync: createArticle, isLoading: isCreatingArticle } = usePostCmsArticle();

  return (
    <div className={classnames(styles.componentWrapper, 'StylePath-Pages-CmsArticle')}>
      {articleId ? (
        <EditArticle activeArticle={articleId} sectionList={sectionData?.items || []} />
      ) : (
        <ArticleForm
          mutate={createArticle}
          isLoading={isCreatingArticle}
          sectionList={sectionData?.items || []}
        />
      )}
    </div>
  );
};

export default CmsArticle;
