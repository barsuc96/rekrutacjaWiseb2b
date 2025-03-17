// kroki checkoutu

import React, { FC, ReactElement } from 'react';
import { useTranslation } from 'react-i18next';
import { Cart, CreditCard, Truck } from 'react-bootstrap-icons';
import classnames from 'classnames';

import { Link } from 'components/controls';

import styles from 'theme/components/controls/CheckoutSteps/CheckoutSteps.module.scss';
import { ArrowLongIcon } from 'assets/icons';

interface IStep {
  label: string;
  icon: ReactElement;
  url?: string;
}

// typ danych wejściowych
interface IProps {
  currentStepIndex: number;
  cartId: number;
  onChange?: (stepIndex: number) => void;
  disabled?: boolean;
}

const CheckoutSteps: FC<IProps> = ({ currentStepIndex, cartId, onChange, disabled }) => {
  const { t } = useTranslation();

  // konfiguracja kroków
  const steps: IStep[] = [
    { label: t('Koszyk'), icon: <Cart className={styles.icon} />, url: `/cart/${cartId}` },
    {
      label: t('Wysyłka i płatność'),
      icon: <Truck className={styles.icon} />,
      url: `/checkout/${cartId}`
    },
    { label: t('Podsumowanie'), icon: <CreditCard className={styles.icon} /> }
  ];

  // Komponent renderujący krok
  const ItemContent = ({ label, icon }: IStep) => (
    <>
      {icon}
      <span className={styles.label}>{label}</span>
    </>
  );

  return (
    <div
      className={classnames(
        styles.wrapperComponent,
        'StylePath-Components-Controls-CheckoutSteps'
      )}>
      {steps.map((item, index) => (
        <div
          key={index}
          className={classnames(styles.item, { [styles.current]: index == currentStepIndex })}>
          {item.url && !disabled ? (
            <Link to={item.url} onClick={() => index !== currentStepIndex && onChange?.(index)}>
              <ItemContent label={item.label} icon={item.icon} />
            </Link>
          ) : (
            <span>
              <ItemContent label={item.label} icon={item.icon} />
            </span>
          )}
          {index + 1 !== steps.length && <ArrowLongIcon className={styles.arrow} />}
        </div>
      ))}
    </div>
  );
};

export default CheckoutSteps;
