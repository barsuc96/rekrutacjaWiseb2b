import React, { useEffect, useMemo, useState } from 'react';
import { Trans, useTranslation } from 'react-i18next';
import { Pencil } from 'react-bootstrap-icons';
import classnames from 'classnames';

import { reduxActions, useDispatch } from 'store';
import { useGetClients } from 'api';
import { IClientListItem } from 'api/types';
import { SearchInput, PageTitle, Button, Modal } from 'components/controls';
import Table, { IColumn } from 'components/controls/Table';
import { ClientForm, ClientActivate } from './components';
import ClientMobile from './components/ClientMobile';
import { useRWD } from 'hooks';

import styles from 'theme/pages/Clients/Clients.module.scss';

const Clients = () => {
  const { t } = useTranslation();
  const dispatch = useDispatch();
  const { isMobile } = useRWD();

  // dane klienta do wypełnienia formularza
  const [formClient, setFormClient] = useState<Partial<IClientListItem> | null>(null);

  // parametry zapytania o listę klientów
  const [queryParams, setQueryParams] = useState({
    page: 1,
    limit: 20,
    search_keyword: ''
  });

  // pobranie listy klientów
  const { data: clientsData, refetch: refetchClients } = useGetClients(queryParams);

  // ustawienie breadcrumbs'ów na starcie strony
  useEffect(() => {
    dispatch(
      reduxActions.setBreadcrumbs([
        { name: t('Dashboard'), path: '/dashboard' },
        { name: t('Lista klientów') }
      ])
    );
  }, []);

  const columns: IColumn<IClientListItem>[] = useMemo(
    () => [
      {
        title: <Trans>LP</Trans>,
        key: 'lp',
        align: 'center',
        width: 50,
        renderCell: (item, index) => index + 1
      },
      {
        title: <Trans>Nazwa odbiorcy</Trans>,
        dataIndex: 'name',
        align: 'left',
        renderCell: (item) => (
          <span className={classnames(styles.colored, styles.primary)}>{item.name}</span>
        )
      },
      {
        title: <Trans>Status</Trans>,
        dataIndex: 'status_formatted',
        align: 'left'
      },
      {
        title: <Trans>Ilość ofert</Trans>,
        dataIndex: 'offers_count',
        align: 'center'
      },
      {
        title: <Trans>Ilość zamówień</Trans>,
        dataIndex: 'orders_count',
        align: 'center'
      },
      {
        title: <Trans>Adres</Trans>,
        key: 'address',
        align: 'left',
        renderCell: (item) =>
          `${item.address?.street}, ${item.address?.postal_code} ${item.address?.city}`
      },
      {
        title: <Trans>Edytuj</Trans>,
        key: 'edit',
        align: 'center',
        renderCell: (item) => (
          <button
            className={styles.editButton}
            onClick={() => {
              setFormClient(item);
            }}>
            <Pencil />
          </button>
        )
      },
      {
        title: '',
        key: 'activate',
        align: 'center',
        renderCell: (item) => <ClientActivate id={item.id} refetchClients={refetchClients} />
      }
    ],
    []
  );

  return (
    <>
      <div className={classnames(styles.componentWrapper, 'StylePath-Pages-Clients')}>
        <PageTitle
          title={
            <>
              <Trans>Lista klientów</Trans>{' '}
              <span className="thin">({clientsData?.total_count})</span>
            </>
          }
        />
        <div className={styles.filtersWrapper}>
          <SearchInput
            placeholder={`${t('Szukaj klienta')}...`}
            value={queryParams.search_keyword}
            onChange={(value) =>
              setQueryParams((prevState) => ({ ...prevState, search_keyword: value, page: 1 }))
            }
          />

          <Button ghost color="secondary" onClick={() => setFormClient({})}>
            <Trans>Nowy klient</Trans>
          </Button>
        </div>

        <div className={styles.tableWrapper}>
          <Table
            mobileItem={(item, index) => (
              <ClientMobile
                key={item.id}
                formClient={formClient}
                setFormClient={setFormClient}
                client={item}
                index={index}
              />
            )}
            columns={columns}
            dataSource={clientsData?.items || []}
            rowKey="id"
            pagination={{
              page: queryParams.page,
              pagesCount: clientsData?.total_pages || 1,
              onChange: (page) => setQueryParams((prevState) => ({ ...prevState, page }))
            }}
          />
        </div>
      </div>

      {!!formClient && (
        <Modal
          fullScreen={isMobile}
          onClose={() => setFormClient(null)}
          title={formClient.id ? `${t('Edytuj klienta')}: ${formClient.name}` : t('Nowy klient')}>
          <ClientForm
            id={formClient.id}
            onSuccess={refetchClients}
            onCancel={() => setFormClient(null)}
          />
        </Modal>
      )}
    </>
  );
};

export default Clients;
