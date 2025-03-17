// sekcja z wyborem metody dostawy

import React, { FC } from 'react';
import { Trans } from 'react-i18next';
import classnames from 'classnames';
import { InfoCircle, Truck } from 'react-bootstrap-icons';

import {
  ICartMainData,
  ICartMainDataUpdateRequest,
  ICartDeliveryMethodListItem,
  IReceiverPoint
} from 'api/types';
import { Radio } from 'components/controls';

import { GeoWidget } from './components';

import styles from 'theme/pages/Checkout/components/Delivery/Delivery.module.scss';

// typ danych wejściowych
interface IProps {
  deliveryMethod: ICartMainData['delivery_method'];
  deliveryPoint: ICartMainData['receiver_delivery_point'];
  cartDeliveryMethods: ICartDeliveryMethodListItem[];
  updateCartMainData: (data: Partial<ICartMainDataUpdateRequest>) => void;
}

const Delivery: FC<IProps> = ({
  deliveryMethod,
  cartDeliveryMethods,
  deliveryPoint,
  updateCartMainData
}) => {
  const handleParcelLocker = (data: IReceiverPoint) => {
    updateCartMainData({ receiver_delivery_point: data });
  };

  return (
    <>
      <div
        className={classnames(
          styles.componentWrapper,
          'StylePath-Pages-Checkout-Components-Delivery'
        )}>
        {cartDeliveryMethods.map((item) => (
          <label
            key={item.id}
            className={classnames(styles.item, {
              [styles.active]: item.id === deliveryMethod?.id
            })}>
            <Radio
              checked={item.id === deliveryMethod?.id}
              onClick={() => updateCartMainData({ delivery_method_id: item.id })}
            />

            <div className={styles.description}>
              <div className={styles.name}>
                <img className={styles.image} src={item.image} alt="" /> {item.name}
              </div>
              {item.free_delivery_from_message && (
                <div className={classnames(styles.info, styles[`styleType-${item.style_type}`])}>
                  <InfoCircle /> {item.free_delivery_from_message}
                </div>
              )}

              <div className={styles.time}>
                <Truck /> <span dangerouslySetInnerHTML={{ __html: item?.delivery_info || '' }} />
              </div>

              {item.parcel_point_widget === 'inpost' && deliveryPoint?.type === 'inpost_locker' && (
                <div className={classnames(styles.info)}>
                  <Trans>Wybrany punkt</Trans>: {deliveryPoint?.symbol}
                </div>
              )}

              {item.parcel_point_widget === 'pocztex' && deliveryPoint?.type === 'pocztex_pickup' && (
                <div className={classnames(styles.info)}>
                  <Trans>Wybrany punkt</Trans>: {deliveryPoint?.symbol}
                </div>
              )}

              {item.parcel_point_widget === 'dpd_pickup' && deliveryPoint?.type === 'dpd_pickup' && (
                <div className={classnames(styles.info)}>
                  <Trans>Wybrany punkt</Trans>: {deliveryPoint?.symbol}
                </div>
              )}
            </div>

            <div className={styles.price}>
              <div className={styles.priceGross}>
                {item.price_gross_formatted} {item.currency}{' '}
                <span>
                  <Trans>brutto</Trans>
                </span>
              </div>

              <div className={styles.priceNet}>
                {item.price_net_formatted} {item.currency}{' '}
                <span>
                  <Trans>netto</Trans>
                </span>
              </div>
            </div>
          </label>
        ))}
      </div>
      <GeoWidget
        handleParcelLocker={handleParcelLocker}
        code={cartDeliveryMethods.find((method) => method.id === deliveryMethod?.id)?.provider}
      />
    </>
  );
};

export default Delivery;
