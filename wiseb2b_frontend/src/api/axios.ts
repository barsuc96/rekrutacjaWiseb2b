// konfiguracja axios'a (zapytania do bazy)

import axios from 'axios';
import { store, reduxActions } from 'store';
import i18n from 'i18n';
import { ICommandResponseError, ICommandResponseSuccess } from './types';
import remove from 'lodash/remove';
import find from 'lodash/find';

const { REACT_APP_API_BASE_URL, REACT_APP_API_BASE_PATH } = process.env;

if (!REACT_APP_API_BASE_URL || !REACT_APP_API_BASE_PATH) {
  throw new Error('Specify REACT_APP_API_BASE_URL and REACT_APP_API_BASE_PATH in your .env file');
}

const axiosInstance = axios.create();

axiosInstance.defaults.baseURL = `${REACT_APP_API_BASE_URL}${REACT_APP_API_BASE_PATH}`;
axiosInstance.defaults.headers.common['Accept'] = 'application/json';
axiosInstance.defaults.headers.post['Content-Type'] = 'application/json';

axiosInstance.interceptors.request.use((config) => {
  // aktualny język w headerze requestu
  const headers: { [key: string]: string } = {
    'Content-Language': i18n.language
  };

  // token autoryzacyjny w headerze requestu
  const authToken = store.getState().auth.token;
  if (authToken) {
    headers.Authorization = `Bearer ${authToken}`;
  }

  config.headers = {
    ...config.headers,
    ...headers
  };

  // id przelogowanego użytkownika
  const overloginUserId = store.getState().auth.overloginUserId;
  config.params = {
    ...config.params,
    switch_user_by_id: overloginUserId
  };

  return config;
});

// mapowanie błędu z api na standardowy typ błędu
const normalizeError = (error: Record<string, unknown>): ICommandResponseError => ({
  fields_info: error?.fields_info as ICommandResponseError['fields_info'],
  message:
    (error?.message as ICommandResponseError['message']) ||
    `Błąd serwera przetwarzania zapytania ${error?.detail && `(${error?.detail})`}`,
  show_message:
    error?.show_message === undefined
      ? true
      : (error?.show_message as ICommandResponseError['show_message']),
  show_modal: (error?.show_modal as boolean) || false,
  status: 0,
  error_fields: error?.error_fields as ICommandResponseError['error_fields']
});

// mapowanie odpowiedzi z api (komenda) na standardowy typ
const normalizeSuccess = (data?: Record<string, unknown>): ICommandResponseSuccess => ({
  message: (data?.message as string) || 'Success',
  show_message: (data?.show_message as boolean) || false,
  status: 1,
  data: data?.data as Record<string, unknown>
});

// pokazanie notyfikacji
const showMessage = (message: string, variant: 'success' | 'error') =>
  store.dispatch(reduxActions.setNotification({ message, variant }));

// pokazanie modala
const showModal = (title: string, content: string) =>
  store.dispatch(reduxActions.setNotificationModal({ title, content }));

// przechwycenie i zmodyfikowanie odpowiedzi z api
axiosInstance.interceptors.response.use(
  (response) => {
    // normalizacja typu komendy
    if (
      (response.config.method === 'post' ||
        response.config.method === 'put' ||
        response.config.method === 'delete') &&
      response.config.url !== '/auth/login' // TODO remove id response will be standard
    ) {
      if (response.data.status === 0) {
        const normalizedError = normalizeError(response.data);
        // TO DO COMMENT THIS
        normalizedError.show_message && showMessage(normalizedError.message, 'error');
        normalizedError.show_modal &&
          showModal(
            normalizedError.message,
            normalizedError.error_fields?.map((item) => item.message).join('<br />') || ''
          );
        return Promise.reject(normalizedError);
      }

      const normalizedSuccess = normalizeSuccess(response.data);
      normalizedSuccess.show_message && showMessage(normalizedSuccess.message, 'success');
      return normalizedSuccess;
    }

    return response.data;
  },
  // normalizacja błędu
  (error) => {
    if (error.response) {
      const { status } = error.response;

      // przeładowanie w trybie maintenance mode
      if (status === 503) {
        window.location.reload();

        return;
      }

      if (status === 401 && store.getState().auth.isAuthenticated) {
        store.dispatch(reduxActions.signOut());
      }
    }

    const normalizedError = normalizeError(error.response?.data || error);

    if (!normalizedError.show_message) {
      return null;
    }

    if (normalizedError.fields_info) {
      const requestKeys = error?.config?.env?.FormData?.requestKeys || [];
      const fieldsInfoToShow = [...normalizedError.fields_info];
      remove(fieldsInfoToShow, (field) => find(requestKeys, (key) => key === field.property_path));
      const text =
        fieldsInfoToShow
          ?.map((item) => `${item.property_name || item.property_path}: ${item.message}`)
          .join('\n') || '';
      if (text.length) {
        showMessage(
          `${normalizedError.message}\n\n${i18n.t(
            'Poniższe pola nie spełniają koniecznych warunków'
          )}:\n${text}`,
          'error'
        );
      } else {
        showMessage(normalizedError.message, 'error');
      }
    } else {
      showMessage(
        `${normalizedError.message} ${
          normalizedError.error_fields?.length
            ? normalizedError.error_fields.map((field) => field.message)
            : ''
        }`,
        'error'
      );
    }

    // TO DO COMMENT THIS

    normalizedError.show_modal &&
      showModal(
        normalizedError.message,
        normalizedError.error_fields?.map((item) => item.message).join('<br />') || ''
      );

    return Promise.reject(normalizedError);
  }
);

export default axiosInstance;
