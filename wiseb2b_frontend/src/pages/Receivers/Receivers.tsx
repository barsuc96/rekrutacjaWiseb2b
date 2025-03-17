// lista odbiorców

import React, { useEffect, useMemo, useState } from 'react';
import { Trans, useTranslation } from 'react-i18next';
import classnames from 'classnames';

import { useGetReceivers } from 'api';
import { IReceiverListItem } from 'api/types';
import { reduxActions, useDispatch } from 'store';
import { ReceiverForm } from 'components/containers';
import { Button, Modal, SearchInput, PageTitle } from 'components/controls';
import Table, { IColumn } from 'components/controls/Table';
import { useRWD } from 'hooks';

import styles from 'theme/pages/Receivers/Receivers.module.scss';


const Receivers = () => {
  const { t } = useTranslation();
  const dispatch = useDispatch();

  const { isMobile } = useRWD();

  // czy widoczny jest modal tworzenia odbiorcy
  const [isReceiverModal, setIsReceiverModal] = useState(false);

  // parametry zapytania api o listę odbiorców
  const [queryParams, setQueryParams] = useState({
    page: 1,
    limit: 20,
    search_keyword: ''
  });

  // pobranie3 listy odbiorców
  const { data: recipientsData, refetch: refetchReceivers } = useGetReceivers(queryParams);

  useEffect(() => {
    dispatch(
      reduxActions.setBreadcrumbs([
        { name: t('Dashboard'), path: '/dashboard' },
        { name: t('Lista odbiorców') }
      ])
    );
  }, []);

  const columns: IColumn<IReceiverListItem>[] = useMemo(
    () => [
      {
        title: <Trans>LP</Trans>,
        dataIndex: 'lp',
        align: 'center',
        width: 50
      },
      {
        title: <Trans>Nazwa odbiorcy</Trans>,
        dataIndex: 'name',
        align: 'left'
      },
      {
        title: <Trans>Adres</Trans>,
        dataIndex: 'address',
        align: 'left',
        renderCell: (item) => {
          const { street, postal_code, city } = item.address;

          return (
            <div>
              ul. {street}, {postal_code} {city}
            </div>
          );
        }
      },
      {
        title: <Trans>Telefon</Trans>,
        dataIndex: 'phone',
        align: 'left'
      },
      {
        title: <Trans>Adres e-mail</Trans>,
        dataIndex: 'email',
        align: 'left'
      }
    ],
    []
  );

  const renderMobileItem = (record: IReceiverListItem) => (
    <div className={styles.mobileItem}>
      <div className={styles.itemHeader}>
        <span>{record.lp}</span>
        <span className={styles.name}>{record.name}</span>
      </div>
      <div className={styles.itemBody}>
        <div>
          <div className={styles.label}>
            <Trans>Adres</Trans>:
          </div>
          {record.address.street}
          <br />
          {record.address.postal_code} {record.address.city}
        </div>
        <div>
          <div className={styles.label}>
            <Trans>Dane kontaktowe</Trans>:
          </div>
          <span className={styles.light}>
            <Trans>Telefon</Trans>:
          </span>{' '}
          1{record.phone}
          <br />
          <span className={styles.light}>
            <Trans>E-mail</Trans>:
          </span>{' '}
          {record.email}
        </div>
      </div>
    </div>
  );

  return (
    <>
      <div className={classnames(styles.wrapperComponent, 'StylePath-Pages-Receivers')}>
        <PageTitle
          title={
            <>
              <Trans>Lista odbiorców</Trans>{' '}
              <span className="thin">({recipientsData?.total_count})</span>
            </>
          }
        />
        <div className={styles.filtersWrapper}>
          <SearchInput
            placeholder={`${t('Szukaj odbiorcę')}...`}
            value={queryParams.search_keyword}
            onChange={(value) =>
              setQueryParams((prevState) => ({ ...prevState, search_keyword: value, page: 1 }))
            }
          />

          <Button onClick={() => setIsReceiverModal(true)}>
            <Trans>Nowy odbiorca</Trans>
          </Button>
        </div>

        <div className={styles.tableWrapper}>
          <Table
            columns={columns}
            dataSource={recipientsData?.items || []}
            rowKey="id"
            mobileItem={renderMobileItem}
            pagination={{
              page: queryParams.page,
              pagesCount: recipientsData?.total_pages || 1,
              onChange: (page) => setQueryParams((prevState) => ({ ...prevState, page }))
            }}
          />
        </div>
      </div>

      {isReceiverModal && (
        <Modal fullScreen={isMobile} onClose={() => setIsReceiverModal(false)} title={t('Nowy odbiorca')}>
          <ReceiverForm
            onSuccess={() => {
              refetchReceivers();
            }}
            onCancel={() => setIsReceiverModal(false)}
          />
        </Modal>
      )}
    </>
  );
};

export default Receivers;
