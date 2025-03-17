import React, { FC, useState, useEffect, MouseEvent } from 'react';
import { useTranslation } from 'react-i18next';

import { IDynamicUiField } from 'plugins/api/types';

import { reduxActions, useDispatch } from 'store';
import { FormElement, Modal } from 'components/controls';
import DynamicListTable from './components/DynamicListTable';
import { useLoadData } from 'plugins/api/endpoints';

import { CloseIcon, ChevronDownIcon } from 'assets/icons';

import 'plugins/theme/components/EditPanel/EditPanel.scss';

// typ danych wejściowych
interface IProps {
  field: IDynamicUiField;
  pageSymbol: string;
  componentSymbol: string;
}

const DynamicList: FC<IProps> = ({ field, pageSymbol, componentSymbol }) => {
  const dispatch = useDispatch();
  const { t } = useTranslation();

  // ustawianie wartości
  const [value, setValue] = useState<string>('');

  const [isOpen, setIsOpen] = useState(false);

  // parametry zapytania
  const [queryParams, setQueryParams] = useState({
    page: 1,
    limit: 10,
    searchKeyword: ''
  });

  const onClose = () => {
    setIsOpen(false);

    setQueryParams({
      page: 1,
      limit: 10,
      searchKeyword: ''
    });
  };

  const { data: dictionaryFromUIData, refetch: loadDictionaryFromUIData } = useLoadData(
    field.dictionary.values_get_u_i_api || '',
    {
      value: field.value
    },
    { enabled: false }
  );

  const onChangeHandler = (item: any) => {
    dispatch(
      reduxActions.setFieldValue({
        pageSymbol,
        componentSymbol,
        fieldName: field.field_symbol,
        fieldValue: item.value
      })
    );

    onClose();
    setValue(item.text);
  };

  const handleClear = (e: MouseEvent<HTMLSpanElement>) => {
    e.stopPropagation();

    onChangeHandler({ text: '', value: null });
  };

  const handleEscape = (e: KeyboardEvent) => {
    if (e.key === 'Escape') {
      setIsOpen(false);
    }
  };

  useEffect(() => {
    if (isOpen) {
      document.addEventListener('keyup', handleEscape);

      return;
    } else {
      document.removeEventListener('keyup', handleEscape);
    }

    return () => window.removeEventListener('keyup', handleEscape);
  }, [isOpen]);

  useEffect(() => {
    if (field.value) {
      loadDictionaryFromUIData();
    }
  }, [field.value]);

  useEffect(() => {
    if (dictionaryFromUIData?.items?.[0]?.text) {
      setValue(dictionaryFromUIData.items[0].text);
    }
  }, [dictionaryFromUIData?.items]);

  return (
    <div className="dynamicList">
      <FormElement>
        <label>{field.label}</label>
        <div onClick={() => setIsOpen(true)}>
          {value || `${t('Wybierz')}...`}
          {value && field.nullable && (
            <span onClick={(e) => handleClear(e)}>
              <CloseIcon />
            </span>
          )}

          <ChevronDownIcon />
        </div>
        {field.hint && <small>{field.hint}</small>}
      </FormElement>

      {isOpen && (
        <Modal title="" onClose={() => onClose()}>
          <DynamicListTable
            field={field}
            setIsOpen={setIsOpen}
            queryParams={queryParams}
            setQueryParams={setQueryParams}
            onClose={onClose}
            onChangeHandler={onChangeHandler}
          />
        </Modal>
      )}
    </div>
  );
};

export default DynamicList;
