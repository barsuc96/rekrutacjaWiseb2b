import React, {
  FC,
  useEffect,
  useMemo,
  Dispatch,
  SetStateAction,
  useState,
  MouseEvent
} from 'react';
import { useTranslation, Trans } from 'react-i18next';

import { reduxActions, useDispatch } from 'store';

import { Modal, SearchInput, Button, Loader } from 'components/controls';
import Table, { IColumn } from 'components/controls/Table';

import { IDynamicFilter } from 'plugins/api/types';
import { useLoadData } from 'plugins/api/endpoints';

import { CloseIcon, ChevronDownIcon } from 'assets/icons';

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

const FilterList: FC<IProps> = ({
  filter,
  queryParams,
  setQueryParams,
  pageSymbol,
  componentSymbol
}) => {
  const dispatch = useDispatch();
  const { t } = useTranslation();

  const [isOpen, setIsOpen] = useState(false);

  const [loadDataQueryParams, setLoadDataQueryParams] = useState({
    page: 1,
    limit: 10,
    searchKeyword: ''
  });

  const {
    data: listDataOptions,
    refetch: refetchSelectDataOptions,
    isLoading: isDataLoading
  } = useLoadData(filter.dictionary?.values_get_u_i_api || '', loadDataQueryParams, {
    enabled: false
  });

  useEffect(() => {
    if (filter.dictionary?.values_get_u_i_api) {
      refetchSelectDataOptions();
    }
  }, [filter.dictionary?.values_get_u_i_api]);

  const onClose = () => {
    setIsOpen(false);

    setLoadDataQueryParams({
      page: 1,
      limit: 10,
      searchKeyword: ''
    });
  };

  const onChangeHandler = (value: any) => {
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

      setIsOpen(false);

      return newQueryParams;
    });
  };

  const handleClear = (e: MouseEvent<HTMLSpanElement>) => {
    e.stopPropagation();

    onChangeHandler({ text: '', value: null });
  };

  const columns: IColumn<any>[] = useMemo(
    () => [
      {
        title: <Trans>Nazwa</Trans>,
        align: 'left',
        renderCell: (item) => <button onClick={() => onChangeHandler(item)}>{item.text}</button>
      }
    ],
    [listDataOptions?.items]
  );

  return (
    <div className="filter">
      <div className="dynamicList">
        <div>
          <div onClick={() => setIsOpen(true)}>
            {listDataOptions?.items.find((item) => item.value === queryParams[filter.field_symbol])
              ?.text || filter.label}
            {queryParams[filter.field_symbol] && (
              <span onClick={(e) => handleClear(e)}>
                <CloseIcon />
              </span>
            )}
            <ChevronDownIcon />
          </div>
        </div>
      </div>

      {isOpen && (
        <Modal title="" onClose={() => onClose()}>
          <div>
            <div className="dynamicListSearchWrapper">
              <SearchInput
                placeholder={`${t('Szukaj')}...`}
                value={queryParams.searchKeyword}
                onChange={(value) =>
                  setQueryParams(() => ({ searchKeyword: value, page: 1, limit: 10 }))
                }
              />
              {!!queryParams.searchKeyword && (
                <button
                  className="clearFilters"
                  color="secondary"
                  onClick={() => setQueryParams(() => ({ searchKeyword: '', page: 1, limit: 10 }))}>
                  <Trans>Wyczyść filtry</Trans>
                </button>
              )}
            </div>
            <div className="dynamicListTableWrapper">
              {isDataLoading ? (
                <Loader />
              ) : (
                <Table
                  columns={columns}
                  dataSource={listDataOptions?.items || []}
                  rowKey="id"
                  pagination={{
                    page: queryParams.page || 1,
                    pagesCount: listDataOptions?.total_pages || 1,
                    onChange: (page) => setQueryParams((prevState) => ({ ...prevState, page }))
                  }}
                />
              )}
            </div>

            <div className="confirmationModalActions">
              <Button color="secondary" ghost onClick={() => onClose()}>
                <Trans>Anuluj</Trans>
              </Button>
            </div>
          </div>
        </Modal>
      )}
    </div>
  );
};

export default FilterList;
