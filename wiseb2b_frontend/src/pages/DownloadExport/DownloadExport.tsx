// strona pobieranie pliku exportu

import React from 'react';
import classnames from 'classnames';
import { useParams } from 'react-router-dom';
import { Trans } from 'react-i18next';

import { useGetExportDownload } from 'api';
import { Loader } from 'components/controls';

import styles from 'theme/pages/DownloadExport/DownloadExport.module.scss';

const DownloadExport = () => {
  const { hash } = useParams();

  const { isLoading, isError, isSuccess } = useGetExportDownload(hash || '', {
    onSuccess: (data) => {
      const a = document.createElement('a');
      a.download = data.file;
      a.href = `data:application/pdf;base64,${data.content}`;
      a.click();
    }
  });

  return (
    <div className={classnames(styles.componentWrapper, 'StylePath-Pages-DownloadExport')}>
      <h2>Pobieranie pliku exportu</h2>
      {isLoading && <Loader />}
      {isError && <Trans>Wystąpił błąd</Trans>}
      {isSuccess && <Trans>Operacja zakończona sukcesem</Trans>}
    </div>
  );
};

export default DownloadExport;
