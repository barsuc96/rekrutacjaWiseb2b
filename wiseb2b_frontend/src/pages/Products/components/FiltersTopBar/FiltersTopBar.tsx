// główne filtry listy produktów

import React, { FC } from 'react';
import { useTranslation } from 'react-i18next';
import classnames from 'classnames';

import { IProductsFilter, IProductsFilterValue } from 'api/types';
import { Select, Range } from 'components/controls';

import styles from 'theme/pages/Products/components/FiltersTopBar/FiltersTopBar.module.scss';

interface IFilter {
  filter_id: string;
  filter_value: string;
}

// typ danych wejściowych
interface IProps {
  queryFilters: IFilter[];
  onChange: (filters: IFilter[]) => void;
  filtersData?: IProductsFilter[];
}

const FiltersTopBar: FC<IProps> = ({ onChange, queryFilters, filtersData }) => {
  const { t } = useTranslation();

  const renderFilterType = (filter: IProductsFilter) => {
    if (filter.type === 'singlechoice') {
      return (
        <Select<IProductsFilterValue>
          value={queryFilters.find((item) => item.filter_id === filter.id)?.filter_value}
          onChange={(item) =>
            onChange([
              ...queryFilters.filter((assignedFilter) => assignedFilter.filter_id !== filter.id),
              ...(item ? [{ filter_id: filter.id, filter_value: item.value }] : [])
            ])
          }
          options={filter.values.map((item) => ({
            value: item.value,
            label: item.name,
            item
          }))}
          placeholder={filter.label}
          clearable
        />
      );
    }

    if (filter.type === 'range') {
      return (
        <Range
          onChange={onChange}
          queryFilters={queryFilters}
          filter={{
            ...filter,
            values: filter.values
          }}
        />
      );
    }

    return (
      <div className={styles.unknownType}>
        {t('Nieznany typ filtra')} (<strong>{filter.type}</strong>)
      </div>
    );
  };

  return (
    <div
      className={classnames(
        styles.wrapperComponent,
        'StylePath-Pages-Products-components-FiltersTopBar'
      )}>
      <div className={styles.filtersWrapper}>
        {filtersData?.map((filter) => renderFilterType(filter))}
      </div>
    </div>
  );
};

export default FiltersTopBar;
