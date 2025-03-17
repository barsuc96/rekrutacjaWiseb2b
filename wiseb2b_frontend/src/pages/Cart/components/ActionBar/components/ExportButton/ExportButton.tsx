import React, { FC, PropsWithChildren } from 'react';

import { useGetCartExport } from 'api';

import styles from 'theme/pages/Cart/components/ActionBar/ActionBar.module.scss';

interface IProps extends PropsWithChildren {
  fileType: 'pdf' | 'xls' | 'csv';
  id: number;
}

const ExportButton: FC<IProps> = ({ fileType, id, children }) => {
  // eksport koszyka do PDF
  const { refetch: downloadPdf } = useGetCartExport(
    id,
    {
      exportType: fileType
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
    <div className={styles.cartActionsItemLabel} onClick={() => downloadPdf()}>
      {children}
    </div>
  );
};

export default ExportButton;
