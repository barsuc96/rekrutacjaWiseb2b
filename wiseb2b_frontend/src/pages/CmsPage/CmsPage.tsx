// strona koszyka

import React, { FC, useCallback, useEffect } from 'react';
import { useParams } from 'react-router-dom';
import classnames from 'classnames';
import { useLocation } from 'react-router-dom';

import { HtmlBlock } from 'components/containers/HtmlBlock';
import { Container } from 'components/controls';

import styles from 'theme/pages/Cart/Cart.module.scss';

// typ danych wejściowych
interface IProps {
  sectionId?: string;
  articleId?: string;
}

const CmsPage: FC<IProps> = ({ sectionId, articleId }) => {
  const { pathname } = useLocation();

  // ID aktualnego artykułu
  const { sectionId: paramsSectionId, articleId: paramsArticleId } = useParams();

  // funkcja skrolująca stronę do góry
  const scrollToTop = useCallback(() => document.documentElement.scrollTo({ top: 0, left: 0 }), []);

  // przeskrolowanie strony do góry przy przejściu między stronami
  useEffect(() => {
    scrollToTop();
  }, [pathname]);

  return (
    <div className={classnames(styles.componentWrapper, 'StylePath-Pages-CmsPage')}>
      <Container>
        {(sectionId || paramsSectionId) && (
          <HtmlBlock
            sectionId={sectionId || paramsSectionId || ''}
            articleId={articleId || paramsArticleId}
            withBreadCrumbs
          />
        )}
      </Container>
    </div>
  );
};

export default CmsPage;
