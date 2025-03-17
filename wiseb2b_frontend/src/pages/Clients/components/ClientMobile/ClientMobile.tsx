import { IClientListItem } from 'api/types';
import classnames from 'classnames';
import { DropDown, Link } from 'components/controls';
import React, { FC } from 'react';
import { Pencil, ThreeDotsVertical } from 'react-bootstrap-icons';
import { Trans } from 'react-i18next';

import styles from 'theme/pages/Users/components/UserMobile/UserMobile.module.scss';

type IProps = {
  client: IClientListItem;
  index: number;
  formClient: Partial<IClientListItem> | null;
  setFormClient: (value: React.SetStateAction<Partial<IClientListItem> | null>) => void;
};

const UserMobile: FC<IProps> = ({ client, index, setFormClient }) => {
  // const { t } = useTranslation();

  return (
    <div
      className={classnames(styles.mobileWrapper, 'StylePath-Pages-Clients-components-ClientMobile')}>
      <div className={styles.mobileRow}>
        <div>
          <span className={styles.lp}>{index + 1}</span>{' '}
          <Link to={`/clients/${client.id}`} className={styles.id}>
            {`${client.first_name} ${client.last_name}`}
          </Link>
        </div>
        <DropDown
          label={<ThreeDotsVertical />}
          items={[
            {
              label: <div
              className={styles.dropdownAction}
              onClick={() => {
                setFormClient(client);
              }}>
                <Trans>Edytuj</Trans>
              <Pencil />
            </div>
            }
          ]}
          withDropdownIcon={false}
        />
      </div>

      <div className={styles.mobileRow}>
        <div>
          <div>
            <Trans>Adres</Trans>:
          </div>
          <div>
            <div className={styles.copy}>{`ul. ${client.address.street}`}</div>
            <div
              className={styles.copy}>{`${client.address.city} ${client.address.postal_code}`}</div>
          </div>
        </div>
      </div>

      <div className={styles.mobileRow}>
        <div>
          <div className={styles.section}>
            <span>
              <Trans>Ilość ofert</Trans>:
            </span>
            <span className={classnames(styles.lp, styles.copy)}>{client.offers_count}</span>
          </div>
          <div className={styles.section}>
            <span>
              <Trans>Ilość zamówień</Trans>:
            </span>
            <span className={classnames(styles.lp, styles.copy)}>{client.orders_count}</span>
          </div>
        </div>
      </div>
    </div>
  );
};

export default UserMobile;
