// hook obsługującu wyskakujące notyfikacje

import { useSnackbar, VariantType } from 'notistack';
import { useCallback } from 'react';

export const useNotifications = () => {
  const { enqueueSnackbar } = useSnackbar();

  const showMessage = useCallback(
    (message: string, variant: VariantType) =>
      enqueueSnackbar(message, {
        variant
      }),
    []
  );

  return {
    showSuccessMessage: (message: string) => showMessage(message, 'success'),
    showErrorMessage: (message: string) => showMessage(message, 'error'),
    showWarningMessage: (message: string) => showMessage(message, 'warning')
  };
};
