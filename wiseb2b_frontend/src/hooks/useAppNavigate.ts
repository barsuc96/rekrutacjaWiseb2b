// nakładka ns useNavigate uwzględniająca aktualnie wybrany język

import { useCallback } from 'react';
import { useNavigate } from 'react-router-dom';
import { useTranslation } from 'react-i18next';

interface NavigateOptions {
  replace?: boolean;
  state?: any;
}

export const useAppNavigate = () => {
  const navigate = useNavigate();
  const { i18n } = useTranslation();

  const appNavigate = useCallback(
    (to: string, options?: NavigateOptions) => navigate(`/${i18n.language}${to}`, options),
    []
  );

  return appNavigate;
};
