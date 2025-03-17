import React, { FC, useState, useEffect } from 'react';
import { useTranslation } from 'react-i18next';

import { reduxActions, useDispatch } from 'store';
import { IDynamicUiField, IDictionaryValue } from 'plugins/api/types';
import { Select, FormElement } from 'components/controls';

// typ danych wejściowych
interface IProps {
  field: IDynamicUiField;
  pageSymbol: string;
  componentSymbol: string;
}

const DynamicSelect: FC<IProps> = ({ field, pageSymbol, componentSymbol }) => {
  const dispatch = useDispatch();
  const { t } = useTranslation();

  // ustawianie wartości
  const [value, setValue] = useState<string | undefined>(field.value as string);

  // ustawienie początkowej wartości po wczytaniu danych
  useEffect(() => {
    setValue(field.value as string);
  }, [field.value]);

  const onChangeHandler = (item: IDictionaryValue) => {
    const multiChoiceValue =
      field.dictionary.multi_choice &&
      Array.isArray(item) &&
      item
        ?.map((item) => item.item.value)
        .toString()
        .replaceAll(',', field.dictionary.separator_multi_choice || '|');

    dispatch(
      reduxActions.setFieldValue({
        pageSymbol,
        componentSymbol,
        fieldName: field.field_symbol,
        fieldValue: multiChoiceValue || item?.value
      })
    );

    setValue(multiChoiceValue || item.value?.toString());
  };

  const options = field.dictionary?.values?.map((value) => ({
    value: value.value?.toString() || '',
    label: value.text,
    item: value
  }));

  return (
    <div className="dynamicSelect">
      <FormElement>
        <label>{field.label}</label>
        <Select
          value={value?.toString()}
          options={[
            ...(field.nullable
              ? [
                  {
                    value: '',
                    label: `${t('Wybierz')}`,
                    item: { value: null, text: `${t('Wybierz')}` }
                  }
                ]
              : []),
            ...options
          ]}
          onChange={(item) => item && onChangeHandler(item)}
          isMulti={field.dictionary?.multi_choice}
          clearable={field.dictionary?.multi_choice}
          separatorMultiChoice={field.dictionary?.separator_multi_choice}
          disabled={!field.editable}
        />
        {field.hint && <small>{field.hint}</small>}
      </FormElement>
    </div>
  );
};

export default DynamicSelect;
