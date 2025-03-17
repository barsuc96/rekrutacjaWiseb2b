import React, { FC, useState } from 'react';
import Dropzone from 'react-dropzone';
import { Plus, X } from 'react-bootstrap-icons';
import { Trans, useTranslation } from 'react-i18next';

import { reduxActions, useDispatch } from 'store';
import { IDynamicUiField } from 'plugins/api/types';

import 'plugins/theme/components/EditPanel/EditPanel.scss';

// typ danych wejściowych
interface IProps {
  field: IDynamicUiField;
  pageSymbol: string;
  componentSymbol: string;
}

const DynamicFile: FC<IProps> = ({ field, pageSymbol, componentSymbol }) => {
  const dispatch = useDispatch();
  const { t } = useTranslation();

  const [value, setValue] = useState<string>((field.value as string) || '');

  const handleDropzone = async (file: File[]) => {
    const reader = new FileReader();
    reader.readAsDataURL(file[0]);
    reader.onload = function () {
      dispatch(
        reduxActions.setFieldValue({
          pageSymbol,
          componentSymbol,
          fieldName: field.field_symbol,
          fieldValue: reader.result
        })
      );

      setValue(reader.result as string);
    };
  };

  const handleRemove = () => {
    dispatch(
      reduxActions.setFieldValue({
        pageSymbol,
        componentSymbol,
        fieldName: field.field_symbol,
        fieldValue: ''
      })
    );

    setValue('');
  };

  return (
    <div className="dynamicFile">
      {value ? (
        <div className="fileWrapper">
          <div>
            <img src={value} />
            <button title={t('Usuń')} onClick={handleRemove}>
              <X size={24} />
            </button>
          </div>
        </div>
      ) : (
        <Dropzone onDrop={(acceptedFiles) => handleDropzone(acceptedFiles)}>
          {({ getRootProps, getInputProps }) => (
            <section className="dropzone">
              <div {...getRootProps()}>
                <input {...getInputProps()} />
                <p>
                  <Trans>Dodaj plik</Trans>
                  <Plus size={24} />
                </p>
              </div>
            </section>
          )}
        </Dropzone>
      )}
      {field.hint && <small>{field.hint}</small>}
    </div>
  );
};

export default DynamicFile;
