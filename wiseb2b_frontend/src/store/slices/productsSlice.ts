// Globalny stan aplikacji - produkty
import { createSlice, PayloadAction } from '@reduxjs/toolkit';

type ISearchFilterCategory = {
  id: number | null;
  name: string;
  url_link: string | null;
};

// typ products
interface IProductsSlice {
  // główna fraza wyszukiwania
  searchKeyword: string;
  searchFilterCategory: ISearchFilterCategory;
  // wybrana kategoria
  categoryId?: number;
  hidePrices?: boolean;
}

// dane inicjalizacyjne
const initialState: IProductsSlice = {
  searchKeyword: '',
  searchFilterCategory: {
    id: null,
    name: '',
    url_link: null
  },
  hidePrices: !!localStorage.getItem('hidePrices')
};

export const productsSlice = createSlice({
  name: 'products',
  initialState,
  reducers: {
    setSearchKeyword: (state, action: PayloadAction<IProductsSlice['searchKeyword']>) => {
      state.searchKeyword = action.payload;
    },
    setCategoryId: (state, action: PayloadAction<IProductsSlice['categoryId']>) => {
      state.categoryId = action.payload;
    },
    setSearchFilterCategory: (
      state,
      action: PayloadAction<IProductsSlice['searchFilterCategory']>
    ) => {
      state.searchFilterCategory = action.payload;
    },
    setHidePrices: (state, action: PayloadAction<IProductsSlice['hidePrices']>) => {
      state.hidePrices = action.payload;
    }
  }
});

export const productsSliceActions = productsSlice.actions;

export default productsSlice.reducer;
