// Globalny stan aplikacji - ui
import { createSlice, PayloadAction } from '@reduxjs/toolkit';

// typ ui
interface IUiSlice {
  // dane konfiguracyjne dla breadrumbs
  breadcrumbs: { name: string; path?: string }[];
  // dane wyświetlane jako wystakujące komunikaty
  notification: {
    message: string;
    variant: 'error' | 'success';
  } | null;
  notificationModal: {
    title: string;
    content: string;
  } | null;
  isMobileMenu: boolean;
}

// dane inicjalizacyjne
const initialState: IUiSlice = {
  breadcrumbs: [],
  notification: null,
  notificationModal: null,
  isMobileMenu: false
};

export const uiSlice = createSlice({
  name: 'ui',
  initialState,
  reducers: {
    setBreadcrumbs: (state, action: PayloadAction<IUiSlice['breadcrumbs']>) => {
      state.breadcrumbs = action.payload;
    },
    setNotification: (state, action: PayloadAction<IUiSlice['notification']>) => {
      state.notification = action.payload;
    },
    setNotificationModal: (state, action: PayloadAction<IUiSlice['notificationModal']>) => {
      state.notificationModal = action.payload;
    },
    setIsMobileMenu: (state, action: PayloadAction<IUiSlice['isMobileMenu']>) => {
      state.isMobileMenu = action.payload;
    }
  }
});

export const uiSliceActions = uiSlice.actions;

export default uiSlice.reducer;
