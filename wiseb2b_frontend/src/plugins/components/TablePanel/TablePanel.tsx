/* eslint-disable @typescript-eslint/no-explicit-any */
import React, { FC, useState, useEffect, useMemo } from 'react';
import { Trans } from 'react-i18next';
import { useLocation } from 'react-router-dom';
import { Check2, X } from 'react-bootstrap-icons';
import classnames from 'classnames';
import each from 'lodash/each';
import filter from 'lodash/filter';
import qs from 'query-string';

import { reduxActions, useDispatch, useSelector } from 'store';
import {
  IDynamicFilter,
  IDynamicColumn,
  IDynamicAction,
  ILoadDataParam,
  IComponentParams
} from 'plugins/api/types';
import { useLoadData } from 'plugins/api/endpoints';
import { loadDataUrlParser } from 'plugins/util/parser';

import Table from 'components/controls/Table';
import { PageTitle, SearchInput } from 'components/controls';
import { AdditionalAction } from './components/actions/AdditionalAction';
import { NoContextAction } from './components/actions/NoContextAction';
import { MainAction } from './components/actions/MainAction';
import { ColumnAction } from './components/actions/ColumnAction';
import { FilterSelect } from './components/filters/FilterSelect';
import { FilterDate } from './components/filters/FilterDate';
import { FilterList } from './components/filters/FilterList';

import styles from 'theme/pages/Clients/Clients.module.scss';
import 'plugins/theme/components/TablePanel/TablePanel.scss';

// typ danych wejściowych
interface IProps {
  filters: IDynamicFilter[];
  actions: IDynamicAction[];
  columns: IDynamicColumn[];
  loadDataParams?: ILoadDataParam;
  componentParams?: IComponentParams;
  title?: string;
  pageSymbol: string;
  componentSymbol: string;
  requestParams?: object;
}

interface IQueryParams {
  page: number;
  limit: number;
  search_keyword?: string;
  [key: string]: any;
}

