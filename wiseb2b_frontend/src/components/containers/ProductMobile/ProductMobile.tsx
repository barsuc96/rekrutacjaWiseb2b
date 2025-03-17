import React from 'react';
import classnames from 'classnames';
import { ThreeDotsVertical } from 'react-bootstrap-icons';
import { Trans } from 'react-i18next';

import { IOrderPositionListItem } from 'api/types';
import { DropDown } from 'components/controls';

import styles from 'theme/components/containers/ProductMobile/ProductMobile.module.scss';

type Props = {
  item: IOrderPositionListItem;
};

const ProductMobile = ({ item }: Props) => {

  const unit = item.units.find((unit) => unit.id === item.unit_id);
  return (
    <div className={styles.mobileWrapper}>
      <div className={styles.mobileRow}>
        <div className={styles.title}>{item.name}</div>
        <DropDown
          label={<ThreeDotsVertical />}
          items={[
            {
              label: 'test'
            }
          ]}
          withDropdownIcon={false}
        />
      </div>
      <div className={styles.mobileRow}>
        <img src={item.image[0]?.thumb} alt="" />

        <div className={styles.productWrapper}>
          <div>
            <span className={styles.label}>
              <Trans>Ilość</Trans>:
            </span>
            <span className="medium">{item.quantity}</span>
          </div>
          <div className={styles.unitWrapper}>
            <span className={styles.label}>
              <Trans>Jednostka</Trans>:
            </span>
            <div className={styles.unitData}>
              <div className="medium">{unit?.name}</div>
              {unit?.converter_message && (
                <span className={styles.unitConverterMessage}>{unit.converter_message}</span>
              )}
            </div>
          </div>
        </div>
      </div>
      <div className={styles.mobileRow}>
        <div className={styles.priceWrapper}>
          <div className={styles.unitPrice}>
            <div className={classnames(styles.label, 'medium', styles.minor, styles.textPadding)}>
              <Trans>Cena sztuka</Trans>:
            </div>
            <div className={classnames('medium', styles.textPadding)}>
              {item.price_net_formatted + ' '}
              {item.currency + ' '}
              <span>
                <Trans>netto</Trans>
              </span>
            </div>
            <div className={classnames(styles.label, styles.minor)}>
              {item.price_gross_formatted + ' '}
              {item.currency + ' '}
              <span>
                <Trans>brutto</Trans>
              </span>
            </div>
          </div>
          <div className={styles.borderPrice}></div>
          <div className={styles.totalPrice}>
            <div className={classnames(styles.label, 'medium')}>
              <Trans>Wartość</Trans> {item.currency}:
            </div>

            <div>
              <div>
                <span className="bold">{item.total_price_net_formatted}</span>{' '}
                <span className={styles.small}>
                  <Trans>netto</Trans>
                </span>
              </div>
              <div className={styles.label}>
                {item.total_price_gross_formatted} <Trans>brutto</Trans>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default ProductMobile;
