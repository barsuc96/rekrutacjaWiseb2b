import React, { FC } from 'react';
import { Trans } from 'react-i18next';
import { FiletypePdf } from 'react-bootstrap-icons';

import { useGetCartExport } from 'api';

import styles from 'theme/pages/Carts/Carts.module.scss';

type IProps = {
  id: number;
};

const CartExportButton: FC<IProps> = ({ id }) => {
  // eksport koszyka do PDF
  const { refetch: downloadPdf } = useGetCartExport(
    id,
    {
      exportType: 'pdf'
    },
    {
      enabled: false,
      onSuccess: (data) => {
        const a = document.createElement('a');
        a.download = data.file_name;
        a.href = `data:application/pdf;base64,${data.content}`;
        a.click();
      }
    }
  );

  return (
    <button className={styles.exportButton} onClick={() => downloadPdf()}>
      <FiletypePdf /> <Trans>export</Trans>
    </button>
  );
};

export default CartExportButton;
