import { IUserListItem } from 'api/types';
import classnames from 'classnames';
import { DropDown, Link } from 'components/controls';
import React, { FC, MouseEventHandler, PropsWithChildren } from 'react';
import { ThreeDotsVertical } from 'react-bootstrap-icons';
import { Trans } from 'react-i18next';

import styles from 'theme/pages/Users/components/UserMobile/UserMobile.module.scss';

type IProps = {
  user: IUserListItem;
  index: number;
  setAgreementsUser: (value: React.SetStateAction<IUserListItem | null>) => void;
  setChangeStatusUser: (value: React.SetStateAction<IUserListItem | null>) => void;
};

interface IColoredProps extends PropsWithChildren {
  onClick?: MouseEventHandler<HTMLSpanElement>;
  agreements?: boolean;
  color: 'primary' | 'secondary' | 'success' | 'error';
}

const UserMobile: FC<IProps> = ({ user, index, setAgreementsUser, setChangeStatusUser }) => {
  const Colored: FC<IColoredProps> = ({ children, agreements, color }) => (
    <span
      className={classnames(styles.colored, styles[color], {
        [styles.agreements]: agreements
      })}>
      {children}
    </span>
  );

  return (
    <div
      className={classnames(styles.mobileWrapper, 'StylePath-Pages-Users-components-UserMobile')}>
      <div className={styles.mobileRow}>
        <div>
          <span className={styles.lp}>{index + 1}</span>{' '}
          <Link to={`/users/${user.id}`} className={styles.id}>
            {`${user.first_name} ${user.last_name}`}
          </Link>
        </div>
        <DropDown
          label={<ThreeDotsVertical />}
          items={[
            {
              label: (
                <div
                  className={styles.dropdownAction}
                  onClick={() => {
                    setChangeStatusUser(user);
                  }}>
                  <span>
                    {user?.status == 1 ? (
                      <Trans>Dezaktywacja użykownika</Trans>
                    ) : (
                      <Trans>Aktywacja użykownika</Trans>
                    )}
                  </span>
                </div>
              )
            }
          ]}
          withDropdownIcon={false}
        />
      </div>

      <div className={styles.mobileRow}>
        <div>
          <div className={styles.section}>
            <span>
              <Trans>Ilość ofert</Trans>:
            </span>
            <span className={classnames(styles.lp, styles.copy)}>{user.total_offers}</span>
          </div>
          <div className={styles.section}>
            <span>
              <Trans>Ilość zamówień</Trans>:
            </span>
            <span className={classnames(styles.lp, styles.copy)}>{user.total_orders}</span>
          </div>
        </div>
      </div>

      <div className={styles.mobileRow}>
        <div>
          <div className={styles.section}>
            <span>
              <Trans>Adres e-mail</Trans>:
            </span>
            <span className={classnames(styles.lp, styles.copy)}>{user.email}</span>
          </div>
          <div className={styles.section}>
            <span>
              <Trans>Status</Trans>:
            </span>
            <span className={classnames(styles.lp, styles.copy)}>
              {user.status === 1 ? (
                <Colored color="success">
                  <Trans>Aktywny</Trans>
                </Colored>
              ) : (
                <Colored color="error">
                  <Trans>Nieaktywny</Trans>
                </Colored>
              )}
            </span>
          </div>

          <div>
            <Trans>Lista zgód</Trans>:{' '}
            <Link
              className={styles.link}
              to="#"
              onClick={(e) => {
                e.preventDefault();
                setAgreementsUser(user);
              }}>
              <Trans>Lista zgód</Trans>
            </Link>
          </div>
        </div>
      </div>
    </div>
  );
};

export default UserMobile;
