/* eslint-disable @typescript-eslint/no-unused-vars */
/* eslint-disable @typescript-eslint/no-explicit-any */
import React, { FC, useEffect, Dispatch, SetStateAction } from 'react';

import { Select } from 'components/controls';

import { reduxActions, useDispatch } from 'store';
import { IDynamicFilter } from 'plugins/api/types';
import { useLoadData } from 'plugins/api/endpoints';

import 'plugins/theme/components/EditPanel/EditPanel.scss';

interface IQueryParams {
  page: number;
  limit: number;
  search_keyword?: string;
  [key: string]: any;
}

// typ danych wejściowych
interface IProps {
  filter: IDynamicFilter;
  queryParams: IQueryParams;
  setQueryParams: Dispatch<SetStateAction<IQueryParams>>;
  pageSymbol: string;
  componentSymbol: string;
}

const FilterSelect: FC<IProps> = ({
  filter,
  queryParams,
  setQueryParams,
  pageSymbol,
  componentSymbol
}) => {
  const dispatch = useDispatch();

  const { data: selectDataOptions, refetch: refetchSelectDataOptions } = useLoadData(
    filter.dictionary?.values_get_u_i_api || '',
    { page: 1, limit: 999 },
    { enabled: false }
  );

  useEffect(() => {
    if (filter.dictionary?.values_get_u_i_api) {
      refetchSelectDataOptions();
    }
  }, [filter.dictionary?.values_get_u_i_api]);

  return (
    <div className="filter">
      <Select
        placeholder={filter.label}
        value={queryParams[filter.field_symbol]}
        options={
          selectDataOptions?.items.map((value) => ({
            value: value.value,
            label: value.text,
            item: value
          })) ||
          filter.dictionary?.values?.map((value) => ({
            value: value.value,
            label: value.text,
            item: value
          })) ||
          []
        }
        clearable
        onChange={(value) => {
          setQueryParams((prevState) => {
            const newQueryParams = {
              ...prevState,
              [filter.field_symbol as string]: value?.value,
              page: 1
            };

            dispatch(
              reduxActions.setContextFilters({
                [pageSymbol]: { [componentSymbol]: newQueryParams }
              })
            );

            return newQueryParams;
          });
        }}
      />
    </div>
  );
};

export default FilterSelect;
