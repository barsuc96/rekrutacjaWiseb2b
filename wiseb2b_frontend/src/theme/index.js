import { createTheme } from '@mui/material/styles';

export const theme = createTheme({
  breakpoints: {
    values: {
      xs: 0,
      sm: 600,
      md: 900,
      lg: 1200,
      xl: 1600
    }
  },
  palette: {
    primary: {
      main: '#173EC7'
    },
    secondary: {
      main: '#4917C7'
    }
  }
});
