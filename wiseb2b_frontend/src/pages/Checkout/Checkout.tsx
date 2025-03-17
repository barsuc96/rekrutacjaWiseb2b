// strona finalizacji koszyka

import React, { useEffect, useMemo, useState } from 'react';
import { useParams } from 'react-router-dom';
import classnames from 'classnames';
import { Trans, useTranslation } from 'react-i18next';
import { ChevronDown } from 'react-bootstrap-icons';
import TextareaAutosize from '@mui/material/TextareaAutosize';
import animateScrollTo from 'animated-scroll-to';

import {
  useGetCart,
  useGetCartMainData,
  useGetCartsAll,
  usePostCartPreConfirm,
  usePostCartConfirm,
  usePutCartMainData,
  useGetCartDeliveryMethods,
  usePostCartValidate,
  useGetContract,
  useGetCheckoutAgreement
} from 'api';
import { ICommandResponseError } from 'api/types';
import { useAppNavigate, useNotifications } from 'hooks';
import { CartSummary } from 'components/containers';
import { Container, CheckoutSteps, Link } from 'components/controls';
import {
  Payer,
  Payment,
  Delivery,
  Details,
  Confirmation,
  Positions,
  Contracts
} from './components';

import styles from 'theme/pages/Checkout/Checkout.module.scss';
import { ArrowIcon } from 'assets/icons';

