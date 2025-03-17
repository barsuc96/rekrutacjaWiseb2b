// dodatkowe filtry pod listą kategorii

import React, { FC, useEffect, useState } from 'react';
import { ExclamationCircle, SortDown } from 'react-bootstrap-icons';
import { useTranslation } from 'react-i18next';
import ReactMarkdown from 'react-markdown';
import classnames from 'classnames';

import { IProductsFilter, IProductsFilterValue } from 'api/types';
import { Select, Button } from 'components/controls';

import styles from 'theme/pages/Products/components/FiltersSidebar/FiltersSidebar.module.scss';

interface IFilter {
  filter_id: string;
  filter_value: string;
}

// typ danych wejściowych
interface IProps {
  categoryId?: number;
  queryFilters: IFilter[];
  filtersData?: IProductsFilter[];
  onChange: (filters: IFilter[]) => void;
}

const FiltersSidebar: FC<IProps> = ({ categoryId, onChange, queryFilters, filtersData }) => {
  const { t } = useTranslation();

  // lista zqaznaczonych filtrów
  const [currentProductsFilters, setCurrentProductsFilters] = useState<
    { filter_id: string; filter_value: string }[]
  >([]);

  // nadpisanie lokalnych filtrów przy zmienie filtrów z props'ów
  useEffect(() => {
    setCurrentProductsFilters(queryFilters);
  }, [queryFilters]);

  return (
    <div
      className={classnames(
        styles.wrapperComponent,
        'StylePath-Pages-Products-components-FiltersSidebar'
      )}>
      {(!categoryId || (filtersData?.length || 0) > 0) && (
        <div className={styles.title}>{t('Filtry')}</div>
      )}

      {!categoryId && (
        <div className={styles.info}>
          <ExclamationCircle />
          <span>
            <ReactMarkdown disallowedElements={['p']} unwrapDisallowed>
              {t('PRODUCTS_SIDEBAR_NO_FILTERS_INFO')}
            </ReactMarkdown>
          </span>
        </div>
      )}

      {!!categoryId && (filtersData?.length || 0) > 0 && (
        <div className={styles.filtersWrapper}>
          {filtersData?.map((filter) => (
            <div className={styles.filter} key={filter.id}>
              {filter.type === 'singlechoice' ? (
                <Select<IProductsFilterValue>
                  value={
                    currentProductsFilters.find((item) => item.filter_id === filter.id)
                      ?.filter_value
                  }
                  onChange={(item) =>
                    setCurrentProductsFilters((prevState) => [
                      ...prevState.filter(
                        (assignedFilter) => assignedFilter.filter_id !== filter.id
                      ),
                      ...(item ? [{ filter_id: filter.id, filter_value: item.value }] : [])
                    ])
                  }
                  options={filter.values.map((item) => ({
                    value: item.value,
                    label: item.name,
                    item
                  }))}
                  placeholder={filter.label}
                />
              ) : (
                <div className={styles.unknownType}>
                  {t('Nieznany typ filtra')} (<strong>{filter.type}</strong>)
                </div>
              )}
            </div>
          ))}

          <div className={styles.actions}>
            <Button color="secondary" onClick={() => onChange(currentProductsFilters)}>
              {t('Filtruj')} <SortDown />
            </Button>
          </div>
        </div>
      )}
    </div>
  );
};

export default FiltersSidebar;
