// sekcja demo na stronie głównej

import React from 'react';
import { useGetHomeHtmlBlock } from 'api';
import classnames from 'classnames';
import styles from 'theme/pages/Home/components/Demo/Demo.module.scss';

export const HomeHtmlBlock = ({
  sectionId,
  articleId
}: {
  sectionId: string;
  articleId: string;
}) => {
  // pobranie bloku html
  const { data: homeHtmlBlockData } = useGetHomeHtmlBlock(sectionId, articleId);

  return (
    <div className={classnames(styles.wrapperComponent, 'StylePath-Pages-Home-components-Demo')}>
      {homeHtmlBlockData?.items.map((item, index) => (
        <div key={index} dangerouslySetInnerHTML={{ __html: item.html_code || '' }} />
      ))}
    </div>
  );
};

export default HomeHtmlBlock;
