// slider z ostatnio oglądanymi produktami

import React, { FC } from 'react';
import { Trans } from 'react-i18next';
import { ButtonBack, ButtonNext, CarouselProvider, Slide, Slider } from 'pure-react-carousel';
import { ChevronLeft, ChevronRight } from 'react-bootstrap-icons';
import classnames from 'classnames';

import { useRWD } from 'hooks';
import { useGetProductsLastViewed } from 'api';
import { Loader, Link } from 'components/controls';

import styles from 'theme/pages/Product/components/LastViewed/LastViewed.module.scss';

// typ danych wejściowych
interface IProps {
  productId: number;
}

const LastViewed: FC<IProps> = ({ productId }) => {
  const { isMobile } = useRWD();

  // pobranie listy ostanio oglądanych produktów
  const { data: productsLastViewedData, isLoading: isProductsLastViewedLoading } =
    useGetProductsLastViewed(productId, {
      page: 1,
      limit: 999
    });

  return (
    <div
      className={classnames(
        styles.wrapperComponent,
        'StylePath-Pages-Product-components-LastViewed'
      )}>
      <div className={styles.title}>
        <Trans>Ostatnio oglądane</Trans>
      </div>

      {isProductsLastViewedLoading && <Loader />}

      {!!productsLastViewedData && (
        <CarouselProvider
          naturalSlideWidth={0}
          naturalSlideHeight={0}
          totalSlides={productsLastViewedData?.items.length || 0}
          visibleSlides={isMobile ? 2.5 : 6}
          className={styles.slider}>
          <ButtonBack disabled={isMobile} className={classnames(styles.navButton, styles.back)}>
            <ChevronLeft />
          </ButtonBack>
          <ButtonNext disabled={isMobile} className={classnames(styles.navButton, styles.next)}>
            <ChevronRight />
          </ButtonNext>
          <Slider>
            {productsLastViewedData?.items.map((product, index) => (
              <Slide key={product.id} index={index} className={styles.slide}>
                <div className={styles.productWrapper}>
                  <Link to={`/${product.url_link}`} className={styles.product}>
                    <div
                      className={styles['product-image']}
                      style={{ backgroundImage: `url(${product.images[0]?.min})` }}
                    />
                    <div className={styles.description}>{product.title}</div>
                  </Link>
                </div>
              </Slide>
            ))}
          </Slider>
        </CarouselProvider>
      )}
    </div>
  );
};

export default LastViewed;
