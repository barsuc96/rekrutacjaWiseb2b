// lista kategorii

import React, { FC, useCallback, useEffect, useState } from 'react';
import classnames from 'classnames';
import { PlusCircle, DashCircle } from 'react-bootstrap-icons';
import { Grid } from '@mui/material';

import { useGetLayoutCategories } from 'api';
import { ICategoryListItem, ILayoutCategoriesRequest } from 'api/types';
import { Loader, Link } from 'components/controls';

import styles from 'theme/components/layouts/MainLayout/components/Categories/Categories.module.scss';

// typ danych wejściowych
interface Props {
  onCategoryClick: () => void;
  columnsCount?: number;
  chosenCategoryId?: number;
  searchKeyword?: string;
}

const Categories: FC<Props> = ({
  onCategoryClick,
  columnsCount = 1,
  chosenCategoryId,
  searchKeyword
}) => {
  // parametry zapytania do api
  const [categoriesQuery, setCategoriesQuery] = useState<ILayoutCategoriesRequest>({
    page: 1,
    limit: 999,
    search_keyword: searchKeyword
  });

  // lista ID'ków rozwiniętych kategorii
  const [expandedCategoryIds, setExpandedCategoryIds] = useState<number[]>([]);

  // pobranie listy kategorii
  const { data: categoriesData, isLoading: isCategoriesLoading } =
    useGetLayoutCategories(categoriesQuery);

  // aktualizacja parametrów zapytania do api przy zmianie frazy wyszukiwania
  useEffect(() => {
    setCategoriesQuery((prevState) => ({
      ...prevState,
      search_keyword: searchKeyword
    }));
  }, [searchKeyword]);

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
              onClick={() => onCategoryClick()}
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
        'StylePath-Components-Layouts-MainLayout-Components-Categories'
      )}>
      {isCategoriesLoading && <Loader />}
      <Grid container>
        {categoriesData?.items.map((category, i) => (
          <>
            {category.categories_list &&
              category.show_category_button &&
              category.categories_list.map((category) => (
                <Grid item key={i} sm={Math.floor(12 / columnsCount)}>
                  {renderCategoryTree(category)}
                </Grid>
              ))}
          </>
        ))}
      </Grid>
    </div>
  );
};

export default Categories;
