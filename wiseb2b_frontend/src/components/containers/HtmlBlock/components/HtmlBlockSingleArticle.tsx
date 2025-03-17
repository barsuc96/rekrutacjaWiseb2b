// sekcja demo na stronie głównej

import React, { FC, useEffect } from 'react';
import classnames from 'classnames';
import { useRWD } from 'hooks';

import { reduxActions, useDispatch } from 'store';
import { IArticleSection } from 'api/types';
import Slider from 'components/containers/Slider';
import ProductsBox from 'components/containers/ProductsBox';
import Blog from 'components/containers/Blog';
import { Breadcrumbs } from 'components/controls';

import styles from 'theme/components/containers/HtmlBlock/HtmlBlock.module.scss';

interface IProps {
  content?: IArticleSection;
  withBreadCrumbs?: boolean;
}

export const HtmlBlockSingleArticle: FC<IProps> = ({ content, withBreadCrumbs }) => {
  const { isMobile } = useRWD();

  // zmapowanie zmiennej isMobile na kody pochodzące z response layout
  const device = isMobile ? 'Mobile' : 'Desktop';

  const dispatch = useDispatch();
  // Ustawienie breadcrumbs'ów (przy renderowaniu strony)
  useEffect(() => {
    dispatch(
      reduxActions.setBreadcrumbs([
        {
          name: content?.title || '',
          path: undefined
        }
      ])
    );
  }, [content?.title]);

  const renderContent = (item: IArticleSection) => {
    if (item.layouts?.includes(device)) {
      return item.article_fields.map((field) => renderArticleContent(field.value));
    }

    return null;
  };

  const renderArticleContent = (value: string) => {
    if (value?.includes('{{Slider}}')) {
      return <Slider />;
    }

    if (value?.includes('{{Blog}}')) {
      return <Blog />;
    }

    if (value?.includes('{{ProductsBox')) {
      const start = value.indexOf('{{') + 2;
      const end = value.indexOf('}}');
      const code = value.slice(start, end);
      const attribute = code.replace('ProductsBox productBoxId="', '').replace('"', '');
      return <ProductsBox productBoxId={attribute} />;
    }

    return <div dangerouslySetInnerHTML={{ __html: value }} />;
  };

  return (
    <div
      className={classnames(styles.wrapperComponent, 'StylePath-Components-Containers-HtmlBlock')}>
      {withBreadCrumbs && (
        <div>
          <Breadcrumbs />
          <div className={styles.title}>
            <h1>{content?.title || ''}</h1>
          </div>
        </div>
      )}
      {content && renderContent(content)}
    </div>
  );
};

export default HtmlBlockSingleArticle;
