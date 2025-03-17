import React from 'react';
import classnames from 'classnames';

import { useAppNavigate, useRWD } from 'hooks';
import { useGetHomeArticlesGroup } from 'api';
import { BlockImageLink } from 'components/controls';

import styles from 'theme/pages/Home/components/KnowledgeBase/KnowledgeBase.module.scss';
import { CarouselProvider, DotGroup, Slide, Slider } from 'pure-react-carousel';

export const KnowledgeBase = () => {
  const navigate = useAppNavigate();

  const { isMobile } = useRWD();

  // pobranie listy aktualności
  const { data: homeHomeArticlesGroupData } = useGetHomeArticlesGroup('KNOWLEDGE_BASE');

  return (
    <div
      id="knowledgeBase"
      className={classnames(
        styles.wrapperComponent,
        'StylePath-Pages-Home-components-KnowledgeBase'
      )}>
      <h2 className={styles.title}>{homeHomeArticlesGroupData?.title}</h2>
      <div className={styles.sliderWrapper}>
        <CarouselProvider
          naturalSlideWidth={0}
          naturalSlideHeight={0}
          totalSlides={homeHomeArticlesGroupData?.articles.length || 0}
          visibleSlides={isMobile ? 1 : 3}
          disableKeyboard>
          <Slider>
            {homeHomeArticlesGroupData?.articles.map((article, index) => (
              <Slide index={index} key={article.id} className={styles.slide}>
                <div className={classnames(styles.productWrapper, styles.mainProductWrapper)}>
                <BlockImageLink
                  imageUrl={article.background_picture}
                  name={article.title}
                  key={article.id}
                  onClick={() => navigate('/wip')}
                />
                </div>
              </Slide>
            ))}
          </Slider>
         { ((homeHomeArticlesGroupData?.articles?.length || 0)>3 || isMobile ) && <DotGroup className={styles.trayBar} /> }
        </CarouselProvider>

        {/* {homeHomeArticlesGroupData?.articles.map((article) => (
            <BlockImageLink
              imageUrl={article.background_picture}
              name={article.title}
              key={article.id}
              onClick={() => navigate('/wip')}
            />
        ))} */}
      </div>
    </div>
  );
};

export default KnowledgeBase;
