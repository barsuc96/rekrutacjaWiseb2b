// widok potwierdzenia zamówienia

import React, { FC, useState, useEffect, useRef, useMemo } from 'react';
import { Trans } from 'react-i18next';
import classnames from 'classnames';
import { ArrowRight } from 'react-bootstrap-icons';
import { useParams } from 'react-router-dom';

import { useAppNavigate } from 'hooks';
import {
  useGetCartThankYouInfo,
  useGetCmsSectionArticles,
  usePostOnlinePaymentInitialize
} from 'api';
import { Container, Loader } from 'components/controls';

import styles from 'theme/pages/Checkout/components/Confirmation/Confirmation.module.scss';
import orderImage from 'assets/images/order.png';

// typ danych wejściowych
interface IProps {
  cartId?: number;
}

const Confirmation: FC<IProps> = ({ cartId }) => {
  const navigate = useAppNavigate();

  const { cartId: id } = useParams();
  // przekształcenie w numer
  const urlCartId = useMemo(() => parseInt(id || ''), [id]);

  // pobranie danych informacyjnych do wyświetlenia
  const { data } = useGetCartThankYouInfo(urlCartId || cartId || 0);

  //inicjalizacja metody płatności
  const { mutate: initializePayment, isLoading } = usePostOnlinePaymentInitialize({
    onSuccess: ({ data }) => {
      if (data.redirect_type === 'REDIRECT_URL') {
        window.location.replace(data.redirect_payload);
      }
    }
  });

  const timeoutRef = useRef<null | ReturnType<typeof setTimeout>>(null);

  const [counter, setCounter] = useState<number | null>(null);

  //ustawienie wartości początkowej dla countera
  useEffect(() => {
    if (data?.payment_action_auto_time) {
      setCounter(data.payment_action_auto_time);
    }
  }, [data?.payment_action_auto_time]);

  // odliczanie countera
  useEffect(() => {
    if (data?.payment_action_enable && counter && counter > 0) {
      timeoutRef.current = setTimeout(() => {
        counter && setCounter(counter - 1);
      }, 1000);
    }
  }, [counter, data?.payment_action_enable]);

  const stopCounter = () => {
    timeoutRef.current && clearTimeout(timeoutRef.current);
    setCounter(null);
  };

  // przerywanie automatycznej akcji
  useEffect(() => {
    const keyboardEvent = (e: KeyboardEvent) => {
      if (e.key === 'Escape') {
        stopCounter();
      }
    };

    const mouseEvent = () => {
      stopCounter();
    };

    document.addEventListener('keydown', keyboardEvent);
    document.addEventListener('mousemove', mouseEvent);

    return () => {
      document.removeEventListener('keydown', keyboardEvent);
      document.removeEventListener('mousemove', mouseEvent);
    };
  }, []);

  // wykonanie automatycznej akcji
  useEffect(() => {
    if (counter === 0) {
      initializePayment({
        context: 'ORDER_CREATED',
        entity_type: 'ORDER',
        entity_id: data?.order_id || 0
      });
    }
  }, [counter]);

  // pobranie bloku html
  const { data: cmsData } = useGetCmsSectionArticles('CHECKOUT', {
    fetchArticleFields: true
  });

  return (
    <div
      className={classnames(
        styles.componentWrapper,
        'StylePath-Pages-Checkout-Components-Confirmation'
      )}>
      <Container>
        <div className={styles.content}>
          <div>
            <h1>{data?.title}</h1>
            {data?.description_html && (
              <div dangerouslySetInnerHTML={{ __html: data.description_html }}></div>
            )}
            {data?.description_html && (
              <div dangerouslySetInnerHTML={{ __html: data.payment_info_html }}></div>
            )}
            <div className={styles.orderNo}>{data?.order_number}</div>
            {data?.payment_action_enable ? (
              <button
                className={styles.button}
                onClick={() => {
                  stopCounter();

                  initializePayment({
                    context: 'ORDER_CREATED',
                    entity_type: 'ORDER',
                    entity_id: data?.order_id || 0
                  });
                }}>
                {data.button_text} {counter !== null && `(${counter})`}
              </button>
            ) : (
              <button className={styles.button} onClick={() => navigate('/')}>
                <Trans>Zakończ i wróć na stronę główną</Trans>
                <ArrowRight />
              </button>
            )}
          </div>
          <img className={styles.image} src={orderImage} alt="order" />
        </div>
        {isLoading && (
          <div className={styles.loaderWrapper}>
            <Loader />
          </div>
        )}

        <div className={styles.tilesWrapper}>
          <span className={styles.title}>
            <Trans>Baza wiedzy</Trans>
          </span>

          <div className={styles.tiles}>
            {cmsData?.items.map((tile) => (
              <div className={styles.tile} key={tile.id}>
                <img src={tile.article_fields[0].value} />
                <div className={styles.label}>
                  {tile.title} <ArrowRight />
                </div>
              </div>
            ))}
          </div>
          <div className={styles.readMore}>
            <a href="#">
              <Trans>Czytaj więcej</Trans> <ArrowRight />
            </a>
          </div>
        </div>
      </Container>
    </div>
  );
};

export default Confirmation;
