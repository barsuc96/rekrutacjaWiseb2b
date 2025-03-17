// główna wyszukiwarka produktów

import React, { useEffect, useRef, useState } from 'react';
import { Grid } from 'react-bootstrap-icons';
import { useLocation } from 'react-router-dom';
import classnames from 'classnames';

import { reduxActions, useDispatch, useSelector } from 'store';
import { useAppNavigate } from 'hooks';
import { useGetSearchCategories, useGetSearchProducts } from 'api';

import MobileOverlay from 'components/containers/Search/MobileOverlay';

const Search = () => {
  const dispatch = useDispatch();
  const navigate = useAppNavigate();
  const location = useLocation();

  const [showCategories, setShowCategories] = useState<boolean>(false);

  // ref popover'a
  const wrapperRef = useRef<HTMLDivElement>(null);

  // czy popover jest widoczny (focus na inpucie)
  const [isOpen, setIsOpen] = useState(false);

  // fraza wyszukiwania
  const [searchKeyword, setSearchKeyword] = useState('');

  // zapisana globalna fraza wyszukiwana - domyślna wartość tutaj i parametr na stronie listy produktów
  const { searchKeyword: globalSearchKeyboard } = useSelector((state) => state.products);

  // pobranie listy produktów
  const { data: productsData, isLoading: isProductsDataLoading } = useGetSearchProducts(
    { page: 1, limit: 999, search_keyword: '' },
    { enabled: false }
  );

  // pobranie listy kategorii
  const { data: categoriesData, isLoading: isCategoriesDataLoading } = useGetSearchCategories({
    page: 1,
    limit: 999,
    search_keyword: ''
  });

  const setGlobalKeyword = () => {
    setSearchKeyword(globalSearchKeyboard);
  };

  // nasłuchiwanie na zmiany globalnej frazy wyszukiwania
  useEffect(() => {
    setGlobalKeyword();
  }, [globalSearchKeyboard]);

  // zamknięcie okienka po zmianie url'a
  useEffect(() => {
    setIsOpen(false);
  }, [location]);

  // funkcja wywona po klinięciu w linki wewnątrz wyszukiwarki
  const applyProductsParameters = (categoryId?: number, url_link?: string) => {
    dispatch(reduxActions.setSearchKeyword(searchKeyword));
    dispatch(reduxActions.setCategoryId(categoryId));
    setIsOpen(false);
    navigate(
      // `/products?${categoryId ? `category_id=${categoryId}&` : ''}search_keyword=${searchKeyword}`

      `/${url_link}${searchKeyword ? `?search_keyword=${searchKeyword}` : ''}`
    );
  };

  return (
    <div className={classnames('StylePath-Components-Containers-MobileMenu')} ref={wrapperRef}>
      <div onClick={() => setIsOpen(true)}>
        <Grid />
      </div>

      {isOpen && (
        <MobileOverlay
          categoriesData={categoriesData}
          isCategoriesDataLoading={isCategoriesDataLoading}
          setShowCategories={setShowCategories}
          showCategories={showCategories}
          productsData={productsData}
          isProductsDataLoading={isProductsDataLoading}
          searchKeyword={searchKeyword}
          setGlobalKeyword={setGlobalKeyword}
          isFocus={isOpen}
          setIsFocus={setIsOpen}
          applyProductsParameters={applyProductsParameters}
        />
      )}
    </div>
  );
};

export default Search;
