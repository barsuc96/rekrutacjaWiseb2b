// lista użytkowników

import React, {
  FC,
  MouseEventHandler,
  PropsWithChildren,
  useEffect,
  useMemo,
  useState
} from 'react';
import { useTranslation, Trans } from 'react-i18next';
import classnames from 'classnames';

import { reduxActions, useDispatch } from 'store';
import { useGetUsers, useGetUsersRoles } from 'api';
import { IUserListItem } from 'api/types';
import { Agreements } from 'components/containers';
import { SearchInput, PageTitle, Button, Modal, Select } from 'components/controls';
import Table, { IColumn } from 'components/controls/Table';
import { ChangeStatus, UserForm } from './components';
import UserMobile from './components/UserMobile';
import { useRWD } from 'hooks';

import styles from 'theme/pages/Users/Users.module.scss';

interface IColoredProps extends PropsWithChildren {
  onClick?: MouseEventHandler<HTMLSpanElement>;
  agreements?: boolean;
  color: 'primary' | 'secondary' | 'success' | 'error';
}

const DashboardUsers = () => {
  const { t } = useTranslation();
  const dispatch = useDispatch();

  const { isMobile } = useRWD();
  // czy jest modal dodawania użytkownika
  const [isAddUserModal, setIsAddUserModal] = useState(false);

  // użytkownik do wyświetlenia modalu z listą zgód
  const [agreementsUser, setAgreementsUser] = useState<IUserListItem | null>(null);

  // użytkownik do aktywaci/deaktywacji
  const [changeStatusUser, setChangeStatusUser] = useState<IUserListItem | null>(null);

  // parametry zapytania o listę iżytkowników
  const [queryParams, setQueryParams] = useState({
    page: 1,
    limit: 20,
    search_keyword: '',
    role: ''
  });

  // pobranie listy użytkowników
  const { data: usersData, refetch: refetchUsersData } = useGetUsers(queryParams);

  // pobieranie listy możliwych ról dla użytkownika
  const { data: usersRolesData } = useGetUsersRoles();

  // ustawienie breadcrums'ów na starcie strony
  useEffect(() => {
    dispatch(
      reduxActions.setBreadcrumbs([
        { name: t('Dashboard'), path: '/dashboard' },
        { name: t('Lista użytkowników') }
      ])
    );
  }, []);

  const Colored: FC<IColoredProps> = ({ children, agreements, color, ...props }) => (
    <span
      {...props}
      className={classnames(styles.colored, styles[color], {
        [styles.agreements]: agreements
      })}>
      {children}
    </span>
  );

  const columns: IColumn<IUserListItem>[] = useMemo(
    () => [
      {
        title: <Trans>LP</Trans>,
        key: 'lp',
        align: 'center',
        renderCell: (item, index) => index + 1
      },
      {
        title: <Trans>Nazwa użytkownika</Trans>,
        dataIndex: 'last_name',
        align: 'left',
        renderCell: (item) => (
          <Colored color="primary">
            {item.first_name} {item.last_name}
          </Colored>
        )
      },
      {
        title: <Trans>Ilość ofert</Trans>,
        dataIndex: 'total_offers',
        align: 'center'
      },
      {
        title: <Trans>Ilość zamówień</Trans>,
        dataIndex: 'total_orders',
        align: 'center'
      },
      {
        title: <Trans>Adres e-mail</Trans>,
        dataIndex: 'email',
        align: 'left'
      },
      {
        title: <Trans>Zgody</Trans>,
        key: 'agreements',
        align: 'center',
        renderCell: (item) => (
          <Colored color="secondary" agreements onClick={() => setAgreementsUser(item)}>
            <Trans>Lista zgód</Trans>
          </Colored>
        )
      },
      {
        title: <Trans>Status</Trans>,
        dataIndex: 'status',
        align: 'center',
        renderCell: (item) =>
          item.status ? (
            <Colored color="success">
              <Trans>Aktywny</Trans>
            </Colored>
          ) : (
            <Colored color="error">
              <Trans>Nieaktywny</Trans>
            </Colored>
          )
      },
      {
        title: <Trans>Rola</Trans>,
        dataIndex: 'role',
        align: 'center'
      },
      {
        title: '',
        key: 'actions',
        align: 'right',
        renderCell: (item) => (
          <Button
            color={item.status ? 'danger' : 'primary'}
            onClick={() => setChangeStatusUser(item)}
            disabled={!item.can_modify_user}>
            {item.status ? t('Deaktywuj') : t('Aktywuj')}
          </Button>
        )
      }
    ],
    []
  );

  // opcje dropdownu typów pola
  const selectOptions = useMemo(() => {
    const roles = (usersRolesData?.items || []).map((role) => ({
      value: role.role,
      label: role.role_name,
      item: role
    }));

    return roles;
  }, [usersRolesData]);

  return (
    <div className={classnames(styles.wrapperComponent, 'StylePath-Pages-Users')}>
      <PageTitle
        title={
          <>
            <Trans>Lista użytkowników</Trans>{' '}
            <span className="thin">({usersData?.total_count})</span>
          </>
        }
      />
      <div className={styles.filtersWrapper}>
        <div>
          <div className={styles.selectWrapper}>
            <SearchInput
              placeholder={`${t('Szukaj użytkownika')}...`}
              value={queryParams.search_keyword}
              onChange={(value) =>
                setQueryParams((prevState) => ({ ...prevState, search_keyword: value }))
              }
            />
            <Select
              variant="bordered"
              placeholder={t('Rola')}
              value={queryParams?.role}
              onChange={(item) => {
                setQueryParams({
                  page: 1,
                  limit: 20,
                  search_keyword: '',
                  role: item?.role || ''
                });
              }}
              options={selectOptions}
              clearable
            />
          </div>
        </div>

        <Button color="secondary" ghost onClick={() => setIsAddUserModal(true)}>
          <Trans>Nowy użytkownik</Trans>
        </Button>
      </div>

      <Table
        mobileItem={(item, index) => (
          <UserMobile
            key={item.id}
            setAgreementsUser={setAgreementsUser}
            setChangeStatusUser={setChangeStatusUser}
            user={item}
            index={index}
          />
        )}
        columns={columns}
        dataSource={usersData?.items || []}
        rowKey="id"
        pagination={{
          page: queryParams.page,
          pagesCount: usersData?.total_pages || 1,
          onChange: (page) => setQueryParams((prevState) => ({ ...prevState, page }))
        }}
      />

      {!!agreementsUser && (
        <Modal
          fullScreen={isMobile}
          onClose={() => setAgreementsUser(null)}
          title={`${agreementsUser.first_name} ${agreementsUser.last_name} - ${t('lista zgód')}`}>
          <Agreements userId={agreementsUser.id} />
        </Modal>
      )}

      {!!changeStatusUser && (
        <Modal
          title={changeStatusUser.status ? t('Dezaktywacja użykownika') : t('Aktywacja użykownika')}
          onClose={() => setChangeStatusUser(null)}>
          <ChangeStatus
            user={changeStatusUser}
            onCancel={() => setChangeStatusUser(null)}
            onSuccess={refetchUsersData}
          />
        </Modal>
      )}

      {isAddUserModal && (
        <Modal
          fullScreen={isMobile}
          title={t('Nowy użytkownik')}
          onClose={() => setIsAddUserModal(false)}>
          <UserForm onCancel={() => setIsAddUserModal(false)} onSuccess={refetchUsersData} />
        </Modal>
      )}
    </div>
  );
};

export default DashboardUsers;
