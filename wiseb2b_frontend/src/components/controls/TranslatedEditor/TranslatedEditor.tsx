import React, { FC, useState, useEffect } from 'react';
import classnames from 'classnames';
import { Trans, useTranslation } from 'react-i18next';
import { Book } from 'react-bootstrap-icons';

import { ITranslatedArticleField, ICmsArticleCountryListItem } from 'api/types';
import { FormElement, Modal, Button, Editor } from 'components/controls';

import styles from 'theme/components/controls/TranslatedInput/TranslatedInput.module.scss';

// typ danych wejściowych
interface IProps {
  name: string;
  selectedCountry: string;
  value: ITranslatedArticleField[];
  setFieldValue: (arg1: string, arg2: ITranslatedArticleField[]) => void;
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

      // ustawianie aktywnego tabu
      setActiveTab(newValues[0].language);
    }
  }, [value, countries]);

  // lista tabów
  const tabs =
    formValues?.map((o) => ({
      key: o.language,
      label: o.language
    })) || [];

  // rodzaj aktywnej listy
  const [activeTab, setActiveTab] = useState<string>(tabs?.[0]?.key || '');

  const handleTabChange = (tab: string) => {
    // ustawienie delay, żeby odmontować i zamontować komponenty
    setTimeout(() => {
      setActiveTab(tab);
    }, 10);
  };

  const onChangeHandler = (value: string, language: string) => {
    if (formValues && Array.isArray(formValues)) {
      const newValue: ITranslatedArticleField[] = formValues.map((o) => {
        if (o.language === language) {
          return {
            ...o,
            translation: value
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

  const renderActiveComponent = () => {
    const selectedValue = formValues?.find((o) => o.language === activeTab);
    return (
      <Editor
        value={selectedValue?.translation || ''}
        onChange={(value: string) => onChangeHandler(value, selectedValue?.language || '')}
      />
    );
  };

  const renderContent = () => {
    return (
      <div>
        <div className="tabs">
          {tabs.map((tab) => (
            <div
              key={tab.key}
              className={classnames('tab', {
                active: tab.key === activeTab
              })}
              onClick={() => (setActiveTab(''), handleTabChange(tab.key))}>
              {tab.label}
            </div>
          ))}
        </div>
        <div className="contentWrapper">{renderActiveComponent()}</div>
      </div>
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
        <Modal fullWidth onClose={() => setOpen(false)} title={t('Słownik')}>
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
