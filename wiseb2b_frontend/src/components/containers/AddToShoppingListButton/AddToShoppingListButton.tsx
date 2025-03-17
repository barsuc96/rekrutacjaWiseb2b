// Przycisk dodawania pozycji do listy zakupowej

import React, { FC, useState } from 'react';
import { useTranslation, Trans } from 'react-i18next';
import classnames from 'classnames';

import { IProductListItem, IUnit, IProduct } from 'api/types';
import { JournalText } from 'react-bootstrap-icons';
import { Modal } from 'components/controls';
import { AddToShoppingListForm } from 'components/containers';

import styles from 'theme/components/containers/AddToShoppingListButton/AddToShoppingListButton.module.scss';
import { useRWD } from 'hooks';

// typ danych wejściowych
interface IProps {
  product: IProductListItem | IProduct;
  unit?: IUnit;
  quantity: number;
  openModal?: boolean;
  setOpenModal?: React.Dispatch<React.SetStateAction<boolean>>;
}

const AddToShoppingListButton: FC<IProps> = ({
  product,
  unit,
  quantity,
  openModal,
  setOpenModal
}) => {
  const { t } = useTranslation();
  const { isMobile } = useRWD();

  // czy jest modal dodawania do listy zakupowej
  const [isAddToShoppingListModal, setIsAddToShoppingListModal] = useState(false);

  return (
    <div
      className={classnames(
        styles.wrapperComponent,
        'StylePath-Components-Containers-AddToShoppingListButton'
      )}>
      <div
        className={styles.shoppingListButton}
        onClick={() => {
          setOpenModal ? setOpenModal(true) : setIsAddToShoppingListModal(true);
        }}>
        <JournalText />
        <Trans>Dodaj do listy</Trans>
      </div>

      {(openModal || isAddToShoppingListModal) && (
        <Modal
          fullScreen={isMobile}
          title={t('Dodawanie produktów do listy zakupowej')}
          onClose={() => {
            setOpenModal ? setOpenModal(false) : setIsAddToShoppingListModal(false);
          }}>
          <AddToShoppingListForm
            items={[
              {
                product_id: product.id,
                unit_id: unit?.unit_id || product.default_unit_id,
                quantity: quantity,
                image: product.images[0]?.thumb,
                title: product.title,
                index: product.index,
                price_net_formatted: unit?.price_net_formatted,
                price_gross_formatted: unit?.price_gross_formatted,
                currency: product.currency
              }
            ]}
            onCancel={() => {
              setOpenModal ? setOpenModal(false) : setIsAddToShoppingListModal(false);
            }}
          />
        </Modal>
      )}
    </div>
  );
};

export default AddToShoppingListButton;
