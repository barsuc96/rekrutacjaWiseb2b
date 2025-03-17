import React, { FC, PropsWithChildren } from 'react';
import { Grid } from '@mui/material';
import { Trans } from 'react-i18next';

import { useGetOrder, useGetPanelOrder } from 'api';
import { Status } from 'components/controls';
import classnames from 'classnames';
import { format } from 'date-fns';

import { useAppNavigate, useRWD, useNotifications } from 'hooks';

import styles from 'theme/pages/Order/components/OrderSummary/OrderSummary.module.scss';

interface IProps {
  orderId: number;
  isPanel: boolean;
}

const OrderSummary: FC<IProps> = ({ orderId, isPanel }) => {
  const navigate = useAppNavigate();
  const { showErrorMessage } = useNotifications();

  const { data: orderData } = (isPanel ? useGetPanelOrder : useGetOrder)(orderId, {
    onSuccess(data) {
      if (!data.id && data.message) {
        navigate('/dashboard/orders');
        showErrorMessage(data.message);
      }
    }
  });

  const { isMobile } = useRWD();

  const Box: FC<PropsWithChildren> = ({ children }) => <div className={styles.box}>{children}</div>;

  return (
    <div
      className={classnames(
        styles.wrapperComponent,
        'StylePath-Pages-Order-components-OrderSummary'
      )}>
      {isMobile && <div className={classnames(styles.title, styles.mainTitle)}>ID{orderId}D</div>}
      <div className={styles.blocks}>
        <Grid container className={styles['summary-blocks']}>
          <Grid item md={8} className={styles['summary-entities']}>
            {!isMobile && (
              <div className={styles.title}>
                <Trans>Zamówienie</Trans>: {orderData?.name}
              </div>
            )}

            <div className={styles.company}>
              {orderData?.customer && (
                <div className={styles.customer}>
                  <div className={styles.title}>
                    <Trans>Dane płatnika</Trans>:
                  </div>
                  {orderData.customer.name}
                  <br />
                  {orderData.customer.address.street}
                  <br />
                  {orderData.customer.address.postal_code} {orderData.customer.address.city}
                  <br />
                  <br />
                  <span className={styles.label}>
                    <Trans>NIP</Trans>:
                  </span>{' '}
                  {orderData.customer.nip}
                  <br />
                  <span className={styles.label}>
                    <Trans>E-mail</Trans>:
                  </span>{' '}
                  {orderData.customer.email}
                  <br />
                  <span className={styles.label}>
                    <Trans>Telefon</Trans>:
                  </span>{' '}
                  {orderData.customer.phone}
                </div>
              )}

              {orderData?.receiver && (
                <div className={styles.receiver}>
                  <div className={styles.title}>
                    <Trans>Dane odbiorcy</Trans>:
                  </div>
                  {orderData.receiver.name}
                  <br />
                  {orderData.receiver.email}
                  <br />
                  <br />
                  <span className={styles.label}>
                    <Trans>Telefon</Trans>:
                  </span>{' '}
                  {orderData.receiver.phone}
                </div>
              )}
            </div>
          </Grid>

          <Grid item md={4} className={styles['summary-status']}>
            <Box>
              <div className={styles.name}>
                <Trans>Data utworzenia</Trans>
              </div>
              <div className={classnames(styles.value, { [styles.largeValue]: isMobile })}>
                {orderData?.create_date
                  ? format(new Date(orderData.create_date), 'dd-MM-yyyy')
                  : '---'}
              </div>
            </Box>

            <Box>
              <div className={styles.name}>
                <Trans>Status</Trans>
              </div>
              <div className={styles.statusWrapper}>
                <Status
                  icon={orderData?.status.icon || ''}
                  message={orderData?.status.label || ''}
                  color={orderData?.status.color}
                />
              </div>
            </Box>

            <Box>
              <div className={styles.name}>
                <Trans>Wartość zamówienia</Trans>
              </div>
              {orderData?.value_net_formatted ? (
                <>
                  <div className={styles.value}>
                    <span>
                      <strong className={styles.large}>
                        {orderData?.value_net_formatted} {orderData?.currency}
                      </strong>
                    </span>
                    <Trans>netto</Trans>
                  </div>
                  <div className={styles.value}>
                    <span>
                      {orderData?.value_gross_formatted} {orderData?.currency}
                    </span>{' '}
                    <Trans>brutto</Trans>
                  </div>
                </>
              ) : (
                '---'
              )}
            </Box>
          </Grid>
        </Grid>
      </div>

      <div className={styles.list}>
        <div className={classnames(styles.name, styles.historyName, { [styles.small]: isMobile })}>
          <Trans>Historia zmian</Trans>:
        </div>
        <div className={styles.history}>
          {orderData?.status_history.map((item, index) => (
            <div className={styles.event} key={`${item.date}-${item.hour}-${index}`}>
              <div className={styles.date}>
                {format(new Date(item.date + ' ' + item.hour), 'dd.MM.yyyy')}{' '}
                <span>{format(new Date(item.date + ' ' + item.hour), '(H:mm)')}</span>
              </div>{' '}
              - {item.status_message}
            </div>
          ))}
        </div>
      </div>
    </div>
  );
};

export default OrderSummary;
