// nagłówek zamówienia

import React, { FC } from 'react';
import { Trans } from 'react-i18next';
import classnames from 'classnames';

import { useGetOrderExportImages } from 'api';
import { useRWD, useNotifications } from 'hooks';
import { Button, PageTitle } from 'components/controls';

import { Download } from 'react-bootstrap-icons';
import styles from 'theme/pages/Order/components/OrderHeader/OrderHeader.module.scss';

// typ danych wejściowych
interface IProps {
  orderId: number;
}

const Header: FC<IProps> = ({ orderId }) => {
  const { isMobile } = useRWD();
  const { showErrorMessage } = useNotifications();

  const { refetch: getOrderImages } = useGetOrderExportImages(orderId, {
    enabled: false,
    onSuccess: (data) => {
      fetch(data.url)
        .then((resp) => resp.blob())
        .then((blob) => {
          const url = window.URL.createObjectURL(blob);
          const a = document.createElement('a');
          a.style.display = 'none';
          a.href = url;

          a.download = data.file_name;
          document.body.appendChild(a);
          a.click();
          window.URL.revokeObjectURL(url);
        })
        .catch(() => showErrorMessage('Błąd pobierania pliku'));
    }
  });

  return (
    <div
      className={classnames(
        styles.wrapperComponent,
        'StylePath-Pages-Order-components-OrderHeader'
      )}>
      {isMobile ? (
        <PageTitle
          title={
            <>
              <Trans>Szczegóły zamówienia</Trans>{' '}
            </>
          }
        />
      ) : (
        <>
          <h3 className={styles.title}>
            <Trans>Szczegóły zamówienia</Trans>
          </h3>
          <Button onClick={() => getOrderImages()}>
            <Download /> <Trans>Pobierz zdjęcia produktów z zamówienia</Trans>
          </Button>
        </>
      )}
    </div>
  );
};

export default Header;
