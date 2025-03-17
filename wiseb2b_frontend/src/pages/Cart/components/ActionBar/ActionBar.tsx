// panel nad tabelką na stronie koszyka

import React, { FC, useMemo, useState, useEffect } from 'react';
import { Trans, useTranslation } from 'react-i18next';
import {
  CaretDownFill,
  FileEarmarkArrowDown,
  FilePdf,
  Gear,
  Pencil,
  Trash,
  FiletypeXls,
  FiletypeCsv
} from 'react-bootstrap-icons';
import classnames from 'classnames';

import { reduxActions, useDispatch } from 'store';
import { useAppNavigate, useRWD } from 'hooks';
import { useGetCartsAll, useGetCartExport, usePutCart, useDeleteCart } from 'api';
import { ICartListItem } from 'api/types';
import { Button, Modal, Input, SearchInput, Select, DropDown } from 'components/controls';
import ExportButton from './components/ExportButton';

import styles from 'theme/pages/Cart/components/ActionBar/ActionBar.module.scss';

// typ danych wejściowych
interface IProps {
  cart?: ICartListItem;
  setSearchKeyword: (searchKeyword: string) => void;
}

const ActionBar: FC<IProps> = ({ cart, setSearchKeyword }) => {
  const navigate = useAppNavigate();
  const { t } = useTranslation();
  const dispatch = useDispatch();

  const { isMobile } = useRWD();

  // nazwa koszyka (przy edycji)
  const [cartName, setCartName] = useState(cart?.name || '');

  // czy jest widoczny modal potwierdzający usunięcie koszyka
  const [openConfirmationModal, setOpenConfirmationModal] = useState(false);

  // czy jest widoczny modal ze zmianą nazwy koszyka
  const [openChangeNameModal, setOpenChangeNameModal] = useState(false);

  // pobranie listy koszyków
  const { data: cartsData, refetch: refetchCarts } = useGetCartsAll();

  // eksport koszyka do PDF
  const { refetch: downloadPdf } = useGetCartExport(
    cart?.id || 0,
    {
      exportType: 'pdf'
    },
    {
      enabled: false,
      onSuccess: (data) => {
        const a = document.createElement('a');
        a.download = data.file_name;
        a.href = `data:application/pdf;base64,${data.content}`;
        a.click();
      }
    }
  );

  // skakowanie koszyka
  const { mutate: deleteCart, isLoading: isCartDeleting } = useDeleteCart(cart?.id || 0, {
    onSuccess: () => {
      const cartIdToRedirect = cartsData?.items[0]?.id || cartsData?.items[1]?.id;
      navigate(cartIdToRedirect ? `/cart/${cartIdToRedirect}` : '/');
      refetchCarts();
      setOpenConfirmationModal(false);
    }
  });

  // zmiana wartości po zmianie koszyka
  useEffect(() => {
    if (cart?.id) {
      dispatch(reduxActions.setCurrentCartId(cart.id));
      setCartName(cart.name);
    }
  }, [cart?.id]);

  // aktualizacja danych (nazwy) koszyka
  const {
    mutate: updateCart,
    isLoading: isCartUpdating,
    error,
    reset: resetPutCart
  } = usePutCart(cart?.id || 0, {
    onSuccess: () => {
      refetchCarts();
      setOpenChangeNameModal(false);
    }
  });

  // opcje selektora koszyków
  const cartSelectOptions = useMemo(
    () =>
      cartsData?.items.map((cart) => ({
        value: cart.id,
        label: (
          <>
            {cart.name} <span>({cart.products_count})</span>
          </>
        ),
        item: cart
      })) || [],
    [cartsData]
  );

  // opcja aktualnego (wybranego) koszyka
  const currentCartOption = useMemo(() => {
    const option = cartSelectOptions.find((item) => item.value === cart?.id);

    return option
      ? {
          ...option,
          label: <div className={styles.cartSelectorOption}>{option.label}</div>
        }
      : undefined;
  }, [cartSelectOptions, cart?.id]);

  // pozycje w rozwijanych akcjach dotyczących koszyka
  const actionMenuItems = [
    {
      label: (
        <div className={styles.cartActionsItemLabel}>
          <Trash /> {t('Usuń koszyk')}
        </div>
      ),
      onClick: () => setOpenConfirmationModal(true)
    },
    {
      label: (
        <div className={styles.cartActionsItemLabel}>
          <Pencil /> {t('Edytuj nazwę')}
        </div>
      ),
      onClick: () => setOpenChangeNameModal(true)
    },
    {
      label: (
        <div className={styles.cartActionsItemLabel}>
          <FileEarmarkArrowDown /> {t('Importuj koszyk')}
        </div>
      ),
      onClick: () => navigate(`/dashboard/carts/import`)
    },
    {
      label: (
        <ExportButton id={cart?.id || 0} fileType="pdf">
          <FilePdf /> {t('Pobierz PDF')}
        </ExportButton>
      )
    },
    {
      label: (
        <ExportButton id={cart?.id || 0} fileType="xls">
          <FiletypeXls /> {t('Pobierz XLS')}
        </ExportButton>
      )
    },
    {
      label: (
        <ExportButton id={cart?.id || 0} fileType="csv">
          <FiletypeCsv /> {t('Pobierz CSV')}
        </ExportButton>
      )
    }
  ];

  return (
    <>
      <div
        className={classnames(
          styles.componentWrapper,
          'StylePath-Pages-Cart-Components-ActionBar'
        )}>
        <div className={styles.searchFormWrapper}>
          <SearchInput
            placeholder={`${t('Szukaj produktu w koszyku')}...`}
            onChange={setSearchKeyword}
          />
        </div>

        <div className={styles.cartSelectorWrapper}>
          <Select<ICartListItem>
            variant={isMobile ? 'borderless' : 'bordered'}
            selectedOption={currentCartOption}
            options={cartSelectOptions}
            onChange={(item) => navigate(item ? `/cart/${item.id}` : '/')}
          />
        </div>

        <div className={styles.actionMenuWrapper}>
          <DropDown
            label={
              <div className={styles.cartActionsLabel}>
                <Gear /> <Trans>Akcje</Trans> <CaretDownFill />
              </div>
            }
            items={actionMenuItems}
            disableClose
            withDropdownIcon={false}
          />

          {isMobile && (
            <div onClick={() => downloadPdf()} className={styles.cartActionsItemLabel}>
              <FilePdf /> {t('Pobierz PDF')}
            </div>
          )}
        </div>
      </div>

      {openConfirmationModal && (
        <Modal title={t('Potwierdzenie usunięcia')} onClose={() => setOpenConfirmationModal(false)}>
          <div className={styles.confirmationModal}>
            <Trans>Czy napewno usunąć koszyk?</Trans>
            <div className={styles.confirmationModalActions}>
              <Button onClick={() => setOpenConfirmationModal(false)} color="secondary" ghost>
                {t('Anuluj')}
              </Button>
              <Button color="secondary" loading={isCartDeleting} onClick={() => deleteCart()}>
                {t('Usuń')}
              </Button>
            </div>
          </div>
        </Modal>
      )}

      {openChangeNameModal && (
        <Modal
          title={t('Zmień nazwę')}
          onClose={() => {
            setCartName(cart?.name || '');
            setOpenChangeNameModal(false);
            resetPutCart();
          }}>
          <div className={styles.confirmationModal}>
            <Input
              value={cartName}
              onChange={(value) => setCartName(value)}
              type="text"
              placeholder={t('Zmień nazwę')}
              error={error?.error_fields?.find((item) => item.property_path === 'name')?.message}
            />
            <div className={styles.confirmationModalActions}>
              <Button
                color="secondary"
                loading={isCartUpdating}
                onClick={() =>
                  updateCart({
                    name: cartName
                  })
                }>
                {t('Zapisz')}
              </Button>
            </div>
          </div>
        </Modal>
      )}
    </>
  );
};

export default ActionBar;
