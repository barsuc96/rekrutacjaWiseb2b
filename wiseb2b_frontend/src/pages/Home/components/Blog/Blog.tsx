import React from 'react';
import { Grid } from '@mui/material';
import classnames from 'classnames';

import { useAppNavigate, useRWD } from 'hooks';
import { useGetHomeArticlesGroup } from 'api';
import { BlockImageLink } from 'components/controls';

import styles from 'theme/pages/Home/components/Blog/Blog.module.scss';
import { CarouselProvider, DotGroup, Slider, Slide } from 'pure-react-carousel';

const Blog = () => {
  const navigate = useAppNavigate();
  const { isMobile } = useRWD();

  // pobranie listy aktualności
  const { data: homeHomeArticlesGroupData } = useGetHomeArticlesGroup('BLOG');

  const [mainArticle, ...articles] = homeHomeArticlesGroupData?.articles || [];

  return isMobile ? (
    <div className={classnames(styles.sliderWrapper)}>
      <CarouselProvider
        naturalSlideWidth={0}
        naturalSlideHeight={0}
        totalSlides={articles.length + (mainArticle ? 1 : 0)}
        visibleSlides={2}
        disableKeyboard>
        <Slider>
          { mainArticle && (
            <Slide index={0} className={styles.slide}>
              <div className={classnames(styles.productWrapper, styles.mainProductWrapper)}>
                <BlockImageLink
                  imageUrl={mainArticle.background_picture}
                  name={
                    <>
                      <span className={styles.strong}>Blog</span>
                      <br />
                      {mainArticle.title}
                    </>
                  }
                  onClick={() => navigate('/wip')}
                  fullHeight
                />
              </div>
            </Slide>
          )}

          {articles.map((article, index) => (
            <Slide index={index + (mainArticle ? 1 : 0)} key={article.id} className={styles.slide}>
              <div className={styles.productWrapper}>
                <BlockImageLink
                  imageUrl={article.background_picture}
                  name={article.title}
                  onClick={() => navigate('/wip')}
                  fullHeight
                />
              </div>
            </Slide>
          ))}
        </Slider>
        <DotGroup className={styles.trayBar} />
      </CarouselProvider>
    </div>
  ) : (
    <div className={classnames(styles.wrapperComponent, 'StylePath-Pages-Home-components-Blog')}>
      <Grid container spacing={2}>
        {mainArticle && (
          <Grid item xs={6} md={3}>
            <BlockImageLink
              imageUrl={mainArticle.background_picture}
              name={
                <>
                  <span className={styles.strong}>Blog</span>
                  <br />
                  {mainArticle.title}
                </>
              }
              onClick={() => navigate('/wip')}
              fullHeight
            />
          </Grid>
        )}
        <Grid item xs={6} md={9}>
          <Grid container spacing={2}>
            {(articles || []).map((article) => (
              <Grid item md={6} key={article.id}>
                <BlockImageLink
                  imageUrl={article.background_picture}
                  name={article.title}
                  onClick={() => navigate('/wip')}
                />
              </Grid>
            ))}
          </Grid>
        </Grid>
      </Grid>
    </div>
  );
};

export default Blog;
