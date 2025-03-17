// slider produktów

import React, { FC } from 'react';
import {
  ButtonBack,
  ButtonNext,
  CarouselProvider,
  DotGroup,
  Slide,
  Slider
} from 'pure-react-carousel';
import { ChevronLeft, ChevronRight } from 'react-bootstrap-icons';
import classnames from 'classnames';

import { useRWD } from 'hooks';
import { IProductListItem } from 'api/types';
import { ProductItem } from 'components/containers';

import styles from 'theme/components/containers/ProductSlider/ProductSlider.module.scss';

// typ danych wejściowych
interface IProps {
  products: IProductListItem[];
  itemsPerSlide: number;
  isArrowHidden?: boolean;
}

const ProductSlider: FC<IProps> = ({ products, itemsPerSlide, isArrowHidden }) => {
  const { isMobile } = useRWD();

  const hideArrows = isArrowHidden || isMobile;
  return (
    <div
      className={classnames(
        styles.wrapperComponent,
        'StylePath-Components-Containers-ProductSlider'
      )}>
      <CarouselProvider
        naturalSlideWidth={0}
        naturalSlideHeight={0}
        totalSlides={products.length}
        visibleSlides={itemsPerSlide}
        disableKeyboard>
        <ButtonBack disabled={hideArrows} className={classnames(styles.navButton, styles.back)}>
          <ChevronLeft />
        </ButtonBack>
        <ButtonNext disabled={hideArrows} className={classnames(styles.navButton, styles.next)}>
          <ChevronRight />
        </ButtonNext>
        <Slider>
          {products.map((product, index) => (
            <Slide key={product.id} index={index} className={styles.slide}>
              <div className={styles.productWrapper}>
                <ProductItem key={product.id} product={product} slide />
              </div>
            </Slide>
          ))}
        </Slider>
        {isMobile && <DotGroup className={styles.trayBar} />}
      </CarouselProvider>
    </div>
  );
};

export default ProductSlider;
