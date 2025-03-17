import React, { FC, useState, useEffect, ChangeEvent } from 'react';
import { Trans, useTranslation } from 'react-i18next';
import { Book } from 'react-bootstrap-icons';

import { ITranslatedTitle, ICmsArticleCountryListItem } from 'api/types';
import { FormElement, Modal, Button } from 'components/controls';

import styles from 'theme/components/controls/TranslatedInput/TranslatedInput.module.scss';

// typ danych wejściowych
interface IProps {
  name: string;
  selectedCountry: string;
  value: ITranslatedTitle[] | null;
  setFieldValue: (arg1: string, arg2: ITranslatedTitle[]) => void;
  countries: ICmsArticleCountryListItem[];
}

const TranslatedInput: FC<IProps> = ({
  name,
  value,
  selectedCountry,
  setFieldValue,
  countries
}) => {
  const { t } = useTranslation();

  const [formValues, setFormValues] = useState(value);
  const [open, setOpen] = useState(false);

  useEffect(() => {
    if (!value || !value.length) {
      const newValues = countries.map((country) => ({
        language: country.code,
        translation: ''
      }));

      setFormValues(newValues);
    }
  }, [value, countries]);

  const onChangeHandler = (e: ChangeEvent<HTMLInputElement>, language: string) => {
    if (formValues && Array.isArray(formValues)) {
      const newValue: ITranslatedTitle[] = formValues.map((o) => {
        if (o.language === language) {
          return {
            ...o,
            translation: e.target.value
          };
        }

        return o;
      });

      setFormValues(newValue);
    }
  };

  const handleSave = () => {
    setOpen(false);
    formValues && setFieldValue(name, formValues);
  };

  const renderContent = () => {
    return (
      formValues &&
      Array.isArray(formValues) &&
      formValues.map((o, i: number) => (
        <div key={i} className={styles.componentWrapper}>
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

  return (
    <div className={styles.componentWrapper}>
      <FormElement>
        <div className={styles.inputWrapper}>
          <input
            disabled
            value={formValues?.find((o) => o.language === selectedCountry)?.translation}
          />
          <Button ghost color="gray" onClick={() => setOpen(true)}>
            <Book size="18px" />
          </Button>
        </div>
      </FormElement>
      {open && (
        <Modal onClose={() => setOpen(false)} title={t('Słownik')}>
          {renderContent()}
          <div className={styles.actionWrapper}>
            <Button onClick={() => handleSave()}>
              <Trans>Zapisz</Trans>
            </Button>
          </div>
        </Modal>
      )}
    </div>
  );
};

export default TranslatedInput;
