import React, { FC, useState } from 'react';
import { Trans, useTranslation } from 'react-i18next';
import classnames from 'classnames';

import {
  IPaginationResponse,
  IProductsFilter,
  IProductsFilterValue,
  IProductsRequest,
  IProductsSortMethod
} from 'api/types';
import { Select } from 'components/controls';

import styles from 'theme/pages/Products/components/FiltersOverlay/FiltersOverlay.module.scss';

interface IFilter {
  filter_id: string;
  filter_value: string;
}

type ISortResponse = IPaginationResponse<IProductsSortMethod>;

// typ danych wejściowych
interface IProps {
  categoryId?: number;
  queryFilters: IFilter[];
  filtersData?: IProductsFilter[];
  productsSortingMethodsData?: ISortResponse;
  onChange: (filters: IFilter[]) => void;
  close: () => void;
  setSort: (id: string) => void;
  productsQuery: IProductsRequest;
}

const FiltersOverlay: FC<IProps> = ({
  onChange,
  filtersData,
  productsQuery,
  setSort,
  productsSortingMethodsData,
  close
}) => {
  const { t } = useTranslation();

  const [filters, setFilters] = useState<IFilter[]>([]);

  return (
    <div
      className={classnames(
        styles.wrapperComponent,
        'StylePath-Pages-Products-components-FiltersOverlay'
      )}>
      <div className={styles.topBar}>
        <span onClick={() => close()} className={styles.exit}>
          &times;
        </span>
        <span className={styles.header}>
          <Trans>Filtrowanie</Trans>/<Trans>Sortowanie</Trans>
        </span>
        <span
          onClick={() => {
            setFilters([]);
            onChange([]);
            setSort('');
          }}
          className={styles.clear}>
          <Trans>Wyczyść</Trans>
        </span>
      </div>

      <div className={styles.content}>
        <div className={styles.sortWrapper}>
          <div className={styles.sortHeader}>
            <Trans>Sortuj</Trans>:
          </div>
          <div className={styles.sort}>
            {productsSortingMethodsData?.items.map((item) => {
              return (
                <button
                  onClick={() => setSort(item.id)}
                  className={classnames(styles.sortButton, {
                    [styles.active]: productsQuery?.sort_method == item.id
                  })}
                  key={item.id}>
                  {item.name}
                </button>
              );
            })}
          </div>
        </div>

        <div className={styles.filterWrapper}>
          <div className={styles.filterHeader}>
            <Trans>Filtruj</Trans>:
          </div>

          <div className={styles.filter}>
            {filtersData?.map((filter) => {
              return (
                <div className={styles.filter} key={filter.id}>
                  {filter.type === 'singlechoice' ? (
                    <Select<IProductsFilterValue>
                      value={filters?.find((item) => item?.filter_id === filter.id)?.filter_value}
                      onChange={(item) => {
                        setFilters((prev: IFilter[]) => [
                          ...prev.filter(
                            (assignedFilter) => assignedFilter.filter_id !== filter.id
                          ),
                          ...(item ? [{ filter_id: filter.id, filter_value: item.value }] : [])
                        ]);
                      }}
                      options={filter.values.map((item) => ({
                        value: item.value,
                        label: item.name,
                        item
                      }))}
                      placeholder={filter.label}
                      clearable
                    />
                  ) : (
                    <div className={styles.unknownType}>
                      {t('Nieznany typ filtra')} (<strong>{filter.type}</strong>)
                    </div>
                  )}
                </div>
              );
            })}
          </div>
        </div>
      </div>

      <div className={styles.bottomBar}>
        <button
          onClick={() => {
            onChange(filters);
          }}
          className={styles.filterButton}>
          <Trans>Filtruj</Trans>
        </button>
      </div>
    </div>
  );
};

export default FiltersOverlay;
