import React, { useState } from 'react';
import classnames from 'classnames';
import { format } from 'date-fns';
import { ChevronDown, ThreeDotsVertical, Trash } from 'react-bootstrap-icons';
import { Trans } from 'react-i18next';

import { useGetCartPositions } from 'api';
import { ICartListItem } from 'api/types';
import ProductMobile from 'components/containers/ProductMobile';
import { DropDown, Link } from 'components/controls';

import { TrashIcon } from 'assets/icons';
import styles from 'theme/pages/Carts/components/CartMobile/CartMobile.module.scss';

type Props = {
  index: number;
  item: ICartListItem;
  setCartIdToDelete: () => void
};

const CartMobile = ({ item, index, setCartIdToDelete }: Props) => {
  const [showMore, setShowMore] = useState<boolean>(false);

  const { data: cartPositionsData } = useGetCartPositions(item.id);
  return (
    <div
      className={classnames(styles.mobileWrapper, 'StylePath-Pages-Carts-components-CartMobile')}>
      <div className={styles.mobileRow}>
        <div>
          <span className={styles.lp}>{index + 1}</span>{' '}
          <Link to={`/cart/${item.id}`} className={styles.id}>
            ID{item.id}D
          </Link>
        </div>
        <DropDown
          label={<ThreeDotsVertical />}
          items={[
            {
              label: (
                <div onClick={() => setCartIdToDelete()} className={classnames(styles.dropdownAction, styles.bin)}>
                  <Trash /> <Trans>Usuń koszyk</Trans>{' '}
                </div>
              )
            }
          ]}
          withDropdownIcon={false}
        />
      </div>

      <div className={styles.mobileRow}>
        <div>
          <Trans>Ilość produktów</Trans>:
          <span className={classnames(styles.lp, styles.copy)}>{item.products_count}</span>
        </div>
      </div>

      <div className={styles.mobileRow}>
        <div className={styles.priceWrapper}>
          <div>
            <span>
              <Trans>Data utworzenia</Trans>:
            </span>{' '}
            <span>{format(new Date(item.create_datetime), 'dd-MM-yyyy')}</span>
          </div>
          <div>
            <span>
              <Trans>Wartość netto</Trans>:
            </span>{' '}
            <span>
              {item.value_net_formatted}
              {item.currency}
            </span>
          </div>
          <div>
            <span>
              <Trans>Wartość brutto</Trans>:
            </span>{' '}
            <span>
              {item.value_gross_formatted}
              {item.currency}
            </span>
          </div>
        </div>
      </div>

      <div className={styles.mobileRow}>
        <TrashIcon onClick={() => setCartIdToDelete()} />
        {cartPositionsData?.items.length ? (
          <div className={styles.showMore} onClick={() => setShowMore((prev) => !prev)}>
            <Trans>{!showMore ? 'Zwiń listę produktów' : 'Lista produktów'}</Trans>{' '}
            <ChevronDown className={classnames(styles.arrow, { [styles.open]: showMore })} />
          </div>
        ) : null}
      </div>
      {showMore && (
        <div className={styles.productsWrapper}>
          {cartPositionsData?.items?.map((item) => {
            return <ProductMobile key={item.id} item={item} />;
          })}
        </div>
      )}
    </div>
  );
};

export default CartMobile;
