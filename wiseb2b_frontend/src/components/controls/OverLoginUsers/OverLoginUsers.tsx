import React, { useEffect, useState, useMemo } from 'react';
import { useTranslation, Trans } from 'react-i18next';
import { useLocation } from 'react-router-dom';
import { ChevronDown } from 'react-bootstrap-icons';

import { useAppNavigate } from 'hooks';
import { useGetAuthOverloginUsers, usePostOverLogin } from 'api';
import { IOverloginUserListItem } from 'api/types';
import { useDispatch, reduxActions, useSelector } from 'store';
import { Modal, SearchInput, Button } from 'components/controls';
import Table, { IColumn } from 'components/controls/Table';

import styles from 'theme/components/layouts/MainLayout/components/HeaderTopBar/HeaderTopBar.module.scss';

const OverLoginUsers = () => {
  const dispatch = useDispatch();
  const { t } = useTranslation();
  const { profile } = useSelector((state) => state.auth);
  const navigate = useAppNavigate();
  const { pathname } = useLocation();

  // parametry zapytania o listę użytkowników
  const [queryParams, setQueryParams] = useState({
    page: 1,
    limit: 10,
    searchKeyword: ''
  });

  // czy widoczny jest modal z przelogowaniem
  const [isOverLoginModal, setIsOverLoginModal] = useState(false);

  // pobranie listy użytkowników możliwych do przelogownania
  const { data: overloginUsersData, refetch: refetchOverloginUsers } = useGetAuthOverloginUsers(
    queryParams,
    { enabled: false }
  );

  // przelogowanie na wybranego użytkownika
  const { mutateAsync: postOverLogin } = usePostOverLogin();

  useEffect(() => {
    if (profile?.role !== 'ROLE_OPEN_PROFILE') {
      refetchOverloginUsers();
    }
  }, [queryParams]);

  useEffect(() => {
    if (!isOverLoginModal) {
      setQueryParams({ searchKeyword: '', page: 1, limit: 10 });
    }
  }, [isOverLoginModal]);

  const handleOverLogin = async (id: number) => {
    await postOverLogin({ to_switch_user_id: id });

    if (pathname.includes('/cart/')) {
      navigate('/cart/0');
    }

    dispatch(reduxActions.setCurrentCartId(null));
    dispatch(reduxActions.setOverlogin(id));
    setIsOverLoginModal(false);
  };

  const columns: IColumn<IOverloginUserListItem>[] = useMemo(
    () => [
      {
        title: <Trans>ID</Trans>,
        dataIndex: 'id',
        align: 'center',
        width: 50
      },
      {
        title: <Trans>Nazwa</Trans>,
        dataIndex: 'name',
        align: 'center'
      },
      {
        title: <Trans>Użytkownik</Trans>,
        key: 'actions',
        align: 'left',
        renderCell: (item) =>
          item.users_list.map((user) => (
            <div className={styles.userListWrapper} key={user.id}>
              <button className={styles.link} onClick={() => handleOverLogin(user.id)}>
                {user.name}
              </button>
            </div>
          ))
      }
    ],
    [overloginUsersData?.items]
  );

  return (
    <div className={styles.overloginWrapper}>
      <button className={styles.overloginButton} onClick={() => setIsOverLoginModal(true)}>
        <Trans>Zaloguj jako</Trans>
        <ChevronDown />
      </button>
      {isOverLoginModal && (
        <Modal title={t('Zaloguj jako')} onClose={() => setIsOverLoginModal(false)}>
          <div className={styles.filtersWrapper}>
            <div className={styles.selectWrapper}>
              <SearchInput
                placeholder={`${t('Szukaj')}...`}
                value={queryParams.searchKeyword}
                onChange={(value) =>
                  setQueryParams(() => ({ searchKeyword: value, page: 1, limit: 10 }))
                }
              />
              {!!queryParams.searchKeyword && (
                <button
                  className="clearFilters"
                  color="secondary"
                  onClick={() => setQueryParams(() => ({ searchKeyword: '', page: 1, limit: 10 }))}>
                  <Trans>Wyczyść filtry</Trans>
                </button>
              )}
            </div>
          </div>
          <div className={styles.overLoginTableWrapper}>
            <Table<IOverloginUserListItem>
              columns={columns}
              dataSource={overloginUsersData?.items || []}
              rowKey="id"
              pagination={{
                page: queryParams.page || 1,
                pagesCount: overloginUsersData?.total_pages || 1,
                onChange: (page) => setQueryParams((prevState) => ({ ...prevState, page }))
              }}
            />
          </div>

          <div className={styles.confirmationModalActions}>
            <Button color="secondary" ghost onClick={() => setIsOverLoginModal(false)}>
              <Trans>Anuluj</Trans>
            </Button>
          </div>
        </Modal>
      )}

      {/* <DropDown
        label={t('Zaloguj jako')}
        isListType
        items={
          overloginUsersData?.items.reduce(
            (acc, item) => [
              ...acc,
              {
                label: item.name,
                disabled: true
              },
              ...item.users_list.map((user) => ({
                label: <span style={{ paddingLeft: 16 }}>{user.name}</span>,
                onClick: () => handleOverLogin(user.id)
              }))
            ],
            [] as {
              label: ReactNode;
              onClick?: () => void;
              disabled?: boolean;
            }[]
          ) || []
        }
      /> */}
    </div>
  );
};

export default OverLoginUsers;
