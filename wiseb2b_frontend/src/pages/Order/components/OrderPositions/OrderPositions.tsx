// pozycje zamówinenia

import React, { FC, useMemo, useState } from 'react';
import { Trans } from 'react-i18next';
import classnames from 'classnames';

import { useRWD } from 'hooks';
import { useGetOrderExport, useGetOrderPositions, useGetPanelOrderPositions } from 'api';
import { IOrderPositionListItem } from 'api/types';
import { AddPositionsToCartSelect } from 'components/containers';
import { Link, Button, Checkbox } from 'components/controls';
import Table, { IColumn } from 'components/controls/Table';

import { ArrowDropdown, FileExportIcon } from 'assets/icons';
import styles from 'theme/pages/Order/components/OrderPositions/OrderPositions.module.scss';
import { ChevronDown } from 'react-bootstrap-icons';
import ProductMobile from 'components/containers/ProductMobile';

// typ danych wejściowych
interface IProps {
  orderId: number;
  isPanel: boolean;
}

const OrderPositions: FC<IProps> = ({ orderId, isPanel }) => {
  const { isMobile } = useRWD();

  const [showMore, setShowMore] = useState<boolean>(false);

  // zaznaczone pozycje
  const [checkedItemIds, setCheckedItemIds] = useState<number[]>([]);

  // export zamówienia do pdf
  const { refetch: downloadExcel } = useGetOrderExport(
    orderId || 0,
    { export_type: 'xls' },
    {
      enabled: false,
      onSuccess: (data) => {
        const a = document.createElement('a');
        a.download = data.file_name;
        a.href = `data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,${data.content}`;
        a.click();
      }
    }
  );

  // pobranie listy pozycji
  const { data: orderPositionsData } = (isPanel ? useGetPanelOrderPositions : useGetOrderPositions)(
    orderId,
    {
      page: 1,
      limit: 999
    }
  );

  const columns: IColumn<IOrderPositionListItem>[] = useMemo(
    () => [
      {
        title: (
          <>
            <Checkbox
              checked={
                checkedItemIds.length > 0 &&
                checkedItemIds.length === (orderPositionsData?.items || []).length
              }
              indeterminate={
                checkedItemIds.length > 0 &&
                checkedItemIds.length < (orderPositionsData?.items || []).length
              }
              onClick={(event) => {
                event.stopPropagation();
                setCheckedItemIds(
                  checkedItemIds.length < (orderPositionsData?.items || []).length
                    ? (orderPositionsData?.items || []).map((position) => position.id)
                    : []
                );
              }}
            />
            <div className={styles.counter}>
              <Trans>LP</Trans>
            </div>
          </>
        ),
        key: 'lp',
        align: 'center',
        renderCell: (item, index) => {
          const isChecked = checkedItemIds.includes(item.id);

          return (
            <>
              <Checkbox
                checked={isChecked}
                onClick={() => {
                  setCheckedItemIds((old) =>
                    isChecked ? old.filter((checkbox) => checkbox !== item.id) : [...old, item.id]
                  );
                }}
              />
              <div className={styles.counter}>{index + 1}</div>
            </>
          );
        },
        width: 100
      },
      {
        title: '',
        key: 'product-image',
        renderCell: (item) => (
          <div className={styles.productThumb}>
            <img src={item.image[0]?.thumb} alt="" />
          </div>
        ),
        width: 78
      },
      {
        title: <Trans>Nazwa produktu</Trans>,
        key: 'name',
        align: 'left',
        renderCell: (item) => (
          <Link to={`/${item.url_link}`} className={styles.link}>
            {item.name}
          </Link>
        )
      },
      {
        title: <Trans>Ilość</Trans>,
        key: 'quantity',
        align: 'center',
        renderCell: (item) => <strong>{item.quantity}</strong>
      },
      {
        title: <Trans>Jednostka</Trans>,
        key: 'unit',
        align: 'center',
        renderCell: (item) => {
          const unit = item.units.find((unit) => unit.unit_id === item.unit_id);
          return (
            <>
              <strong>{unit?.name}</strong>
              {unit?.converter_message && (
                <span className={styles.unitConverterMessage}>{unit.converter_message}</span>
              )}
            </>
          );
        }
      },
      {
        title: (
          <div className={styles.noWrapLabel}>
            <Trans>Cena netto</Trans>
          </div>
        ),
        key: 'net_value',
        align: 'right',
        renderCell: (item) => (
          <>
            <strong>
              {item.price_net_formatted} {item.currency}
            </strong>
          </>
        )
      },
      {
        title: (
          <div className={styles.noWrapLabel}>
            <Trans>Cena brutto</Trans>
          </div>
        ),
        key: 'gross_value',
        align: 'right',
        renderCell: (item) => (
          <>
            <strong>
              {item.price_gross_formatted} {item.currency}
            </strong>
          </>
        )
      },
      {
        title: (
          <div className={styles.noWrapLabel}>
            <Trans>Wartość</Trans>
          </div>
        ),
        key: 'summary',
        align: 'right',
        renderCell: (item) => (
          <>
            <div className={classnames(styles.NoWrapLabel, styles.right, styles.last)}>
              <strong>
                {item.total_price_gross_formatted} {item.currency}
              </strong>{' '}
              <span>
                <Trans>brutto</Trans>
              </span>
            </div>
            <div className={classnames(styles.NoWrapLabel, styles.right, styles.last)}>
              {item.total_price_net_formatted} {item.currency}{' '}
              <span>
                <Trans>netto</Trans>
              </span>
            </div>
          </>
        )
      }
    ],
    [checkedItemIds, setCheckedItemIds, orderPositionsData]
  );

  return (
    <div
      className={classnames(
        styles.wrapperComponent,
        'StylePath-Pages-Order-components-OrderPositions'
      )}>
      {!isMobile && (
        <div className={styles.actionsBar}>
          <ArrowDropdown />
          <div className={styles.cartSelectorWrapper}>
            <AddPositionsToCartSelect
              positions={
                orderPositionsData?.items
                  .filter((item) => checkedItemIds.includes(item.id))
                  .map((item) => ({
                    product_id: item.product_id,
                    quantity: item.quantity,
                    unit_id: item.unit_id
                  })) || []
              }
              onSuccess={() => setCheckedItemIds([])}
            />
          </div>

          <div className={styles.exportWrapper}>
            <Button onClick={() => downloadExcel()} ghost>
              <FileExportIcon />
              <Trans>Exportuj do Excela</Trans>
            </Button>
          </div>
        </div>
      )}
      {!showMore && (
        <div className={styles.showMore} onClick={() => setShowMore((prev) => !prev)}>
          <Trans>Rozwiń zawartość zamówienia</Trans> <ChevronDown />
        </div>
      )}
      {(!isMobile || showMore) && (
        <div className={styles.tableWrapper}>
          <Table<IOrderPositionListItem>
            columns={columns}
            mobileItem={(item) => {
              return <ProductMobile item={item} />;
            }}
            dataSource={orderPositionsData?.items || []}
            rowKey="id"
          />
        </div>
      )}
    </div>
  );
};

export default OrderPositions;
