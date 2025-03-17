import React, { FC, useState, useEffect, ChangeEvent } from 'react';
import { useLocation } from 'react-router-dom';
import { Trans, useTranslation } from 'react-i18next';
import { Book } from 'react-bootstrap-icons';
import orderBy from 'lodash/orderBy';

import { reduxActions, useDispatch } from 'store';
import { ILanguageListItem } from 'api/types';
import { IDynamicUiField, ITranslation } from 'plugins/api/types';

import { FormElement, Modal, Button } from 'components/controls';

// typ danych wejściowych
interface IProps {
  field: IDynamicUiField;
  languagesData?: ILanguageListItem[];
  pageSymbol: string;
  componentSymbol: string;
}

const DynamicTranslation: FC<IProps> = ({ field, languagesData, pageSymbol, componentSymbol }) => {
  const dispatch = useDispatch();
  const { t } = useTranslation();
  const { pathname } = useLocation();

  const getPosition = (string: string, subString: string, index: number) => {
    return string.split(subString, index).join(subString).length;
  };

  const urlPrefix = pathname.slice(0, getPosition(pathname, '/', 2));

  // ustawianie wartości
  const [value, setValue] = useState(field.value);

  const [open, setOpen] = useState(false);

  //ustawianie wartości początkowych
  useEffect(() => {
    if (field.value && pageSymbol && componentSymbol) {
      dispatch(
        reduxActions.setFieldValue({
          pageSymbol,
          componentSymbol,
          fieldName: field.field_symbol,
          fieldValue: field.value
        })
      );
    }
  }, [field, pageSymbol, componentSymbol]);

  // ustawienie value przy zmianie danych
  useEffect(() => {
    if (field.value && !!field.value.length) {
      setValue(field.value);

      return;
    }

    if (languagesData) {
      const languages = languagesData.map((language) => ({
        language: language.id,
        translation: ''
      }));
      setValue(languages as any);
    }
  }, [field.value, languagesData]);

  const onChangeHandler = (e: ChangeEvent<HTMLInputElement>, language: string) => {
    if (value && Array.isArray(value)) {
      const newValue: ITranslation[] = value.map((o) => {
        if (o.language === language) {
          return {
            ...o,
            translation: e.target.value
          };
        }

        return o;
      });

      dispatch(
        reduxActions.setFieldValue({
          pageSymbol,
          componentSymbol,
          fieldName: field.field_symbol,
          fieldValue: newValue
        })
      );

      setValue(newValue as any);
    }
  };

  const renderContent = () => {
    return (
      value &&
      Array.isArray(value) &&
      value.map((o, i: number) => (
        <div key={i} className="dynamicText">
          <FormElement>
            <div>
              <label>
                <small>{o.language}</small>
              </label>
              <div>
                <input value={o.translation} onChange={(e) => onChangeHandler(e, o.language)} />
              </div>
            </div>
          </FormElement>
        </div>
      ))
    );
  };

  const fieldToDisplay = Array.isArray(value)
    ? orderBy(value, (a) => a.language !== urlPrefix.replace('/', ''))
    : [];

  return (
    <div className="dynamicText">
      <FormElement>
        <label>{field.label}</label>
        {fieldToDisplay.length && (
          <div>
            <label>
              <small>{fieldToDisplay[0].language}</small>
            </label>
            <div className="inputWrapper">
              <input
                value={fieldToDisplay[0].translation}
                onChange={(e) => onChangeHandler(e, fieldToDisplay[0].language)}
              />
              <Button ghost color="gray" onClick={() => setOpen(true)}>
                <Book size="18px" />
              </Button>
            </div>
          </div>
        )}
        {field.hint && <small>{field.hint}</small>}
      </FormElement>
      {open && (
        <Modal onClose={() => setOpen(false)} title={t('Słownik')}>
          {renderContent()}
          <div className="actionWrapper">
            <Button onClick={() => setOpen(false)}>
              <Trans>Zamknij</Trans>
            </Button>
          </div>
        </Modal>
      )}
    </div>
  );
};

export default DynamicTranslation;
