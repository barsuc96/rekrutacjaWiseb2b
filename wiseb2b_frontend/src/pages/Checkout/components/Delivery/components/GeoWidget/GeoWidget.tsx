import React, { FC, useEffect, useCallback } from 'react';
import classnames from 'classnames';

import { IReceiverPoint } from 'api/types';

import styles from 'theme/pages/Checkout/components/GeoWidget/GeoWidget.module.scss';

interface IEasyPack {
  init: (arg: object) => void;
  mapWidget: (arg1: string, arg2: (point: string) => void) => void;
}

interface IPPWidgetApp {
  toggleMap: (arg: object) => void;
}

declare global {
  interface Window {
    easyPackAsyncInit: () => void;
    easyPack: IEasyPack;
    PPWidgetApp: IPPWidgetApp;
  }
}

// typ danych wejściowych
interface IProps {
  handleParcelLocker: (data: IReceiverPoint) => void;
  code?: string;
}

const InpostWidget: FC<IProps> = ({ handleParcelLocker, code }) => {
  // inicjalizacja widgeta Poczty Polskiej
  useEffect(() => {
    const callbackFunc = (wybranyPunkt: string) => wybranyPunkt;
    const pobranie = true;
    const address = 'Warszawa Mazowieckie Polska';
    const type = ['POCZTA', 'ORLEN', 'AUTOMAT_POCZTOWY', 'ZABKA', 'AUTOMAT_BIEDRONKA', 'RUCH'];
    window.PPWidgetApp.toggleMap({
      callback: callbackFunc,
      payOnPickup: pobranie,
      address: address,
      type: type,
      embeddedElementId: 'pocztex'
    });
  }, []);

  // funkcja skrolująca stronę do góry
  const scrollToTop = useCallback(
    () => document.documentElement.scrollTo({ top: 0, left: 0, behavior: 'smooth' }),
    []
  );

  // pobieranie danych po wyborze paczkomatu
  useEffect(() => {
    window.addEventListener('message', (data) => handleIframe(data));

    return () => window.removeEventListener('message', handleIframe);
  }, []);

  const handleIframe = (data: MessageEvent) => {
    if (data.data.source === 'dpd-widget') {
      const { city, code, postalCode, street } = data.data.payload;
      handleParcelLocker({
        type: 'dpd_pickup',
        symbol: code,
        address: {
          street: street,
          building: '',
          apartment: '',
          postal_code: postalCode,
          city: city,
          state: ''
        }
      });

      scrollToTop();
    }

    if (data.data.source === 'inpost-widget') {
      const {
        name,
        address_details: { building_number, street, post_code, city, province }
      } = data.data.payload;
      handleParcelLocker({
        type: 'inpost_locker',
        symbol: name,
        address: {
          street: street,
          building: building_number,
          apartment: '',
          postal_code: post_code,
          city: city,
          state: province
        }
      });

      scrollToTop();
    }

    if (data.data.type === 'sendPPData') {
      const { pni, city, street, zipCode, province } = data.data.value;
      handleParcelLocker({
        type: 'pocztex_pickup',
        symbol: pni,
        address: {
          street: street,
          building: '',
          apartment: '',
          postal_code: zipCode,
          city: city,
          state: province
        }
      });

      scrollToTop();
    }
  };

  return (
    <div
      className={classnames(
        styles.componentWrapper,
        'StylePath-Pages-Checkout-Components-GeoWidget'
      )}>
      <div
        className={classnames(
          styles.widget,
          styles.inpostWidget,
          code === 'inpost_locker' && styles.visible
        )}>
        <iframe src={process.env.PUBLIC_URL + '/inpost.html'} />
      </div>
      <div
        className={classnames(
          styles.widget,
          styles.dpdWidget,
          code === 'dpd_pickup' && styles.visible
        )}>
        <iframe src={process.env.PUBLIC_URL + '/dpd.html'} />
      </div>
      <div className={classnames(styles.widget, code === 'pocztex_pickup' && styles.visible)}>
        <div id="pocztex" />
      </div>
    </div>
  );
};

export default InpostWidget;
