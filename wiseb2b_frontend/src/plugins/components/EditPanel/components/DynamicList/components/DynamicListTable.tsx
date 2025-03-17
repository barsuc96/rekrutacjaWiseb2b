import React, { FC, useEffect, useMemo, Dispatch, SetStateAction } from 'react';
import { useTranslation, Trans } from 'react-i18next';

import { IDynamicUiField } from 'plugins/api/types';
import { useLoadData } from 'plugins/api/endpoints';

import { SearchInput, Button, Loader } from 'components/controls';
import Table, { IColumn } from 'components/controls/Table';

import 'plugins/theme/components/EditPanel/EditPanel.scss';

interface IQueryParams {
  page: number;
  limit: number;
  searchKeyword: string;
}

// typ danych wejściowych
interface IProps {
  field: IDynamicUiField;
  setIsOpen: Dispatch<SetStateAction<boolean>>;
  queryParams: IQueryParams;
  setQueryParams: Dispatch<SetStateAction<IQueryParams>>;
  onClose: () => void;
  onChangeHandler: (arg: any) => void;
}

const DynamicListTable: FC<IProps> = ({
  field,
  queryParams,
  setQueryParams,
  onClose,
  onChangeHandler
}) => {
  const { t } = useTranslation();

  const {
    data: dictionaryFromUIData,
    refetch: refetchDictionaryFromUI,
    isLoading: isDataLoading
  } = useLoadData(field.dictionary.values_get_u_i_api || '', queryParams, {
    enabled: false,
    keepPreviousData: true
  });

  useEffect(() => {
    if (field.dictionary.values_get_u_i_api) {
      refetchDictionaryFromUI();
    }
  }, [field.dictionary.values_get_u_i_api, queryParams]);

  const columns: IColumn<any>[] = useMemo(
    () => [
      {
        title: <Trans>Nazwa</Trans>,
        align: 'left',
        renderCell: (item) => <button onClick={() => onChangeHandler(item)}>{item.text}</button>
      }
    ],
    [dictionaryFromUIData?.items]
  );

  return (
    <div>
      <div className="dynamicListSearchWrapper">
        <SearchInput
          placeholder={`${t('Szukaj')}...`}
          value={queryParams.searchKeyword}
          onChange={(value) => setQueryParams(() => ({ searchKeyword: value, page: 1, limit: 10 }))}
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
            dataSource={dictionaryFromUIData?.items || []}
            rowKey="id"
            pagination={{
              page: queryParams.page || 1,
              pagesCount: dictionaryFromUIData?.total_pages || 1,
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
  );
};

export default DynamicListTable;
