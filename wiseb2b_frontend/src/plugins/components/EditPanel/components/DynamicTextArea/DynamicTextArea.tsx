import React, { FC, useState, useEffect, ChangeEvent } from 'react';
import TextareaAutosize from '@mui/material/TextareaAutosize';

import { reduxActions, useDispatch } from 'store';
import { IDynamicUiField } from 'plugins/api/types';

import { FormElement } from 'components/controls';

// typ danych wejściowych
interface IProps {
  field: IDynamicUiField;
  pageSymbol: string;
  componentSymbol: string;
}

const DynamicTextArea: FC<IProps> = ({ field, pageSymbol, componentSymbol }) => {
  const dispatch = useDispatch();

  // ustawianie wartości
  const [value, setValue] = useState<string>((field.value as string) || '');

  // ustawienie value przy zmianie danych
  useEffect(() => {
    setValue(field.value as string);
  }, [field.value]);

  const onChangeHandler = (e: ChangeEvent<HTMLTextAreaElement>) => {
    dispatch(
      reduxActions.setFieldValue({
        pageSymbol,
        componentSymbol,
        fieldName: field.field_symbol,
        fieldValue: e.target.value
      })
    );

    setValue(e.target.value);
  };

  return (
    <div className="dynamicTextArea">
      <FormElement>
        <label>{field.label}</label>
        <TextareaAutosize
          // value={JSON.stringify(JSON.parse(value), undefined, 4)}
          value={value}
          onChange={(e) => onChangeHandler(e)}
          disabled={!field.editable}
        />
        {field.hint && <small>{field.hint}</small>}
      </FormElement>
    </div>
  );
};

export default DynamicTextArea;
