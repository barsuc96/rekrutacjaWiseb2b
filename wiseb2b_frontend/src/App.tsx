// Główny plik aplikacji - inicjalizacje zależności globalnych
import React, { useRef } from 'react';
import { BrowserRouter } from 'react-router-dom';
import { QueryClient, QueryClientProvider } from 'react-query';
import { ThemeProvider } from '@mui/material/styles';
import { SnackbarProvider } from 'notistack';
import { ReactQueryDevtools } from 'react-query/devtools';
import { Provider as ReduxProvider } from 'react-redux';
import { Helmet } from 'react-helmet';

import { store } from 'store';
import { theme } from 'theme';

import Routes from './routes';

import 'theme/global.scss';

// Konfiguracja klienta react-query
export const queryClient = new QueryClient({
  defaultOptions: {
    queries: {
      refetchOnWindowFocus: false,
      retry: false
    }
  }
});

const App = () => {
  const notistackRef = useRef<SnackbarProvider>(null);

  return (
    <React.StrictMode>
      <Helmet>
        <title>WiseB2B</title>
        <link rel="canonical" href={window.location.href} />
      </Helmet>
      <QueryClientProvider client={queryClient}>
        <ThemeProvider theme={theme}>
          <ReduxProvider store={store}>
            <SnackbarProvider
              action={(key) => (
                <span
                  onClick={() => notistackRef.current?.closeSnackbar(key)}
                  style={{ position: 'absolute', top: 0, left: 0, right: 0, bottom: 0 }}
                />
              )}
              ref={notistackRef}
              anchorOrigin={{ vertical: 'top', horizontal: 'right' }}
              disableWindowBlurListener>
              <BrowserRouter>
                <Routes />
              </BrowserRouter>
              <ReactQueryDevtools initialIsOpen={false} position="top-left" />
            </SnackbarProvider>
          </ReduxProvider>
        </ThemeProvider>
      </QueryClientProvider>
    </React.StrictMode>
  );
};

export default App;
