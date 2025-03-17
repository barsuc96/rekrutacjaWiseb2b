/* eslint-disable @typescript-eslint/no-unused-vars */
/* eslint-disable @typescript-eslint/no-explicit-any */
import React, { FC, useEffect, Dispatch, SetStateAction } from 'react';

import { DatePicker } from 'components/controls';

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
  isTimePicker?: boolean;
}

const FilterDate: FC<IProps> = ({
  filter,
  queryParams,
  setQueryParams,
  pageSymbol,
  componentSymbol,
  isTimePicker
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

  useEffect(() => {
    if (filter.default_value) {
      setQueryParams((prevState) => {
        const newQueryParams = {
          ...prevState,
          [filter.field_symbol as string]: filter.default_value
        };

        dispatch(
          reduxActions.setContextFilters({
            [pageSymbol]: { [componentSymbol]: newQueryParams }
          })
        );

        return newQueryParams;
      });
    }
  }, [filter.default_value]);

  return (
    <div className="filter">
      <DatePicker
        date={queryParams[filter.field_symbol]}
        onChange={(value) => {
          setQueryParams((prevState) => {
            const newQueryParams = {
              ...prevState,
              [filter.field_symbol as string]: value,
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
        clearable={!!queryParams[filter.field_symbol]}
        isTimePicker={isTimePicker}
      />
    </div>
  );
};

export default FilterDate;
