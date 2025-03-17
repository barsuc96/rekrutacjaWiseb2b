// Globalny stan aplikacji
import { configureStore } from '@reduxjs/toolkit';
import {
  authSlice,
  authSliceActions,
  cartSlice,
  cartSliceActions,
  productsSlice,
  productsSliceActions,
  uiSlice,
  uiSliceActions
} from './slices';

import { dynamicPageSlice, dynamicPageSliceActions } from 'plugins/store/slices';

import {
  TypedUseSelectorHook,
  useDispatch as useDispatchRedux,
  useSelector as useSelectorRedux
} from 'react-redux';

export const store = configureStore({
  reducer: {
    auth: authSlice,
    cart: cartSlice,
    products: productsSlice,
    ui: uiSlice,
    dynamicPage: dynamicPageSlice
  }
});

export type RootState = ReturnType<typeof store.getState>;
type AppDispatch = typeof store.dispatch;

export const useDispatch: () => AppDispatch = useDispatchRedux;
export const useSelector: TypedUseSelectorHook<RootState> = useSelectorRedux;

export const reduxActions = {
  ...authSliceActions,
  ...cartSliceActions,
  ...productsSliceActions,
  ...uiSliceActions,
  ...dynamicPageSliceActions
};
