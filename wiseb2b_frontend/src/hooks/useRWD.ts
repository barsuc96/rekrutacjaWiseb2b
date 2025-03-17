// hook zwracający informacje i mediawueries (np. czu jest aktualny szerokość mobilna)

import { useTheme } from '@mui/material/styles';
import useMediaQuery from '@mui/material/useMediaQuery';

export const useRWD = () => {
  const theme = useTheme();
  const isMobile = useMediaQuery(theme.breakpoints.down('md'));
  const isTablet = useMediaQuery(theme.breakpoints.down('lg'));
  const isDesktop = useMediaQuery(theme.breakpoints.down('xl'));

  return {
    isMobile,
    isTablet,
    isDesktop
  };
};
