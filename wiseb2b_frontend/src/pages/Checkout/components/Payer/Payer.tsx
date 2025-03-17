// sekcja płatnikiem i odbiorcą

import React, { FC, useEffect, useMemo, useState } from 'react';
import classnames from 'classnames';
import { Trans, useTranslation } from 'react-i18next';
import { PlusCircle } from 'react-bootstrap-icons';

import { useRWD } from 'hooks';
import { useGetCartReceivers } from 'api';
import {
  ICartMainData,
  ICartMainDataUpdateRequest,
  ICartReceiverListItem,
  ICommandResponseErrorField
} from 'api/types';
import { ReceiverForm } from 'components/containers';
import { Select, Modal, Radio } from 'components/controls';
import CustomerForm from '../CustomerForm';

import styles from 'theme/pages/Checkout/components/Payer/Payer.module.scss';

// typ danych wejściowych
interface IProps {
  cartId: number;
  customer: ICartMainData['customer'];
  receiver: ICartMainData['receiver'];
  updateCartMainData: (data: Partial<ICartMainDataUpdateRequest>) => void;
  isCartMainDataUpdating?: boolean;
  isSummary?: boolean;
  returnToCheckout?: (boxKey: string) => void;
  error?: ICommandResponseErrorField;
}

const Payer: FC<IProps> = ({
  cartId,
  customer,
  receiver,
  updateCartMainData,
  isCartMainDataUpdating,
  isSummary,
  returnToCheckout
}) => {
  const { isMobile } = useRWD();
  const { t } = useTranslation();

  // czy jest widoczny modal z edycją płatnika
  const [isCustomerEditing, setIsCustomerEditing] = useState(false);

  // czy jest widoczny modal z dodawaniem odbiorcy
  const [isReceiverAdding, setIsReceiverAdding] = useState(false);

  // pobranie listy odbiorców
  const { data: cartReceiversData, refetch: refetchCartReceiversData } = useGetCartReceivers(
    cartId,
    {
      page: 1,
      limit: 999
    }
  );

  // opcje w selektorze odbiorców
  const receiverOptions = useMemo(
    () =>
      cartReceiversData?.items.map((item) => ({
        value: item.id,
        label: (
          <div className={styles.receiverOption}>
            <Radio checked={item.id === receiver?.id} />
            <div className={classnames({ [styles.current]: item.id === receiver?.id })}>
              {item.name}
              <br />
              {item.address.street}
              <br />
              {item.address.postal_code} {item.address.city}
            </div>
          </div>
        ),
        item
      })) || [],
    [cartReceiversData, receiver?.id]
  );

  // aktualny/wybrany odbiorca
  const selectedReceiversOption = useMemo(() => {
    const foundReceiver = cartReceiversData?.items.find((item) => item.id === receiver?.id);

    return foundReceiver
      ? {
          value: foundReceiver.id,
          label: foundReceiver.name,
          item: foundReceiver
        }
      : undefined;
  }, [cartReceiversData, receiver?.id]);

  // chowanie modali po skończeniu zapisu danych
  useEffect(() => {
    if (!isCartMainDataUpdating) {
      setIsCustomerEditing(false);
      setIsReceiverAdding(false);
    }
  }, [isCartMainDataUpdating]);

  return (
    <>
      <div
        className={classnames(
          styles.componentWrapper,
          'StylePath-Pages-Checkout-Components-Payer'
        )}>
        <div className={styles.column}>
          <div className={styles.section}>
            <div className={styles.title}>
              <Trans>Dane płatnika</Trans>
              {!!customer && (
                <button
                  onClick={() => {
                    returnToCheckout?.('receiver');
                    setIsCustomerEditing(true);
                  }}>
                  <Trans>Zmień</Trans>
                </button>
              )}
            </div>
            {customer ? (
              <>
                {customer.name && (
                  <>
                    {customer.name}
                    <br />
                  </>
                )}
                {customer.contact_name && (
                  <>
                    {customer.contact_name}
                    <br />
                  </>
                )}
                {customer.address.street && (
                  <>
                    {customer.address.street} {customer.address.building}
                    <br />
                  </>
                )}
                {customer.address && (
                  <>
                    {customer.address.country} {customer.address.postal_code}{' '}
                    {customer.address.city}
                    <br />
                  </>
                )}
                <br />
                {customer.nip && (
                  <>
                    <span className={styles.label}>
                      <Trans>NIP</Trans>:
                    </span>{' '}
                    {customer.nip}
                    <br />
                  </>
                )}

                {customer.phone && (
                  <>
                    <span className={styles.label}>
                      <Trans>Telefon</Trans>:
                    </span>{' '}
                    {customer.phone}
                    <br />
                  </>
                )}

                {customer.email && (
                  <>
                    <span className={styles.label}>
                      <Trans>E-mail</Trans>:
                    </span>{' '}
                    {customer.email}
                  </>
                )}
              </>
            ) : (
              '-'
            )}
          </div>
        </div>
        <div className={styles.column}>
          <div className={styles.section}>
            <div className={styles.title}>
              <Trans>Dane odbiorcy</Trans>
              {isSummary && (
                <button onClick={() => returnToCheckout?.('receiver')}>
                  <Trans>Zmień</Trans>
                </button>
              )}
            </div>
            {receiver ? (
              <>
                {receiver.name && (
                  <>
                    {receiver.name}
                    <br />
                  </>
                )}
                {receiver.contact_name && (
                  <>
                    {receiver.contact_name}
                    <br />
                  </>
                )}
                {receiver.address && (
                  <>
                    {receiver.address.street} {receiver.address.building}{' '}
                    {receiver.address.apartment}
                    <br />
                  </>
                )}
                {receiver.address && (
                  <>
                    {receiver.address.country} {receiver.address.postal_code}{' '}
                    {receiver.address.city}
                    <br />
                  </>
                )}
                <br />

                {receiver.phone && (
                  <>
                    <span className={styles.label}>
                      <Trans>Telefon</Trans>:
                    </span>{' '}
                    {receiver.phone}
                    <br />
                  </>
                )}

                {receiver.email && (
                  <>
                    <span className={styles.label}>
                      <Trans>Email</Trans>:
                    </span>{' '}
                    {receiver.email}
                  </>
                )}
              </>
            ) : (
              '-'
            )}
          </div>

          {!isSummary && (
            <div className={styles.section}>
              <button onClick={() => setIsReceiverAdding(true)}>
                <PlusCircle /> <Trans>Dodaj odbiorcę</Trans>
              </button>
            </div>
          )}

          <div className={styles.section}>
            <div className={styles.title}>
              <Trans>Wybrany odbiorca</Trans>
            </div>
            <Select<ICartReceiverListItem>
              disabled={isSummary}
              options={receiverOptions}
              selectedOption={selectedReceiversOption}
              onChange={(item) =>
                item &&
                updateCartMainData({
                  receiver_id: item.id
                })
              }
            />
          </div>
        </div>
      </div>

      {isCustomerEditing && (
        <Modal
          onClose={() => setIsCustomerEditing(false)}
          title={t('Edycja danych płatnika')}
          fullScreen={isMobile}>
          {customer && (
            <CustomerForm
              onCancel={() => setIsCustomerEditing(false)}
              customer={customer}
              updateCartMainData={updateCartMainData}
              isCartMainDataUpdating={isCartMainDataUpdating}
            />
          )}
        </Modal>
      )}

      {isReceiverAdding && (
        <Modal
          onClose={() => setIsReceiverAdding(false)}
          title={t('Nowy odbiorca')}
          fullScreen={isMobile}>
          <ReceiverForm
            onSuccess={(receiverId: number) => {
              updateCartMainData({
                receiver_id: receiverId
              });
              refetchCartReceiversData();
            }}
            onCancel={() => setIsReceiverAdding(false)}
          />
        </Modal>
      )}
    </>
  );
};

export default Payer;
