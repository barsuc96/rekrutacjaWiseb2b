// Globalny stan aplikacji - autoryzacja

import { createSlice, PayloadAction } from '@reduxjs/toolkit';
import { queryClient } from 'App';
import { IUserProfile } from 'api/types';
import { axios } from 'api';

// nazwa pola z tokenem w localStorage
const JWT_TOKEN = 'JWT_TOKEN';

// nazwa pola z overloginem w localStorage
const OVERLOGIN_USER_ID = 'OVERLOGIN_USER_ID';

//typ auth
interface IAuthSlice {
  // flaga czy jest dostępny profil otwarty
  isOpenProfileReady: boolean;
  // flaga czy użytkownik jest zalogowany
  isAuthenticated: boolean;
  // aktualny token
  token: string | null;
  // profil zalogowanego użytkownika
  profile: IUserProfile | null;
  // login przelogowanego użytkownika
  overloginUserId: number | null;
}

// dane inicjalizacyjne
const initialState: IAuthSlice = {
  isOpenProfileReady:
    !!(process.env.REACT_APP_OPEN_PROFILE_LOGIN && process.env.REACT_APP_OPEN_PROFILE_PASSWORD) &&
    !localStorage.getItem(JWT_TOKEN),
  isAuthenticated: !!localStorage.getItem(JWT_TOKEN),
  token: localStorage.getItem(JWT_TOKEN),
  profile: null,
  overloginUserId: localStorage.getItem(OVERLOGIN_USER_ID)
    ? Number(localStorage.getItem(OVERLOGIN_USER_ID))
    : null
};

const apiLogout = () => axios.post('auth/logout', {});

export const authSlice = createSlice({
  name: 'auth',
  initialState,
  reducers: {
    // logowanie
    signIn: (state, action: PayloadAction<{ token: string }>) => {
      localStorage.setItem(JWT_TOKEN, action.payload.token);
      state.isAuthenticated = true;
      state.isOpenProfileReady = false;
      state.token = action.payload.token;
      queryClient.removeQueries();
    },
    signOut: (state) => {
      state.profile && state.profile.role !== 'ROLE_OPEN_PROFILE' && apiLogout();
      localStorage.removeItem(JWT_TOKEN);
      localStorage.removeItem(OVERLOGIN_USER_ID);
      state.isAuthenticated = false;
      state.token = null;
      state.profile = null;
      state.overloginUserId = null;
      state.isOpenProfileReady = !!(
        process.env.REACT_APP_OPEN_PROFILE_LOGIN && process.env.REACT_APP_OPEN_PROFILE_PASSWORD
      );
      // usunięcie całego cache z react-query
      queryClient.removeQueries();
    },
    setProfile: (state, action: PayloadAction<IUserProfile>) => {
      state.profile = action.payload;
    },
    setOverlogin: (state, action: PayloadAction<IAuthSlice['overloginUserId']>) => {
      queryClient.removeQueries();
      action.payload
        ? localStorage.setItem(OVERLOGIN_USER_ID, String(action.payload))
        : localStorage.removeItem(OVERLOGIN_USER_ID);
      state.overloginUserId = action.payload;
      state.profile = null;
    },
    setIsOpenProfileReady: (state, action: PayloadAction<IAuthSlice['isOpenProfileReady']>) => {
      state.isOpenProfileReady = action.payload;
    }
  }
});

export const authSliceActions = authSlice.actions;

export default authSlice.reducer;
