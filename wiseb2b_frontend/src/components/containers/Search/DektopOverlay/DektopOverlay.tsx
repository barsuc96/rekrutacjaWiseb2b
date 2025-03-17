import React, { RefObject } from 'react';
import { ArrowRight } from 'react-bootstrap-icons';
import classnames from 'classnames';
import { Grid } from '@mui/material';
import { Trans } from 'react-i18next';

import { Loader } from 'components/controls';
import ProductItem from 'components/containers/ProductItem';
import { IPaginationResponse, IProductListItem } from 'api/types';

import styles from 'theme/components/containers/Search/DesktopOverlay/DesktopOverlay.module.scss';

type IResponseProduct = IPaginationResponse<IProductListItem>;

type Props = {
  setGlobalKeyword: () => void;
  searchKeyword: string;
  isProductsDataLoading: boolean;
  productsData?: IResponseProduct;
  setIsFocus: (state: boolean) => void;
  applyProductsParameters: (categoryId?: number) => void;
  wrapperRef: RefObject<HTMLDivElement>;
};

const DesktopOverlay = ({
  searchKeyword,
  applyProductsParameters,
  isProductsDataLoading,
  productsData,
  setIsFocus,
  wrapperRef
}: Props) => {
  return (
    <div
      className={classnames(
        styles.overlay,
        'StylePath-Components-Containers-Search-DesktopOverlay'
      )}
      onClick={() => {
        setIsFocus(false);
      }}>
      {searchKeyword && (
        <div
          style={{
            left: `${Math.round(wrapperRef.current?.getBoundingClientRect().x || 0)}px`,
            width: wrapperRef.current?.getBoundingClientRect().width
          }}
          className={styles.searchResults}
          onClick={(event) => {
            event.stopPropagation();
          }}>
          <div className={styles.products}>
            <div className={styles.list}>
              <div>
                {isProductsDataLoading && (
                  <Grid item xs={12}>
                    <Loader />
                  </Grid>
                )}

                {productsData && productsData.items.length === 0 ? (
                  <div className={styles.notFound}>
                    <h3>
                      <Trans>Brak wyników</Trans>
                    </h3>
                    <span>
                      <Trans>Zmień kryteria wyszukiwania</Trans>
                    </span>
                  </div>
                ) : (
                  productsData?.items?.map((product: IProductListItem) => (
                    <Grid item key={product.id}>
                      <ProductItem
                        imageStretch
                        product={product}
                        line
                        searchKeywords={searchKeyword}
                        isSearch
                      />
                    </Grid>
                  ))
                )}
              </div>
            </div>
            {(productsData?.items || []).length > 0 && (
              <button className={styles.showAllButton} onClick={() => applyProductsParameters()}>
                <Trans>Zobacz wszystkie produkty</Trans> <ArrowRight />
              </button>
            )}
          </div>
        </div>
      )}
    </div>
  );
};

export default DesktopOverlay;
