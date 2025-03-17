/* eslint-disable @typescript-eslint/no-unused-vars */
// strona listy produktów

import React, { FC, useEffect, useState, useMemo } from 'react';
import { Helmet } from 'react-helmet';
import qs from 'query-string';
import { Grid } from '@mui/material';
import { Trans, useTranslation } from 'react-i18next';
import { Link, useLocation } from 'react-router-dom';
import classnames from 'classnames';
import { ChevronLeft, Funnel } from 'react-bootstrap-icons';

import { reduxActions, useDispatch, useSelector } from 'store';
import {
  useGetProducts,
  useGetProductsFiltersAdditional,
  useGetProductsFiltersMain,
  useGetProductsSortMethods,
  useGetProductsTitle,
  useGetCmsSectionArticle,
  useGetProductsBreadcrumbs
} from 'api';
import { IProductsRequest, IProductsSortMethod } from 'api/types';
import { useAppNavigate, useRWD, useScrollDown, usePrevious } from 'hooks';
import { FiltersSidebar, FiltersTopBar, FiltersOverlay, Categories } from './components';
import { MobileProductItem, ProductItem, Search } from 'components/containers';
import { Breadcrumbs, Container, Loader, Pagination, Select } from 'components/controls';
import GridSwitcher, { IProps as IGridSwitcherProps } from 'components/controls/GridSwitcher';

import styles from 'theme/pages/Products/Products.module.scss';

interface IFilter {
  filter_id: string;
  filter_value: string;
}

// typ danych wejściowych
interface IProps {
  categoryId?: string;
  mode?: 'PROMOTIONS' | 'NEWS' | 'BESTSELLERS';
}

