/* eslint-disable @typescript-eslint/no-explicit-any */
// Globalny stan aplikacji - produkty
import { createSlice, PayloadAction } from '@reduxjs/toolkit';

// typ komponentu
interface IComponent {
  [componentSymbol: string]: any;
}

// typ dynamicPage
interface IDynamicPage {
  [dynamicPageSymbol: string]: IComponent;
}

//typ dynamicPageSlice

interface IDynamicPageSlice {
  contextData: IDynamicPage;
  contextFilters: any;
}

// dane inicjalizacyjne
const initialState: IDynamicPageSlice = { contextData: {}, contextFilters: {} };

export const dynamicPageSlice = createSlice({
  name: 'dynamicPage',
  initialState,
  reducers: {
    setContextData: (state, action: PayloadAction<IDynamicPageSlice['contextData']>) => {
      const pageSymbol = Object.keys(action.payload)?.[0];
      const componentSymbol = Object.keys(action.payload?.[pageSymbol])?.[0];
      state.contextData = {
        ...state.contextData,
        [pageSymbol]: {
          ...state.contextData[pageSymbol],
          [componentSymbol]: action.payload?.[pageSymbol]?.[componentSymbol]
        }
      };
    },

    setContextFilters: (state, action: PayloadAction<IDynamicPageSlice['contextFilters']>) => {
      state.contextFilters = {
        ...state.contextFilters,
        ...action.payload
      };
    },

    setFieldValue: (state, action: any) => {
      const { pageSymbol, componentSymbol, fieldName, fieldValue } = action.payload;
      if (pageSymbol && componentSymbol && fieldName) {
        state.contextData[pageSymbol][componentSymbol][fieldName] = fieldValue;
      }
    }
  }
});

export const dynamicPageSliceActions = dynamicPageSlice.actions;

export default dynamicPageSlice.reducer;
