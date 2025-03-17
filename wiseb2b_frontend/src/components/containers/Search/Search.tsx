// główna wyszukiwarka produktów

import React, {
  useCallback,
  useEffect,
  useRef,
  useState,
  useMemo,
  Dispatch,
  SetStateAction
} from 'react';
import { ArrowLeft } from 'react-bootstrap-icons';
import { useTranslation } from 'react-i18next';
import { debounce } from 'lodash';
import { useLocation } from 'react-router-dom';
import classnames from 'classnames';

import { reduxActions, useDispatch, useSelector } from 'store';
import { useAppNavigate, useRWD, usePrevious } from 'hooks';
import { useGetSearchCategories, useGetSearchProducts, useGetSearchFilterCategoryList } from 'api';
import { Select } from 'components/controls';

import styles from 'theme/components/containers/Search/Search.module.scss';
import DesktopOverlay from './DektopOverlay';
import MobileOverlay from './MobileOverlay';

import { SearchIcon, XIcon } from 'assets/icons';

type IQueryParams = {
  page: number;
  limit: number;
  search_keyword: string;
  categoryId: number | null;
};

const Search = ({
  trigerReset,
  setTriggerReset
}: {
  trigerReset?: boolean;
  setTriggerReset?: Dispatch<SetStateAction<boolean>>;
}) => {
  const { t } = useTranslation();
  const dispatch = useDispatch();
  const navigate = useAppNavigate();
  const location = useLocation();

  const [showCategories, setShowCategories] = useState<boolean>(false);

  const { isMobile } = useRWD();

  // ref popover'a
  const wrapperRef = useRef<HTMLDivElement>(null);

  // czy popover jest widoczny (focus na inpucie)
  const [isFocus, setIsFocus] = useState(false);

  // fraza wyszukiwania
  const [searchKeyword, setSearchKeyword] = useState('');

  // parametry wyszukiwania
  const [queryParams, setQueryParams] = useState<IQueryParams>({
    page: 1,
    limit: 5,
    search_keyword: '',
    categoryId: null
  });

  // zapisana globalna fraza wyszukiwana - domyślna wartość tutaj i parametr na stronie listy produktów
  const {
    searchKeyword: globalSearchKeyboard,
    searchFilterCategory: globalSearchFilterCategory,
    categoryId: globalCategoryId
  } = useSelector((state) => state.products);

  // poprzednia kategoria
  const prevGlobalCategoryId = usePrevious(globalCategoryId);

  // pobranie listy produktów
  const {
    data: productsData,
    isLoading: isProductsDataLoading,
    refetch: refetchProductsData
  } = useGetSearchProducts(queryParams, { enabled: false });

  // pobranie listy kategorii
  const {
    data: categoriesData,
    isLoading: isCategoriesDataLoading,
    refetch: refetchCategoriesData
  } = useGetSearchCategories(
    {
      page: 1,
      limit: 999,
      search_keyword: queryParams.search_keyword
    },
    { enabled: false }
  );

  // pobieranie listy filtrów kategorii
  const { data: filtersCategoryData } = useGetSearchFilterCategoryList(
    {
      page: 1,
      limit: 999,
      categoryId: globalCategoryId
    },
    {
      keepPreviousData: true,
      onSuccess: () => {
        setQueryParams((prevState) => ({ ...prevState, categoryId: globalCategoryId || null }));
      }
    }
  );

  // funcja aktualizująca frazę wyszukiwania (sekunda opóźnienia dla optymalizacji ilości zapytań do api)
  const searchKeywordDebounce = useCallback(
    debounce(
      (phrase) => setQueryParams((prevState) => ({ ...prevState, search_keyword: phrase })),
      1000
    ),
    []
  );

  // reset lokalnego stanu
  useEffect(() => {
    if (trigerReset) {
      handleClearButton();
      setTriggerReset?.(false);
    }
  }, [trigerReset, searchKeyword]);

  useEffect(() => {
    const category = filtersCategoryData?.items.find((item) => item.id === globalCategoryId);

    category && dispatch(reduxActions.setSearchFilterCategory(category));
  }, [globalCategoryId, filtersCategoryData]);

  // nasłuchiwanie na zmiany frazy wyszukiwania w lokalnym inpucie
  useEffect(() => {
    searchKeywordDebounce(searchKeyword);
  }, [searchKeyword]);

  const setGlobalKeyword = () => {
    setSearchKeyword(globalSearchKeyboard);
  };

  // nasłuchiwanie na zmiany globalnej frazy wyszukiwania
  useEffect(() => {
    setGlobalKeyword();
  }, [globalSearchKeyboard]);

  // pobranie kategorii po zmianie frazy wyszukiwania
  useEffect(() => {
    if (queryParams.search_keyword) {
      if (isMobile) {
        refetchCategoriesData();
      }

      refetchProductsData();
    }
  }, [queryParams]);

  // zamknięcie okienka po zmianie url'a
  useEffect(() => {
    dispatch(reduxActions.setSearchKeyword(searchKeyword));
    setIsFocus(false);
  }, [location]);

  // funkcja wywona po klinięciu w linki wewnątrz wyszukiwarki
  const applyProductsParameters = () => {
    const { id, url_link } = globalSearchFilterCategory;
    dispatch(reduxActions.setSearchKeyword(searchKeyword));
    dispatch(reduxActions.setCategoryId(id || undefined));
    setIsFocus(false);

    id
      ? navigate(`/${url_link}?search_keyword=${searchKeyword}`)
      : navigate(`/products?search_keyword=${searchKeyword}`);
  };

  // obsługa przycisku kasowania frazy
  const handleClearButton = () => {
    setSearchKeyword('');
    dispatch(reduxActions.setSearchKeyword(''));
  };

  // opcje dropdownu kategorii
  const filterCategoryOptions = useMemo(
    () =>
      filtersCategoryData?.items.map((category) => ({
        value: category.id,
        label: category.name,
        item: category
      })) || [],
    [filtersCategoryData]
  );

  return (
    <div
      className={classnames(
        styles.wrapperComponent,
        { [styles.focus]: isFocus },
        'StylePath-Components-Containers-Search'
      )}
      ref={wrapperRef}>
      <ArrowLeft
        className={classnames(styles.icon, { [styles.hide]: !(isFocus && isMobile) })}
        onClick={() => setIsFocus(false)}
      />
      <input
        placeholder={t('Wyszukaj')}
        value={searchKeyword}
        onChange={(event) => setSearchKeyword(event.target.value)}
        onKeyUp={(event) => {
          event.key === 'Enter' &&
            (applyProductsParameters(),
            event.target instanceof HTMLInputElement && event.target.blur());
        }}
        onClick={() => {
          setIsFocus(true);
          if (isMobile) {
            setShowCategories(true);
          }
        }}
        onFocus={() => {
          setIsFocus(true);
          if (isMobile) {
            setShowCategories(true);
          }
        }}
      />
      {searchKeyword && (
        <button onClick={() => handleClearButton()} className={styles.clearSearchKeyword}>
          <XIcon />
        </button>
      )}

      {!isMobile && (
        <div className={styles.categories}>
          <Select
            options={filterCategoryOptions}
            placeholder={t('Wybierz kategorie')}
            variant="borderless"
            value={queryParams.categoryId}
            onChange={(item) => {
              setQueryParams((prevState) => ({ ...prevState, categoryId: item?.id || null }));
              item && dispatch(reduxActions.setSearchFilterCategory(item));
            }}
          />
        </div>
      )}
      <div className={styles.icon} onClick={() => applyProductsParameters()}>
        <SearchIcon />
      </div>
      {isFocus &&
        (isMobile ? (
          <MobileOverlay
            categoriesData={categoriesData}
            isCategoriesDataLoading={isCategoriesDataLoading}
            setShowCategories={setShowCategories}
            showCategories={showCategories}
            productsData={productsData}
            isProductsDataLoading={isProductsDataLoading}
            searchKeyword={searchKeyword}
            setGlobalKeyword={setGlobalKeyword}
            isFocus={isFocus}
            setIsFocus={setIsFocus}
            applyProductsParameters={applyProductsParameters}
          />
        ) : (
          <DesktopOverlay
            productsData={productsData}
            isProductsDataLoading={isProductsDataLoading}
            searchKeyword={searchKeyword}
            setGlobalKeyword={setGlobalKeyword}
            setIsFocus={setIsFocus}
            applyProductsParameters={applyProductsParameters}
            wrapperRef={wrapperRef}
          />
        ))}
    </div>
  );
};

export default Search;
