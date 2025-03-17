// lista exportów cyklicznych

import React, { useMemo, useState } from 'react';
import { Trans, useTranslation } from 'react-i18next';
import classnames from 'classnames';

import { useGetExportsPeriodic } from 'api';
import { IExportPeriodicListItem } from 'api/types';
import { Button, Modal } from 'components/controls';
import Table, { IColumn } from 'components/controls/Table';
import RemoveExport from '../RemoveExport';

import styles from 'theme/pages/Export/components/PeriodicList/PeriodicList.module.scss';

const PeriodicList = () => {
  const { t } = useTranslation();

  // eksport do skasowania
  const [exportToRemove, setExportToRemove] = useState<IExportPeriodicListItem | null>(null);

  // pobranie listy eksportów
  const { data: exportsData, refetch: refetchExports } = useGetExportsPeriodic({
    page: 1,
    limit: 999
  });

  // kolumny tabelki
  const columns: IColumn<IExportPeriodicListItem>[] = useMemo(
    () => [
      {
        title: <Trans>Nazwa</Trans>,
        dataIndex: 'name',
        align: 'left'
      },
      {
        title: <Trans>Typ pliku</Trans>,
        dataIndex: 'type',
        align: 'center'
      },
      {
        title: <Trans>Data ostatniej generacji</Trans>,
        dataIndex: 'last_generation_date',
        align: 'left'
      },
      {
        title: <Trans>Data następnej generacji</Trans>,
        dataIndex: 'next_generation_date',
        align: 'left'
      },
      {
        title: '',
        key: 'actions',
        renderCell: (item) => (
          <Button color="danger" onClick={() => setExportToRemove(item)}>
            <Trans>usuń</Trans>
          </Button>
        )
      }
    ],
    []
  );

  return (
    <div className={classnames(
      styles.wrapperComponent,
      'StylePath-Pages-Export-components-PeriodicList'
    )}>
      <Table columns={columns} dataSource={exportsData?.items || []} rowKey="id" />

      {!!exportToRemove && (
        <Modal
          onClose={() => setExportToRemove(null)}
          title={`${t('Usuwanie ustawień exportu')} "${exportToRemove.name}"`}>
          <RemoveExport
            data={exportToRemove}
            onCancel={() => setExportToRemove(null)}
            onSuccess={refetchExports}
          />
        </Modal>
      )}
    </div>
  );
};

export default PeriodicList;
