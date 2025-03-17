// strona importu koszyka

import React, { useEffect, useState, useMemo, ChangeEventHandler } from 'react';
import { Trans, useTranslation } from 'react-i18next';
import classnames from 'classnames';

import { useRWD } from 'hooks';
import { useGetCartsAll, usePostCartImport, usePutCartImport } from 'api';
import { ICartListItem } from 'api/types';
import { reduxActions, useDispatch } from 'store';
import { PageTitle, Button, Modal, FileUploader, Input } from 'components/controls';
import Table, { IColumn } from 'components/controls/Table';

import styles from 'theme/pages/CartsImport/CartsImport.module.scss';
import { FileImportIcon } from 'assets/icons';

const CartsImport = () => {
  const { t } = useTranslation();
  const dispatch = useDispatch();
  const { isMobile } = useRWD();

  // uploadowany plik w base64
  const [base64File, setBase64File] = useState('');

  // nazwa uploadowanego pliku
  const [fileName, setFileName] = useState('');

  // czy widoczny jest modal?
  const [isModalOpen, setIsModalOpen] = useState(false);

  // nazwa nowego koszyka
  const [cartName, setCartName] = useState('');

  // ID wybranego koszyka
  const [cartID, setCartID] = useState<number | null>(null);

  // ustawieni breadcrumbs'ów na starcie strony
  useEffect(() => {
    dispatch(
      reduxActions.setBreadcrumbs([
        { name: t('Dashboard'), path: '/dashboard' },
        { name: t('Importuj koszyk') }
      ])
    );
  }, []);

  const createBase64File = (file: Blob): Promise<string | undefined> =>
    new Promise((resolve, reject) => {
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = () => resolve(reader.result?.toString().replace(/^data:(.*,)?/, ''));
      reader.onerror = (error) => reject(error);
    });

  const handleFileInputChange: ChangeEventHandler<HTMLInputElement> = async (event) => {
    const file = event.target.files?.[0];

    if (file) {
      setFileName(file.name);
      setBase64File((await createBase64File(file)) || '');
    }
  };

  // pobranie listy koszyków
  const { data: cartsData, refetch: cartDataRefetch } = useGetCartsAll({
    onSuccess: (data) => {
      dispatch(reduxActions.setCurrentCartId(data.items[0]?.id) || null);
    }
  });

  // import do nowego koszyka
  const { mutate: importToNewCart, isLoading: importToNewCartLoading } = usePostCartImport({
    onSuccess: () => {
      setCartName('');
      setIsModalOpen(false);
      setCartID(null);
      cartDataRefetch();
    }
  });

  const { mutate: importToCartImport, isLoading: importToCartLoading } = usePutCartImport(
    cartID || 0,
    {
      onSuccess: () => {
        setIsModalOpen(false);
        setCartID(null);
        cartDataRefetch();
      }
    }
  );

  useEffect(() => {
    if (cartID) {
      importToCartImport({
        file: base64File,
        name: ''
      });
    }
  }, [cartID]);

  const columns: IColumn<ICartListItem>[] = useMemo(
    () => [
      {
        title: <Trans>Symbol</Trans>,
        dataIndex: 'name',
        align: 'left'
      },
      {
        title: <Trans>Wartość brutto</Trans>,
        dataIndex: 'value_gross',
        align: 'left',
        renderCell: (item) => (
          <span className={styles.dark}>
            {item.value_gross} {item.currency}
          </span>
        )
      },
      {
        title: <Trans>Wartość netto</Trans>,
        dataIndex: 'value_net',
        align: 'left',
        renderCell: (item) => (
          <span className={styles.light}>
            {item.value_net} {item.currency}
          </span>
        )
      },
      {
        title: <Trans>Akcja</Trans>,
        key: 'action',
        align: 'center',
        renderCell: (item) => (
          <div className={styles.buttonWrapper}>
            <Button
              loading={importToCartLoading && cartID === item.id}
              disabled={importToCartLoading || importToNewCartLoading || !base64File}
              onClick={() => {
                setCartID(item.id);
              }}>
              <Trans>Dodaj do koszyka</Trans>
            </Button>
          </div>
        )
      }
    ],
    [base64File]
  );

  return (
    <div className={classnames(styles.componentWrapper, 'StylePath-Pages-CartsImport')}>
      <PageTitle title={t('Importuj koszyk')} />

      <div className={styles.importWrapper}>
        <div className={styles.title}>
          <Trans>Importuj produkty z pliku csv lub xlsx do koszyka</Trans>
        </div>

        <div className={styles.form}>
          <FileUploader
            buttonLabel={t('Przeglądaj')}
            onChange={handleFileInputChange}
            file={fileName}
          />

          <Button
            loading={importToNewCartLoading}
            disabled={importToCartLoading || importToNewCartLoading || !base64File}
            onClick={() => {
              cartDataRefetch();
              setIsModalOpen(true);
            }}>
            <FileImportIcon />
            <Trans>Importuj do koszyka</Trans>
          </Button>
        </div>

        <div className={styles.examples}>
          <Trans>Pobierz przyklądowy plik</Trans>:
          <a
            href={`${process.env.REACT_APP_API_BASE_URL}/files/przyklad_importu_koszyk_OK.xlsx`}
            target="_blank"
            rel="noreferrer">
            XLS
          </a>
          <a
            href={`${process.env.REACT_APP_API_BASE_URL}/files/przyklad_importu_koszyk_OK.csv`}
            target="_blank"
            rel="noreferrer">
            CSV
          </a>
        </div>
      </div>

      {isModalOpen && (
        <Modal
          fullScreen={isMobile}
          title={t('Importowanie produktów z pliku do koszyka')}
          onClose={() => setIsModalOpen(false)}>
          <div className={styles.modalBody}>
            <div className={styles.formWrapper}>
              <Input
                onChange={setCartName}
                placeholder={t('Ustaw symbol nowego koszyka')}
                value={cartName}
              />

              <Button
                loading={importToNewCartLoading}
                onClick={() =>
                  importToNewCart({
                    file: base64File,
                    name: cartName
                  })
                }>
                <Trans>Dodaj do nowego koszyka</Trans>
              </Button>
            </div>

            <div className={styles.text}>
              <Trans>lub wybierz koszyk z listy aktywnych koszyków</Trans>
            </div>

            <div className={styles.modalTable}>
              <Table<ICartListItem>
                rowKey="id"
                dataSource={cartsData?.items || []}
                columns={columns}
              />
            </div>
          </div>
        </Modal>
      )}
    </div>
  );
};

export default CartsImport;
