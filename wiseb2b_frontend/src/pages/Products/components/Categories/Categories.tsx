// lista kategorii

import React, { FC, useCallback, useEffect, useState } from 'react';
import classnames from 'classnames';
import { PlusCircle, DashCircle, ExclamationCircle } from 'react-bootstrap-icons';
import { Grid } from '@mui/material';
import ReactMarkdown from 'react-markdown';
import { useTranslation } from 'react-i18next';

import { useGetProductsCategories } from 'api';
import { ICategoryListItem, IProductsCategoriesRequest, IProductsRequest } from 'api/types';
import { Loader, Link } from 'components/controls';

import styles from 'theme/pages/Products/components/Categories/Categories.module.scss';

// typ danych wejściowych
interface Props {
  columnsCount?: number;
  chosenCategoryId?: number;
  searchKeyword?: string;
  productsQueryParams: IProductsRequest;
}

const Categories: FC<Props> = ({ columnsCount = 1, chosenCategoryId, productsQueryParams }) => {
  // parametry zapytania do api
  const [categoriesQuery, setCategoriesQuery] = useState<IProductsCategoriesRequest>({
    ...productsQueryParams,
    page: 1,
    limit: 999
  });

  const { t } = useTranslation();

  // lista ID'ków rozwiniętych kategorii
  const [expandedCategoryIds, setExpandedCategoryIds] = useState<number[]>([]);

  // pobranie listy kategorii
  const { data: categoriesData, isLoading: isCategoriesLoading } =
    useGetProductsCategories(categoriesQuery);

  // aktualizacja parametrów zapytania do api przy zmianie parametrów zapytaqnia o produkty
  useEffect(() => {
    if (chosenCategoryId) {
      if (!expandedCategoryIds.includes(chosenCategoryId)) {
        setExpandedCategoryIds((prev) => [...prev, chosenCategoryId]);
      }
    }
    setCategoriesQuery({
      ...productsQueryParams,
      page: 1,
      limit: 999
    });
  }, [productsQueryParams]);

  // funkcja renderująca drzewo kategorii
  const renderCategoryTree = useCallback(
    (category: ICategoryListItem) => {
      // funkcja rekurencyjna renderująca  listę wraz z rozwiniętymi podkategoriami
      const renderTree = (categories: ICategoryListItem[] = [], level = 0) =>
        categories.map((category) => (
          <div
            key={category.id}
            className={classnames(styles.category, { [styles.main]: level === 0 })}>
            {category.subcategories_total_count > 0 && (
              <>
                {expandedCategoryIds.includes(category.id) ||
                category.subcategories.some((item) => item.id === chosenCategoryId) ? (
                  <DashCircle
                    onClick={() =>
                      setExpandedCategoryIds((prevState) =>
                        prevState.filter((categoryId) => categoryId !== category.id)
                      )
                    }
                  />
                ) : (
                  <PlusCircle
                    onClick={() =>
                      setExpandedCategoryIds((prevState) => [...prevState, category.id])
                    }
                  />
                )}
              </>
            )}
            <Link
              to={`/${category.url_link}`}
              className={classnames({ [styles.chosen]: category.id === chosenCategoryId })}>
              {category.name} <span className={styles.counter}>({category.products_count})</span>
            </Link>

            {category.subcategories_total_count > 0 &&
              (expandedCategoryIds.includes(category.id) ||
                category.subcategories.some((item) => item.id === chosenCategoryId)) &&
              renderTree(category.subcategories, level + 1)}
          </div>
        ));

      return renderTree([category]);
    },
    [expandedCategoryIds, chosenCategoryId]
  );

  return (
    <div
      className={classnames(
        styles.wrapperComponent,
        'StylePath-Pages-Products-components-Categories'
      )}>
      {isCategoriesLoading && <Loader />}
      <Grid container>
        {categoriesData?.items.length ? (
          categoriesData?.items.map((category) => (
            <Grid item key={category.id} sm={Math.floor(12 / columnsCount)}>
              {renderCategoryTree(category)}
            </Grid>
          ))
        ) : (
          <div className={styles.info}>
            <ExclamationCircle />
            <span>
              <ReactMarkdown disallowedElements={['p']} unwrapDisallowed>
                {t('PRODUCTS_SIDEBAR_NO_CATEGORIES_INFO')}
              </ReactMarkdown>
            </span>
          </div>
        )}
      </Grid>
    </div>
  );
};

export default Categories;
