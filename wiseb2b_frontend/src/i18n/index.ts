// obsługa tłumaczeń wersji językowych

import i18n from 'i18next';
import { initReactI18next } from 'react-i18next';
import { resources } from './resources';

// język wynikający z url'a
const urlLang = window.location.pathname.split('/').filter((item) => item)[0];

const supportedLngs = ['pl', 'en'];
const fallbackLng = 'pl';

// inicjalny język
const lng = supportedLngs.find((supportedLng) => supportedLng === urlLang) || fallbackLng;

i18n.use(initReactI18next).init({
  resources,
  lng,
  fallbackLng,
  supportedLngs,
  interpolation: {
    escapeValue: false
  },
  parseMissingKeyHandler: (key: string) => {
    if (process.env.NODE_ENV === 'development') {
      // eslint-disable-next-line no-console
      console.warn(`[${key}] missing translation`);
      return `[${key}]`;
    }

    return key;
  }
});

export default i18n;
