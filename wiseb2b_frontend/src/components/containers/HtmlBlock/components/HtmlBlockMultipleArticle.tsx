// sekcja demo na stronie głównej

import React, { FC } from 'react';
import parser, { DomElement, HTMLReactParserOptions } from 'html-react-parser';
import { useRWD } from 'hooks';

import { ICmsSectionArticlesResponse, IArticleSection } from 'api/types';
import Slider from 'components/containers/Slider';
import ProductsBox from 'components/containers/ProductsBox';
import SelectedCategories from 'components/containers/SelectedCategories';
import HomeSubelements from 'components/containers/HomeSubelements';
import HomeArticlesGroup from 'components/containers/HomeArticlesGroup';
import HomeHtmlBlock from 'components/containers/HomeHtmlBlock';
import Blog from 'components/containers/Blog';
import { Video } from 'components/controls';

interface IProps {
  content?: ICmsSectionArticlesResponse;
}

export const HtmlBlockMultipleArticle: FC<IProps> = ({ content }) => {
  const { isMobile } = useRWD();

  // zmapowanie zmiennej isMobile na kody pochodzące z response layout
  const device = isMobile ? 'Mobile' : 'Desktop';

  const parserOptions: HTMLReactParserOptions = {
    replace: (domNode: DomElement) => {
      const { name: domName, attribs: domAttrs, children = [] } = domNode;

      if (domName === 'video') {
        return <Video domAttrs={domAttrs}>{children}</Video>;
      }
    }
  };

  const renderContent = (item: IArticleSection) => {
    if (item.layouts?.includes(device)) {
      return item.article_fields.map((field) => renderArticleContent(field.value));
    }

    return null;
  };

  const renderArticleContent = (value: string) => {
    if (typeof value !== 'string') {
      return null;
    }

    if (value.includes('{{Slider}}')) {
      return <Slider />;
    }

    if (value?.includes('{{Blog}}')) {
      return <Blog />;
    }

    if (value.includes('{{SelectedCategories}}')) {
      return <SelectedCategories />;
    }

    if (value.includes('{{HomeSubelements}}')) {
      return <HomeSubelements />;
    }

    if (value.includes('{{ProductsBox')) {
      const start = value.indexOf('{{') + 2;
      const end = value.indexOf('}}');
      const code = value.slice(start, end);
      const attribute = code.replace('ProductsBox productBoxId="', '').replace('"', '');
      return <ProductsBox productBoxId={attribute} />;
    }

    if (value.includes('{{HomeArticlesGroup')) {
      const start = value.indexOf('{{') + 2;
      const end = value.indexOf('}}');
      const code = value.slice(start, end);
      const attribute = code.replace('HomeArticlesGroup articleGroupId="', '').replace('"', '');
      return <HomeArticlesGroup articleGroupId={attribute} />;
    }

    if (value.includes('{{HomeHtmlBlock')) {
      const start = value.indexOf('{{') + 2;
      const end = value.indexOf('}}');
      const code = value.slice(start, end);
      const attributes = JSON.parse(code.replace('HomeHtmlBlock params=', ''));
      return <HomeHtmlBlock sectionId={attributes.sectionId} articleId={attributes.articleId} />;
    }

    return <div className="htmlBlock">{parser(value, parserOptions)}</div>;
  };

  return <>{content && content.items.map((item) => renderContent(item))}</>;
};

export default HtmlBlockMultipleArticle;