const Products: FC<IProps> = ({ categoryId, mode }) => {
  const { t } = useTranslation();
  const dispatch = useDispatch();
  const navigate = useAppNavigate();
  const location = useLocation();

  const { category_id } = qs.parse(location.search);

  const getPosition = (string: string, subString: string, index: number) => {
    return string.split(subString, index).join(subString).length;
  };

  const urlPrefix = location.pathname.slice(0, getPosition(location.pathname, '/', 2));

  const { isMobile, isTablet } = useRWD();

  const { isScrollDown } = useScrollDown();

  // kategoria pochodząca z url resolvera
  const urlResolverCategoryId = parseInt(categoryId || '0');

  const categoryIdQueryParam = urlResolverCategoryId
    ? { category_id: urlResolverCategoryId }
    : typeof category_id === 'string'
    ? { category_id: parseInt(category_id) }
    : {};

  // poprzednia wartość kategorii
  const prevCategoryId = usePrevious(categoryIdQueryParam.category_id);

  // globalnie ustawiona kategoria i fraza wyszukiwarki
  const { searchKeyword: globalSearchKeyword } = useSelector((state) => state.products);

  // poprzednia wartość kategorii
  const prevGlobalSearchKeyword = usePrevious(globalSearchKeyword);

  // Aktualne filtry
  const [queryFilters, setQueryFilters] = useState<IFilter[]>([]);

  const [isFilterMobile, setIsFilterMobile] = useState(false);

  const localStorageGridType = localStorage.getItem('gridType') as 'grid' | 'line' | null;

  // Widok listy (boxy/linie)
  const [gridType, setGridType] = useState<IGridSwitcherProps['type']>(
    localStorageGridType || 'line'
  );

  // Parametry zapytania do API
  const [productsQuery, setProductsQuery] = useState<IProductsRequest>({
    page: 1,
    limit: 20,
    search_keyword: '',
    filter_attributes: '',
    sort_method: 'NAME_ASC',
    mode: mode,
    ...categoryIdQueryParam,
    ...qs.parseUrl(window.location.href, { parseNumbers: false }).query
  });

  // Pobieranie breadcrumbs
  const { data: productsBreadcrumbsData, isFetching: isBredcrumbsLoading } =
    useGetProductsBreadcrumbs(productsQuery);

  // pobranie listy filtrów
  const { data: mainFiltersData, isLoading: isMainFiltersLoading } = useGetProductsFiltersMain({
    ...productsQuery,
    page: 1,
    limit: 999
  });

  // pobranie listy filtrów
  const { data: additionalFiltersData, isLoading: isAdditionalFiltersLoading } =
    useGetProductsFiltersAdditional({ page: 1, limit: 999 });

  // Pobranie listy produktów
  const { data: productsData, isLoading: isLoadingProducts } = useGetProducts(productsQuery, {
    keepPreviousData: true
  });

  // Pobranie nagłówka
  const { data: productsTitleData } = useGetProductsTitle(productsQuery);

  // Pobranie opcji sortowania
  const { data: productsSortingMethodsData } = useGetProductsSortMethods({
    page: 1,
    limit: 999
  });

  const { data: seoDescriptionData, refetch: refetchSeoDescription } = useGetCmsSectionArticle(
    'CATEGORY_DESCRIPTION',
    categoryIdQueryParam.category_id?.toString() || '',
    {
      enabled: false
    }
  );

  const urlSearchKeyword = qs.parseUrl(location.search).query.search_keyword;
  // Uaktualnienie frazy wyszukwania (w globalnym stanie) po zmianie tej danej w url'u
  useEffect(() => {
    typeof urlSearchKeyword === 'string' &&
      dispatch(reduxActions.setSearchKeyword(urlSearchKeyword));
  }, [urlSearchKeyword]);

  // ustawienie globalnej kategorii
  useEffect(() => {
    if (categoryIdQueryParam.category_id !== prevCategoryId && categoryIdQueryParam.category_id) {
      dispatch(reduxActions.setCategoryId(categoryIdQueryParam.category_id));
    }
  }, [categoryIdQueryParam]);

  // usuwanie globalnej kategorii
  useEffect(() => {
    return () => {
      dispatch(reduxActions.setCategoryId(undefined));
      dispatch(reduxActions.setSearchFilterCategory({ id: null, name: '', url_link: null }));
    };
  }, []);

  useEffect(() => {
    if (categoryIdQueryParam.category_id) {
      refetchSeoDescription();
    }
  }, [categoryIdQueryParam.category_id]);

  // Zmiana url'a przy zmianie parametrów zapytania do API
  useEffect(() => {
    const { limit, mode, category_id, ...restQuery } = productsQuery;

    const navigationUrl = categoryId
      ? `${location.pathname.replace(urlPrefix, '')}?${qs.stringify(restQuery, {
          skipEmptyString: true
        })}`
      : `${location.pathname.replace(urlPrefix, '')}?${qs.stringify(
          { ...restQuery, category_id },
          {
            skipEmptyString: true
          }
        )}`;

    navigate(navigationUrl, { replace: true });
  }, [
    productsQuery.page,
    productsQuery.filter_attributes,
    productsQuery.sort_method,
    productsQuery.search_keyword
  ]);

  // Ustawienie aktywnych filtrów z url'a (podczas wejścia na stronę)
  useEffect(() => {
    typeof productsQuery.search_keyword === 'string' &&
      dispatch(reduxActions.setSearchKeyword(productsQuery.search_keyword));
    setQueryFilters(
      productsQuery.filter_attributes
        ?.split('|')
        .filter((item) => item)
        .map((queryFilter) => {
          const queryFilterArray = queryFilter.split('=');
          return {
            filter_id: queryFilterArray[0],
            filter_value: queryFilterArray[1]
          };
        }) || []
    );
  }, []);

  // Ustawienie breadcrumbs'ów (przy renderowaniu strony)
  useEffect(() => {
    dispatch(
      reduxActions.setBreadcrumbs(
        productsBreadcrumbsData
          ? productsBreadcrumbsData.items.map((item) => ({
              name: item.name,
              path: item.category_id ? `/${item.url}` : undefined
            }))
          : []
      )
    );
  }, [productsBreadcrumbsData?.items]);

  // aktualizacja parametrów zapytania przy zmianie kategorii
  useEffect(() => {
    setProductsQuery((prevState) => ({
      ...prevState,
      mode: undefined,
      filter_attributes: '',
      category_id: categoryIdQueryParam.category_id
    }));
  }, [categoryIdQueryParam.category_id]);

  // aktualizacja parametrów zapytania przy zmianie frazy wyszukiwania
  useEffect(() => {
    setProductsQuery((prevState) => ({
      ...prevState,
      mode: undefined,
      filter_attributes: '',
      search_keyword: globalSearchKeyword
    }));
  }, [globalSearchKeyword]);

  useEffect(() => {
    if (
      globalSearchKeyword &&
      prevGlobalSearchKeyword &&
      globalSearchKeyword !== prevGlobalSearchKeyword
    ) {
      setProductsQuery((prevState) => ({
        ...prevState,
        page: 1
      }));
    }
  }, [globalSearchKeyword, prevGlobalSearchKeyword, productsQuery?.page]);

  // Uaktualnienie filtrów w zapytaniu do API
  useEffect(() => {
    setProductsQuery((prevState) => ({
      ...prevState,
      filter_attributes: queryFilters
        .map((filter) => `${filter.filter_id}=${filter.filter_value}`)
        .join('|')
    }));
  }, [queryFilters]);

  // Uaktualnienie kategorii (w globalnym stanie) po zmianie tej danej w url'u
  useEffect(() => {
    if (mode) {
      setProductsQuery((prevState) => ({
        ...prevState,
        category_id: undefined,
        limit: 30,
        mode
      }));
    }
  }, [mode]);

  // Funkcja aktualizująa filtry (w stanie komponentu i zapytaniu do API)
  const updateQueryFilters = (filters: IFilter[]) => {
    setQueryFilters(filters);
    setProductsQuery((prevState) => ({
      ...prevState,
      filter_attributes: filters
        .map((filter) => `${filter.filter_id}=${filter.filter_value}`)
        .join('|'),
      page: 1
    }));
  };

  return (
    <div className={classnames(styles.wrapperComponent, 'StylePath-Pages-Products')}>
      {productsTitleData && (
        <Helmet>
          <title>{productsTitleData.name}</title>
          <link rel="canonical" href={window.location.href} />
        </Helmet>
      )}
      <Breadcrumbs isLoading={isBredcrumbsLoading} />

      {isFilterMobile ? (
        isMobile && (
          <FiltersOverlay
            categoryId={categoryIdQueryParam.category_id}
            onChange={updateQueryFilters}
            queryFilters={queryFilters}
            filtersData={[
              ...(mainFiltersData?.items || []),
              ...(additionalFiltersData?.items || [])
            ]}
            close={() => setIsFilterMobile(false)}
            productsSortingMethodsData={productsSortingMethodsData}
            productsQuery={productsQuery}
            setSort={(id: string) =>
              setProductsQuery({
                ...productsQuery,
                sort_method: id,
                page: 1
              })
            }
          />
        )
      ) : (
        <Container>
          <div className={styles.content}>
            {!isMobile && (
              <div className={styles.categories}>
                <span className={styles.Title}>{t('Kategorie')}</span>

                <Categories
                  searchKeyword={globalSearchKeyword}
                  chosenCategoryId={categoryIdQueryParam.category_id}
                  productsQueryParams={productsQuery}
                />

                {isAdditionalFiltersLoading && <Loader />}
                <FiltersSidebar
                  categoryId={categoryIdQueryParam.category_id}
                  onChange={updateQueryFilters}
                  queryFilters={queryFilters}
                  filtersData={additionalFiltersData?.items}
                />
              </div>
            )}

            <div className={styles.list}>
              <div className={styles.searchWrapper}>
                <Search />
              </div>

              <div
                className={classnames(styles.bottomBar, { [styles.isScrollDown]: !isScrollDown })}>
                <div className={styles.title}>
                  {productsTitleData && (
                    <>
                      <Link className={styles.arrow} to="/">
                        <ChevronLeft />
                      </Link>{' '}
                      <h1>{productsTitleData.name}</h1>{' '}
                      <span>({productsTitleData.products_count})</span>
                    </>
                  )}
                </div>

                {seoDescriptionData?.article_fields?.[0]?.value && (
                  <h2
                    className={styles.seoBlock}
                    dangerouslySetInnerHTML={{
                      __html: seoDescriptionData?.article_fields?.[0].value
                    }}
                  />
                )}

                <div className={styles.filterWrapper}>
                  <div className={styles.filterInfo}>
                    <span>
                      <Trans>Wyniki wyszukiwania</Trans>
                    </span>
                    <span>({productsData?.total_count})</span>
                  </div>

                  <div onClick={() => setIsFilterMobile(true)} className={styles.filterButton}>
                    <Funnel />{' '}
                    <div>
                      <Trans>Filtruj</Trans>/<Trans>Sortuj</Trans>
                    </div>
                    <div className={styles.filterBox}>0</div>
                  </div>
                </div>
              </div>

              {!isMobile && (
                <Grid item sm={12}>
                  <div className={styles.actionsTopBar}>
                    {isMainFiltersLoading && <Loader />}
                    <FiltersTopBar
                      onChange={updateQueryFilters}
                      queryFilters={queryFilters}
                      filtersData={mainFiltersData?.items}
                    />
                    <div>
                      <div className={styles.sortingSelectWrapper}>
                        <Select<IProductsSortMethod>
                          onChange={(sortMethod) =>
                            sortMethod &&
                            setProductsQuery({
                              ...productsQuery,
                              sort_method: sortMethod.id,
                              page: 1
                            })
                          }
                          value={productsQuery.sort_method}
                          options={
                            productsSortingMethodsData?.items.map((item) => ({
                              value: item.id,
                              label: item.name,
                              item
                            })) || []
                          }
                          placeholder={t('Sortowanie')}
                        />
                      </div>

                      <GridSwitcher type={gridType} onChange={setGridType} />
                    </div>
                  </div>
                </Grid>
              )}

              <Grid
                container
                columnSpacing="12px"
                rowSpacing="12px"
                itemScope
                itemType="http://schema.org/ItemList">
                {productsData?.items.map((product) => (
                  <Grid
                    key={product.id}
                    item
                    md={gridType === 'line' ? 12 : isTablet ? 4 : 3}
                    itemProp="itemListElement"
                    itemScope
                    itemType="http://schema.org/ListItem">
                    {isMobile ? (
                      <MobileProductItem
                        product={product}
                        categoryId={categoryIdQueryParam.category_id}
                        searchKeywords={globalSearchKeyword}
                      />
                    ) : (
                      <ProductItem
                        product={product}
                        line={gridType === 'line'}
                        categoryId={categoryIdQueryParam.category_id}
                        searchKeywords={globalSearchKeyword}
                      />
                    )}
                  </Grid>
                ))}

                {isLoadingProducts && (
                  <Grid item sm={12} className={styles.loadingWrapper}>
                    <Loader />
                  </Grid>
                )}
              </Grid>
            </div>
          </div>
          <Pagination
            pagesCount={productsData?.total_pages || 1}
            page={productsQuery.page || 1}
            onChange={(page) => setProductsQuery({ ...productsQuery, page })}
          />
          {seoDescriptionData?.article_fields?.[1]?.value && (
            <h2
              className={styles.seoBlock}
              dangerouslySetInnerHTML={{ __html: seoDescriptionData?.article_fields?.[1].value }}
            />
          )}
        </Container>
      )}
    </div>
  );
};

export default Products;
