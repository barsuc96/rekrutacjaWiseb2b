// box z produktami

import React, { FC, useMemo, useState } from 'react';
import { Trans } from 'react-i18next';
import { ChevronUp } from 'react-bootstrap-icons';
import classnames from 'classnames';

import { useGetHomeProductsBox } from 'api';
import { ProductSlider } from 'components/containers';

import styles from 'theme/pages/Home/components/ProductsBox/ProductsBox.module.scss';
import { useRWD } from 'hooks';

interface IProps {
  productBoxId: string;
}

const ProductsBox: FC<IProps> = ({ productBoxId }) => {
  const { data: homeProductsBoxData } = useGetHomeProductsBox(productBoxId);

  const [seeAlso, setSeeAlso] = useState(false);

  const { isMobile, isDesktop, isTablet } = useRWD();

  const onlySlider = useMemo(
    () => !homeProductsBoxData?.image && !homeProductsBoxData?.see_also,
    [homeProductsBoxData?.image, homeProductsBoxData?.see_also]
  );

  return (
    <div
      className={classnames(
        styles.wrapperComponent,
        { [styles.onlySlider]: onlySlider, [styles.withImage]: homeProductsBoxData?.image },
        'StylePath-Pages-Home-components-ProductBox'
      )}>
      <div
        className={styles.block}
        style={{
          backgroundImage: `url('${homeProductsBoxData?.image}')`,
          backgroundRepeat: 'no-repeat',
          backgroundSize: 'cover'
        }}>
        <div className={styles.title}>{homeProductsBoxData?.name}</div>

        {homeProductsBoxData?.see_also && !isMobile && (
          <div className={styles.menuCategory}>
            <div className={styles.label}>
              <Trans>Zobacz także</Trans>:
            </div>
            <div dangerouslySetInnerHTML={{ __html: homeProductsBoxData.see_also }} />
          </div>
        )}
      </div>

      <div className={styles.content}>
        <ProductSlider
          products={homeProductsBoxData?.products || []}
          itemsPerSlide={
            isMobile ? 1 : onlySlider ? (isDesktop ? 4 : 6) : isTablet ? 4 : isDesktop ? 3 : 4
          }
        />
      </div>
      {isMobile && homeProductsBoxData?.see_also && homeProductsBoxData.see_also.length && (
        <>
          <div
            onClick={() => setSeeAlso((prev) => !prev)}
            className={classnames(styles.seeAlsoButton, {
              [styles.active]: seeAlso
            })}>
            <Trans>Więcej produktów z oferty</Trans>
            <ChevronUp className={styles.arrow} />
          </div>

          {seeAlso && (
            <div className={styles.seeAlso}>
              <div dangerouslySetInnerHTML={{ __html: homeProductsBoxData.see_also }} />
            </div>
          )}
        </>
      )}
    </div>
  );
};

export default ProductsBox;
