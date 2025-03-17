// Globalny stan aplikacji - koszyk
import { createSlice, PayloadAction } from '@reduxjs/toolkit';

// typ cart
interface ICartSlice {
  // id domyślnego koszyka
  currentCartId: number | null;
}

// dane inicjalizacyjne
const initialState: ICartSlice = {
  currentCartId: null
};

export const cartSlice = createSlice({
  name: 'cart',
  initialState,
  reducers: {
    setCurrentCartId: (state, action: PayloadAction<ICartSlice['currentCartId']>) => {
      state.currentCartId = action.payload;
    }
  }
});

export const cartSliceActions = cartSlice.actions;

export default cartSlice.reducer;
