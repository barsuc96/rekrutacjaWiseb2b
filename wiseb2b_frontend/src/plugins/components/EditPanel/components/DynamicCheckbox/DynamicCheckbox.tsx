import React, { FC, useState, useEffect } from 'react';

import { reduxActions, useDispatch } from 'store';
import { IDynamicUiField } from 'plugins/api/types';

import { FormElement, Checkbox } from 'components/controls';

// typ danych wejściowych
interface IProps {
  field: IDynamicUiField;
  pageSymbol: string;
  componentSymbol: string;
}

const DynamicCheckbox: FC<IProps> = ({ field, pageSymbol, componentSymbol }) => {
  const dispatch = useDispatch();

  // ustawianie wartości
  const [value, setValue] = useState<boolean>(!!field.value);

  // ustawienie value przy zmianie danych
  useEffect(() => {
    setValue(!!field.value);
  }, [field.value]);

  const onChangeHandler = () => {
    dispatch(
      reduxActions.setFieldValue({
        pageSymbol,
        componentSymbol,
        fieldName: field.field_symbol,
        fieldValue: !value
      })
    );

    setValue(!value);
  };

  return (
    <div className="dynamicCheckbox">
      <FormElement>
        <label>{field.label}</label>
        <Checkbox checked={value} onClick={() => onChangeHandler()} disabled={!field.editable} />
      </FormElement>
    </div>
  );
};

export default DynamicCheckbox;
