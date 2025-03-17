// baner ze sliderem, odliczaniem i zajawkami aktualności

import React, { useEffect, useState } from 'react';
import { Grid } from '@mui/material';
import { CarouselProvider, Dot, Slide, Slider as CarouselSlider } from 'pure-react-carousel';
import qs from 'query-string';
import {
  addDays,
  addHours,
  addMinutes,
  differenceInDays,
  differenceInHours,
  differenceInMinutes,
  differenceInSeconds
} from 'date-fns';
import { ArrowRight } from 'react-bootstrap-icons';
import classnames from 'classnames';

import { useAppNavigate } from 'hooks';
import { useGetHomeSlider, useGetHomePromotionCountdown, useGetHomeArticlesGroup } from 'api';
import { Link } from 'components/controls';
import { Trans } from 'react-i18next';

import styles from 'theme/pages/Home/components/Banner/Banner.module.scss';

export const Slider = () => {
  const navigate = useAppNavigate();

  // licznik - dane do odliczania promocji
  const [promotionCountdown, setPromotionCountdown] = useState<{
    days: number;
    hours: number;
    minutes: number;
    seconds: number;
  } | null>(null);

  // pobranie danych slidera
  const { data: homeSliderData } = useGetHomeSlider();

  // pobranie listy aktualności
  const { data: homeHomeArticlesGroupData } = useGetHomeArticlesGroup('NEWS');

  // pobranie danych licznika promocji
  const { data: homePromotionCountdownData } = useGetHomePromotionCountdown();

  // aktualizacja licznika co sekundę
  useEffect(() => {
    if (homePromotionCountdownData?.finish_datetime) {
      const interval = setInterval(updatePromotionCountdown, 1000);

      return () => clearInterval(interval);
    }
  }, [homePromotionCountdownData?.finish_datetime]);

  // funkcja ustawiająca licznik na podstawie aktyalnej daty
  const updatePromotionCountdown = () => {
    if (homePromotionCountdownData?.finish_datetime) {
      const currentDate = addMinutes(new Date(), new Date().getTimezoneOffset());
      const endPromotionDate = new Date(homePromotionCountdownData.finish_datetime);

      if (differenceInSeconds(endPromotionDate, currentDate) < 0) {
        return setPromotionCountdown({
          days: 0,
          hours: 0,
          minutes: 0,
          seconds: 0
        });
      }

      const days = differenceInDays(endPromotionDate, currentDate);
      const hours = differenceInHours(endPromotionDate, addDays(currentDate, days));
      const minutes = differenceInMinutes(
        endPromotionDate,
        addHours(addDays(currentDate, days), hours)
      );
      const seconds = differenceInSeconds(
        endPromotionDate,
        addMinutes(addHours(addDays(currentDate, days), hours), minutes)
      );

      setPromotionCountdown({
        days,
        hours,
        minutes,
        seconds
      });
    }
  };

  return (
    <div className={classnames(styles.wrapperComponent, 'StylePath-Pages-Home-components-Banner')}>
      <Grid container>
        <Grid item xs={12} md={8} lg={9}>
          {homeSliderData && (
            <div className={styles.slider}>
              <CarouselProvider
                naturalSlideWidth={0}
                naturalSlideHeight={0}
                totalSlides={homeSliderData?.items.length || 0}
                visibleSlides={1}
                isPlaying>
                <CarouselSlider>
                  {(homeSliderData?.items || []).map((slideData, index) => (
                    <Slide
                      key={slideData.id}
                      index={index}
                      className={styles.slide}
                      style={{ backgroundImage: `url('${slideData.image}')` }}>
                      {slideData.url ? (
                        <a
                          href={slideData.url}
                          target={slideData.new_tab ? '_blank' : '_self'}
                          className={styles.slideLink}
                          rel="noreferrer"
                        />
                      ) : (
                        <Link
                          to={`/products?${qs.stringify(
                            {
                              category_id: slideData.category_id,
                              search_keyword: slideData.search_keyword,
                              sort_method: slideData.sort_method
                            },
                            { skipNull: true }
                          )}`}
                          target={slideData.new_tab ? '_blank' : '_self'}
                          className={styles.slideLink}
                        />
                      )}
                    </Slide>
                  ))}
                </CarouselSlider>

                <div className={styles.sliderLinks}>
                  {(homeSliderData?.items || []).map((slideData, index) => (
                    <Dot slide={index} key={slideData.id}>
                      {slideData.name}
                    </Dot>
                  ))}
                </div>
              </CarouselProvider>
            </div>
          )}
        </Grid>

        <Grid item xs={12} md={4} lg={3}>
          <div className={styles.sidebar}>
            <div className={styles.newsBlock}>
              <Link to="/wip" className={styles.title}>
                {homeHomeArticlesGroupData?.title}
              </Link>
              <div className={styles.newsWrapper}>
                {homeHomeArticlesGroupData?.articles.map((article) => (
                  <div className={styles.news} key={article.id} onClick={() => navigate('/wip')}>
                    <span>{article.title}</span>
                    <ArrowRight />
                  </div>
                ))}
              </div>
            </div>

            {promotionCountdown && (
              <div className={styles.counterWrapper}>
                <div className={styles.title}>{homePromotionCountdownData?.title}</div>
                {promotionCountdown.days > 0 && (
                  <div className={styles.days}>
                    {promotionCountdown.days}{' '}
                    <Trans>{promotionCountdown.days === 1 ? 'dzień' : 'dni'}</Trans>
                  </div>
                )}
                <div className={styles.counter}>
                  <span className={styles.number}>{Math.floor(promotionCountdown.hours / 10)}</span>
                  <span className={styles.number}>{promotionCountdown.hours % 10}</span>
                  <span>:</span>
                  <span className={styles.number}>
                    {Math.floor(promotionCountdown.minutes / 10)}
                  </span>
                  <span className={styles.number}>{promotionCountdown.minutes % 10}</span>
                  <span>:</span>
                  <span className={styles.number}>
                    {Math.floor(promotionCountdown.seconds / 10)}
                  </span>
                  <span className={styles.number}>{promotionCountdown.seconds % 10}</span>
                </div>
              </div>
            )}
          </div>
        </Grid>
      </Grid>
    </div>
  );
};

export default Slider;
