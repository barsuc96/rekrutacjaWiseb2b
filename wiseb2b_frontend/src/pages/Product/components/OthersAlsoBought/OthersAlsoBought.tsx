// slider z produktami kupionymi przez innych

import React, { FC, useCallback } from 'react';
import { Trans } from 'react-i18next';
import { ButtonBack, ButtonNext, CarouselProvider, Slide, Slider } from 'pure-react-carousel';
import classnames from 'classnames';
import { ChevronLeft, ChevronRight } from 'react-bootstrap-icons';

import { useRWD } from 'hooks';
import { IProductListItem } from 'api/types';
import { useGetProductsBoughtByOthers } from 'api';
import { Loader, Link } from 'components/controls';

import styles from 'theme/pages/Product/components/OthersAlsoBought/OthersAlsoBought.module.scss';

// typ danych wejściowych
interface IProps {
  productId: number;
}

const OthersAlsoBought: FC<IProps> = ({ productId }) => {
  const { isMobile } = useRWD();

  // pobranie listy produktów oglądanych prze3z innnych
  const { data: productsBoughtByOthersData, isLoading: isProductsBoughtByOthersLoading } =
    useGetProductsBoughtByOthers(productId, {
      page: 1,
      limit: 999
    });

  // pobranie domyślnej jednośtki
  const getDefaultUnit = useCallback(
    (product: IProductListItem) =>
      product.units.find((unit) => unit.id === product.default_unit_id),
    []
  );

  return (
    <div
      className={classnames(
        styles.wrapperComponent,
        'StylePath-Page-Product-components-OthersAlsoBought'
      )}>
      <div className={styles.title}>
        <Trans>Inni kupili także</Trans>
      </div>

      {isProductsBoughtByOthersLoading && <Loader />}

      {!!productsBoughtByOthersData && (
        <CarouselProvider
          naturalSlideWidth={0}
          naturalSlideHeight={0}
          totalSlides={productsBoughtByOthersData?.items.length || 0}
          visibleSlides={isMobile ? 1 : 4}
          className={styles.slider}>
          <ButtonBack disabled={isMobile} className={classnames(styles.navButton, styles.back)}>
            <ChevronLeft />
          </ButtonBack>
          <ButtonNext disabled={isMobile} className={classnames(styles.navButton, styles.next)}>
            <ChevronRight />
          </ButtonNext>
          <Slider>
            {productsBoughtByOthersData?.items.map((product, index) => (
              <Slide key={product.id} index={index} className={styles.slide}>
                <div className={styles.productWrapper}>
                  <Link to={`/${product.url_link}`} className={styles.product}>
                    <div
                      className={styles['product-image']}
                      style={{ backgroundImage: `url(${product.images[0]?.min})` }}
                    />
                    <div className={styles.description}>
                      {product.title}
                      <div className={styles.prices}>
                        <div className={styles.net}>
                          <strong>{getDefaultUnit(product)?.price_net_formatted}</strong> netto
                        </div>
                        <div className={styles.gross}>
                          {getDefaultUnit(product)?.price_net_formatted} brutto
                        </div>
                      </div>
                    </div>
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

export default OthersAlsoBought;
