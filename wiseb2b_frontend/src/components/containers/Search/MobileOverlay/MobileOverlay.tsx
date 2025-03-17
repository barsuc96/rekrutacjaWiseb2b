import React, { useState } from 'react';
import { ChevronRight } from 'react-bootstrap-icons';
import classnames from 'classnames';
import { Grid, Switch } from '@mui/material';
import { Trans } from 'react-i18next';
import { Link } from 'react-router-dom';

import { Loader } from 'components/controls';
import { ICategoryListItem, IPaginationResponse, IProductListItem } from 'api/types';

import styles from 'theme/components/containers/Search/MobileOverlay/MobileOverlay.module.scss';

type IResponseCategory = IPaginationResponse<ICategoryListItem>;
type IResponseProduct = IPaginationResponse<IProductListItem>;

type Props = {
  setGlobalKeyword: () => void;
  searchKeyword: string;
  isProductsDataLoading: boolean;
  isCategoriesDataLoading: boolean;
  productsData?: IResponseProduct;
  categoriesData?: IResponseCategory;
  setIsFocus: (state: boolean) => void;
  isFocus: boolean;
  setShowCategories: (state: boolean) => void;
  showCategories: boolean;
  applyProductsParameters: (categoryId?: number, url_link?: string) => void;
};

const MobileOverlay = ({
  setGlobalKeyword,
  searchKeyword,
  showCategories,
  setShowCategories,
  applyProductsParameters,
  isProductsDataLoading,
  isCategoriesDataLoading,
  productsData,
  categoriesData,
  setIsFocus
}: Props) => {
  const [stage, setStage] = useState<number[]>([]);

  const filteredCategory = () => {
    let newCategory: Partial<ICategoryListItem> | undefined = {
      ...categoriesData,
      subcategories: categoriesData?.items
    };
    stage.forEach((id) => {
      newCategory = newCategory?.subcategories?.find((item) => item.id == id);
    });
    return newCategory;
  };

  interface IItemComponent {
    category: ICategoryListItem;
  }

  const CategoryItem = ({ category }: IItemComponent) => {
    return (
      <button
        onClick={() => {
          if (!category.subcategories_total_count) {
            applyProductsParameters(category.id, category.url_link);
          }
        }}
        key={category.id}
        className={styles.category}>
        <div onClick={() => applyProductsParameters(category.id, category.url_link)}>
          {category.name}
          <span>({category.products_count})</span>
        </div>
        {category.subcategories_total_count ? (
          <ChevronRight
            onClick={() => setStage((prev: number[]) => [...prev, category.id])}
            className={styles.arrow}
          />
        ) : null}
      </button>
    );
  };

  return (
    <div
      className={classnames(styles.overlay, 'StylePath-Components-Containers-Search-MobileOverlay')}
      onClick={() => {
        setGlobalKeyword();
        setIsFocus(false);
      }}>
      <div
        className={styles.searchResults}
        onClick={(event) => {
          event.stopPropagation();
        }}>
        <div className={styles.products}>
          {isProductsDataLoading && (
            <Grid item xs={12}>
              <Loader />
            </Grid>
          )}
          <div className={classnames(styles.switcherWrapper, { [styles.active]: !showCategories })}>
            <span className={styles.switchLabel}>
              <Trans>W produktach</Trans>
            </span>
            <Switch
              className={styles.switcher}
              checked={!showCategories}
              onClick={() => setShowCategories(false)}
            />
            <span className={styles.switchLabel}>
              <Trans>W katalogu produktów</Trans>
            </span>
          </div>
          {!searchKeyword ? (
            <div className={styles.lastSearched}>
              <div className={styles.lastSearchedHeader}>
                <Trans>Ostatnio szukane</Trans>
              </div>
            </div>
          ) : (
            productsData?.items.map((item) => {
              return (
                <Link
                  to={`products/${item.id}`}
                  state={{ searchKeywords: searchKeyword }}
                  className={styles.productItem}
                  key={item.id}>
                  <img src={item.images[0]?.thumb} />
                  <div className={styles.productDescription}>
                    <div className={styles.productTitle}> {item.title}</div>
                    <div className={styles.productInfo}>
                      <div>Index:</div>
                      <div>{item.index}</div>
                      {item.producer_name && (
                        <>
                          <div className={styles.borderInfo} />
                          <div>
                            <Trans>Producent</Trans>:
                          </div>
                          <div>{item.producer_name}</div>
                        </>
                      )}
                    </div>
                  </div>
                </Link>
              );
            })
          )}
        </div>

        <div className={classnames(styles.categories, { [styles.showCategories]: showCategories })}>
          <div
            onClick={() => applyProductsParameters()}
            className={classnames(styles.title, styles.mainTitle, {
              [styles.notTitle]: stage?.length
            })}>
            <Trans>Kategorie</Trans>
          </div>
          {stage?.length !== 0 && (
            <div className={classnames(styles.title, { [styles.mainTitle]: !stage.length })}>
              <>
                <ChevronRight
                  onClick={() => setStage((prev) => prev.slice(0, -1))}
                  className={styles.backIcon}
                />
                <div
                  onClick={() =>
                    applyProductsParameters(filteredCategory()?.id, filteredCategory().url_link)
                  }>
                  {filteredCategory()?.name}
                  <span>({filteredCategory()?.products_count})</span>
                </div>
              </>
            </div>
          )}
          <div className={styles.list}>
            {isCategoriesDataLoading && (
              <Grid item xs={12}>
                <Loader />
              </Grid>
            )}

            {filteredCategory()?.subcategories?.map((category) => (
              // eslint-disable-next-line react/prop-types
              <CategoryItem key={category.id} category={category} />
            ))}
          </div>
        </div>
      </div>
    </div>
  );
};

export default MobileOverlay;