const TablePanel: FC<IProps> = ({
  filters,
  actions,
  title,
  columns,
  loadDataParams,
  componentParams,
  pageSymbol,
  componentSymbol,
  requestParams
}) => {
  const dispatch = useDispatch();
  const { search } = useLocation();

  // pobieranie globalnego stanu kontekstu danych
  const { contextFilters } = useSelector((state) => state.dynamicPage);

  // parametry zapytania o listę klientów
  const [queryParams, setQueryParams] = useState<IQueryParams>({
    page: 1,
    limit: 20,
    search_keyword: ''
  });

  const loadDataUrl = loadDataUrlParser(loadDataParams?.get_url || '', {
    ...requestParams,
    ...qs.parse(search)
  });

  // pobranie danych tabeli
  const { data: tableData, refetch: refetchTableData } = useLoadData(loadDataUrl, queryParams, {
    onSuccess: (data) => {
      dispatch(reduxActions.setContextData({ [pageSymbol]: { [componentSymbol]: data } }));
    },
    enabled: false
  });

  // aktualizacja danych
  useEffect(() => {
    refetchTableData();
  }, [loadDataUrl, queryParams]);

  // pobieranie danych z kontekstu i zapisywanie w lokalnych filtrach
  useEffect(() => {
    const newQueryParams = contextFilters?.[pageSymbol]?.[componentSymbol];
    if (newQueryParams) {
      setQueryParams(newQueryParams);

      return;
    }

    // ustawienie wartości początkowych
    setQueryParams({
      page: 1,
      limit: 20,
      search_keyword: ''
    });
  }, [contextFilters, pageSymbol, componentSymbol]);

  const renderFilters = () =>
    filters.map((filter) => {
      if (filter.type === 'search') {
        return (
          <div className="filter">
            <SearchInput
              placeholder={filter.label}
              onChange={(value) =>
                setQueryParams((prevState) => {
                  const newQueryParams = {
                    ...prevState,
                    [filter.field_symbol || 'search_keyword']: value,
                    page: 1
                  };

                  dispatch(
                    reduxActions.setContextFilters({
                      [pageSymbol]: { [componentSymbol]: newQueryParams }
                    })
                  );

                  return newQueryParams;
                })
              }
              value={queryParams[filter.field_symbol || 'search_keyword']}
            />
          </div>
        );
      }

      if (filter.type === 'select') {
        return (
          <FilterSelect
            pageSymbol={pageSymbol}
            componentSymbol={componentSymbol}
            filter={filter}
            setQueryParams={setQueryParams}
            queryParams={queryParams}
          />
        );
      }

      if (filter.type === 'datetime' || filter.type === 'date') {
        return (
          <FilterDate
            pageSymbol={pageSymbol}
            componentSymbol={componentSymbol}
            filter={filter}
            setQueryParams={setQueryParams}
            queryParams={queryParams}
            isTimePicker={filter.type === 'datetime'}
          />
        );
      }

      if (filter.type === 'list') {
        return (
          <FilterList
            pageSymbol={pageSymbol}
            componentSymbol={componentSymbol}
            filter={filter}
            setQueryParams={setQueryParams}
            queryParams={queryParams}
          />
        );
      }

      return null;
    });

  const tableColumns = useMemo(() => {
    const columnsToDisplay = columns.map((column) => ({
      title: column.label,
      align: column.align,
      renderCell: (item: any) => renderCell(item, column)
    }));

    const actionsToDisplay = {
      title: <Trans>Akcje</Trans>,
      renderCell: (item: any) => renderActionsCell(item)
    };

    return [...columnsToDisplay, actionsToDisplay];
  }, [columns, actions]);

  // komórki akcji
  const renderActionsCell = (item: any) => {
    const mainActions: IDynamicAction[] = [];
    const additionalActions: IDynamicAction[] = [];

    each(actions, (action) => {
      if (action.type === 'CONTEXT_ADDITIONAL') {
        additionalActions.push(action);
      }

      if (action.type === 'CONTEXT_MAIN') {
        mainActions.push(action);
      }
    });

    return (
      <div style={{ display: 'flex' }}>
        {renderMainActionsCell(item, mainActions)}
        {!!additionalActions.length && renderAdditionalActionsCell(item, additionalActions)}
      </div>
    );
  };

  const renderMainActionsCell = (item: any, mainActions: IDynamicAction[]) => {
    return mainActions.map((action, i) => (
      <MainAction key={i} action={action} refetch={refetchTableData} item={item} />
    ));
  };

  const renderAdditionalActionsCell = (item: any, additionalActions: IDynamicAction[]) => {
    return (
      <AdditionalAction
        additionalActions={additionalActions}
        item={item}
        refetchTableData={refetchTableData}
        requestParams={requestParams}
      />
    );
  };

  // komórki tabel
  const renderCell = (item: any, column: IDynamicColumn) => {
    if (column.type === 'text') {
      return renderTextCell(item, column);
    }

    if (column.type === 'address') {
      return `${item.address?.street}, ${item.address?.postal_code} ${item.address?.city}`;
    }

    if (column.type === 'boolean') {
      return item?.[column.field_symbol] ? <Check2 /> : <X />;
    }

    return null;
  };

  const renderTextCell = (item: any, column: IDynamicColumn) => {
    const columnAction = actions.find(
      (action) => action.type === 'COLUMN' && action.column_field_symbol === column.field_symbol
    );

    if (columnAction?.column_field_symbol === column.field_symbol) {
      return <ColumnAction action={columnAction} item={item} refetch={refetchTableData} />;
    }

    return <span>{item?.[column.field_symbol]}</span>;
  };

  const renderNoContextActions = () => {
    const noContextActions = filter(actions, (action) => action.type === 'NO_CONTEXT');

    return noContextActions.map((action, i) => (
      <NoContextAction
        key={i}
        action={action}
        refetch={refetchTableData}
        componentParams={componentParams}
      />
    ));
  };

  return (
    <div className={classnames(styles.componentWrapper, 'TablePanel', 'StylePath-TablePanel')}>
      {title && (
        <PageTitle
          title={
            <>
              {title} <span className="thin">({tableData?.total_count})</span>
            </>
          }
        />
      )}

      <div className={styles.filtersWrapper}>
        <div className="filterWrapper">
          {renderFilters()}{' '}
          {!!filters.length && (
            <button
              className="clearFilters"
              color="secondary"
              onClick={() => {
                dispatch(
                  reduxActions.setContextFilters({
                    [pageSymbol]: { [componentSymbol]: { page: 1, limit: 20 } }
                  })
                );
                setQueryParams({ page: 1, limit: 20 });
              }}>
              <Trans>Wyczyść filtry</Trans>
            </button>
          )}
        </div>
        {renderNoContextActions()}
      </div>

      <div className={styles.tableWrapper}>
        <Table
          columns={tableColumns}
          dataSource={tableData?.items || []}
          rowKey="field_symbol"
          pagination={{
            page: queryParams.page,
            pagesCount: tableData?.total_pages || 1,
            onChange: (page) =>
              setQueryParams((prevState) => {
                const newQueryParams = { ...prevState, page };

                dispatch(
                  reduxActions.setContextFilters({
                    [pageSymbol]: { [componentSymbol]: newQueryParams }
                  })
                );

                return newQueryParams;
              })
          }}
        />
      </div>
    </div>
  );
};

export default TablePanel;