const Checkout = () => {
  const { t } = useTranslation();
  const navigate = useAppNavigate();
  const { showErrorMessage } = useNotifications();

  // ID koszyka z url'a
  const { cartId: cartIdParam } = useParams();
  // rzutowanie ID na numer
  const cartId = useMemo(() => parseInt(cartIdParam || ''), [cartIdParam]);

  // czy jest widok podsumowania
  const [isSummary, setIsSummary] = useState(false);

  // czy jest widok podsumowania
  const [comment, setComment] = useState('');

  // czy jest widok podsumowania
  const [validationErrors, setValidationErrors] = useState<ICommandResponseError['error_fields']>();

  // czy jest widok potwierdzenia
  const [isConfirmation, setIsConfirmation] = useState(false);

  // lista rozwiniętych sekcji
  const [expandedSections, setExpandedSections] = useState([
    'receiver',
    'delivery_method',
    'payment_method',
    'comment',
    'contracts'
  ]);

  // wybrane zgody
  const [selectedContractIds, setSelectedContractIds] = useState<number[]>([]);

  // ustawienie komentarza z responsu (i wyczyszczenie błędów) z api (przy poejściu pomiędzy widokiem podsumowania i checkoutu)
  useEffect(() => {
    setComment(cartMainData?.comment || '');
    setValidationErrors(undefined);
  }, [isSummary]);

  useEffect(() => {
    animateScrollTo(0, {
      elementToScroll: document.getElementsByClassName('scrollableContent')[0]
    });
  }, [isSummary, isConfirmation]);

  // reset stanu po zmienie ID koszyka
  useEffect(() => {
    setExpandedSections(['receiver', 'delivery_method', 'payment_method', 'comment', 'contracts']);
    setIsSummary(false);
    setIsConfirmation(false);
  }, [cartId]);

  // pobranie wszystkich koszyków
  const { refetch: refetchAllCarts } = useGetCartsAll({
    onSuccess: (data) => {
      const currentCartItem = data.items.find((item) => item.id === cartId);

      if (!isConfirmation) {
        currentCartItem
          ? currentCartItem.products_count > 0
            ? refetchCartData()
            : (showErrorMessage(t('Koszyk jest pusty')), navigate(`/cart/${cartId}`))
          : (showErrorMessage(t('Koszyk nie istnieje')), navigate('/'));
      }
    }
  });

  // pobranie szczegółów koszyka
  const { refetch: refetchCartData } = useGetCart(cartId, {
    enabled: false,
    onError: () => {
      navigate('/');
    }
  });

  // pobranie listy metod dostawy
  const { data: cartDeliveryMethodsData, refetch: refetchCartDeliveryMethods } =
    useGetCartDeliveryMethods(cartId, {
      page: 1,
      limit: 999
    });

  // pobranie zgód
  const { data: checkoutAgreementData, refetch: refetchCheckoutAgreementData } =
    useGetCheckoutAgreement({
      page: 1,
      limit: 999,
      cartId: cartId
    });

  // walidacja poprawności koszykia
  const { mutate: preConfirmCart, isLoading: isCartPreConfirming } = usePostCartPreConfirm(cartId, {
    onSuccess: () => {
      setValidationErrors(undefined);
      setIsSummary(true);
      setExpandedSections(['receiver', 'details', 'comment']);
    },
    onError: (error) => {
      setValidationErrors(error.error_fields);
    }
  });

  // walidacja koszyka
  const { mutateAsync: validateCart } = usePostCartValidate(cartId || 0, {
    onSuccess: async () => {
      await putCartMainDataAsync({
        comment
      });

      if (isSummary) {
        confirmCart();

        return;
      }

      setValidationErrors(undefined);
      setIsSummary(true);
      setExpandedSections(['receiver', 'details', 'comment']);
    }
  });

  // pobranie main data koszyka
  const { data: cartMainData, refetch: refetchCartMainData } = useGetCartMainData(cartId, {
    onSuccess: (data) => {
      !cartMainData && setComment(data.comment || '');

      // odznaczenie błędu walidacji
      setValidationErrors((prevState) =>
        prevState?.filter(
          (item) => !data[item.property_path as 'receiver' | 'delivery_method' | 'payment_method']
        )
      );
    }
  });

  // aktualizacja main data koszyka
  const {
    mutate: putCartMainData,
    mutateAsync: putCartMainDataAsync,
    isLoading: isCartMainDataUpdating
  } = usePutCartMainData(cartId, {
    onSuccess: (data, variables) => {
      refetchAllCarts();
      refetchCartMainData();
      refetchCartDeliveryMethods();
      if (typeof variables.comment !== 'undefined') {
        setComment(variables.comment);
        setValidationErrors((prevState) =>
          prevState?.filter((item) => item.property_path !== 'comment')
        );
      }
    },
    onError: (error) => {
      setValidationErrors(error.error_fields);
    }
  });

  // potwiedznie koszyka (złożenie zamówienia)
  const { mutate: confirmCart, isLoading: isCartConfirming } = usePostCartConfirm(cartId, {
    onSuccess: () => {
      setIsConfirmation(true);
      refetchAllCarts();
    }
  });

  const toggleSections = (key: string) => {
    const isExpanded = expandedSections.includes(key);
    setExpandedSections(
      isExpanded
        ? expandedSections.filter((section) => section !== key)
        : [...expandedSections, key]
    );
  };

  // list sekcji widocznych na stronie
  const sections = isSummary
    ? [
        {
          key: 'payer',
          label: `1. ${t('Dane płatnika, odbiorcy')}`,
          content: (
            <Payer
              cartId={cartId}
              customer={cartMainData?.customer}
              receiver={cartMainData?.receiver}
              updateCartMainData={putCartMainData}
              returnToCheckout={(boxKey) => {
                setExpandedSections([boxKey]);
                setIsSummary(false);
              }}
              isSummary
            />
          )
        },
        {
          key: 'details',
          label: `2. ${t('Szczegóły zamówienia')}`,
          content: (
            <Details
              returnToCheckout={(boxKey) => {
                setExpandedSections([boxKey]);
                setIsSummary(false);
              }}
              mainData={cartMainData}
            />
          )
        },
        {
          key: 'positions',
          label: (
            <span className="positions">
              <Trans>Rozwiń zawartość zamówienia</Trans>
            </span>
          ),
          content: <Positions cartId={cartId} />,
          noStyles: true
        }
      ]
    : [
        {
          key: 'receiver',
          label: `1. ${t('Dane płatnika, odbiorcy')}`,
          content: (
            <Payer
              cartId={cartId}
              customer={cartMainData?.customer}
              receiver={cartMainData?.receiver}
              updateCartMainData={putCartMainData}
              isCartMainDataUpdating={isCartMainDataUpdating}
            />
          )
        },
        {
          key: 'payment_method',
          label: `2. ${t('Metoda płatności')}`,
          content: (
            <Payment
              cartId={cartId}
              updateCartMainData={putCartMainData}
              paymentMethod={cartMainData?.payment_method}
            />
          )
        },
        {
          key: 'delivery_method',
          label: `3. ${t('Sposób dostawy')}`,
          content: (
            <Delivery
              updateCartMainData={putCartMainData}
              cartDeliveryMethods={cartDeliveryMethodsData?.items || []}
              deliveryMethod={cartMainData?.delivery_method}
              deliveryPoint={cartMainData?.receiver_delivery_point}
            />
          )
        },
        {
          key: 'contracts',
          label: `4. ${t('Zgody')}`,
          content: (
            <Contracts
              cartId={cartId}
              contracts={checkoutAgreementData?.items || []}
              selectedContractIds={selectedContractIds}
              setSelectedContractIds={setSelectedContractIds}
              refetchCheckoutAgreementData={refetchCheckoutAgreementData}
            />
          )
        }
      ];

  const commentError = useMemo(
    () => validationErrors?.find((item) => item.property_path === 'comment')?.message,
    [validationErrors]
  );

  if (isConfirmation) {
    return <Confirmation cartId={cartId} />;
  }

  const handleSidebarButtonClick = () => {
    validateCart({ verification_scope: 'all' });
  };

  return (
    <div className={classnames(styles.componentWrapper, 'StylePath-Pages-Checkout')}>
      <Container>
        <div className={styles.contentHeader}>
          <Link to="/products" className={styles.link}>
            <ArrowIcon className={styles.backIcon} /> <Trans>Wróć do zakupów</Trans>
          </Link>

          <CheckoutSteps
            currentStepIndex={isSummary ? 2 : 1}
            cartId={cartId}
            onChange={(newIndex) => newIndex === 1 && setIsSummary(false)}
          />
        </div>

        <div className={styles.contentWrapper}>
          <div className={styles.content}>
            {sections.map(({ key, label, content, noStyles }) => {
              const isOpened = expandedSections.includes(key);
              const error = validationErrors?.find((item) => item.property_path === key);

              return (
                <div className={styles.collapse} key={key}>
                  <div
                    className={classnames({
                      [styles.header]: true,
                      [styles.open]: isOpened,
                      error
                    })}
                    onClick={() => toggleSections(key)}>
                    <span>
                      {label}{' '}
                      {error && <span className={styles.errorMessage}>({error.message})</span>}
                    </span>{' '}
                    <ChevronDown />
                  </div>
                  {isOpened && (
                    <div className={classnames(styles.content, { [styles.noStyles]: noStyles })}>
                      {content}
                    </div>
                  )}
                </div>
              );
            })}
          </div>
          <div className={styles.sidebar}>
            <CartSummary
              cartId={cartId}
              buttonOnClick={handleSidebarButtonClick}
              buttonLabel={isSummary ? t('Płacę i zamawiam') : t('Przejdź do podsumowania')}
              isLoading={isCartConfirming || isCartPreConfirming}
            />

            <div className={styles.collapse}>
              <div
                className={classnames({
                  [styles.header]: true,
                  [styles.open]: expandedSections.includes('comment'),
                  [styles.error]: !!commentError
                })}
                onClick={() => toggleSections('comment')}>
                <span>
                  <Trans>Komentarz do zamówienia</Trans>
                </span>{' '}
                <ChevronDown />
              </div>
              <div
                className={classnames({
                  [styles.content]: true,
                  [styles.hidden]: !expandedSections.includes('comment')
                })}>
                <TextareaAutosize
                  className={styles.textarea}
                  value={comment}
                  placeholder={`${t('napisz komentarz')}...`}
                  minRows={3}
                  onChange={(event) => setComment(event.target.value)}
                  onBlur={() =>
                    comment !== cartMainData?.comment
                      ? putCartMainData({
                          comment
                        })
                      : setValidationErrors((prevState) =>
                          prevState?.filter((item) => item.property_path !== 'comment')
                        )
                  }
                />
                {commentError && <span className={styles.errorMessage}>{commentError}</span>}
              </div>
            </div>
          </div>
        </div>
      </Container>
    </div>
  );
};

export default Checkout;
