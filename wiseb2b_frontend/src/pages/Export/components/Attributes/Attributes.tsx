// wybieranie atrybutów eksportu

import React, { FC, useMemo, useState } from 'react';
import { sortBy } from 'lodash';
import classnames from 'classnames';
import { useTranslation } from 'react-i18next';

import { useGetExportAttributes } from 'api';
import { IExportAttributeListItem } from 'api/types';

import styles from 'theme/pages/Export/components/Attributes/Attributes.module.scss';

// typ danych wejściowych
interface IProps {
  onChange: (attributes: IExportAttributeListItem[]) => void;
  chosenFields: number[];
  error?: string;
}

const Attributes: FC<IProps> = ({ onChange, chosenFields, error }) => {
  const { t } = useTranslation();

  // pobranie listy atrybutów
  const { data: attributesData } = useGetExportAttributes({
    page: 1,
    limit: 999
  });

  // lista zaznaczonych atrybutów
  const [selectedAttributes, setSelectedAttributes] = useState<string[]>([]);

  const attributesToChoose = useMemo(
    () => attributesData?.items.filter((item) => !chosenFields.includes(item.id)) || [],
    [chosenFields, attributesData]
  );

  const attributesSelected = useMemo(
    () => attributesData?.items.filter((item) => chosenFields.includes(item.id)) || [],
    [chosenFields, attributesData]
  );

  const toggleItem = (item: IExportAttributeListItem) =>
    setSelectedAttributes((prevState) =>
      prevState.includes(item.field_name)
        ? prevState.filter((field) => field !== item.field_name)
        : [...prevState, item.field_name]
    );

  const renderList = (title: string, items: IExportAttributeListItem[]) => (
    <div className={styles.list}>
      <div className={styles.title}>{title}</div>
      {items.map((item) => (
        <div
          key={item.field_name}
          className={classnames(styles.item, {
            [styles.selected]: selectedAttributes.includes(item.field_name)
          })}
          onClick={() => toggleItem(item)}>
          {item.name}
        </div>
      ))}
    </div>
  );

  return (
    <>
      <div
        className={classnames(
          styles.wrapperComponent,
          'StylePath-Pages-Export-components-Attributes'
        )}>
        {renderList(t('Niewybrane'), attributesToChoose)}
        <div className={styles.actions}>
          <button
            onClick={() => {
              onChange(
                sortBy(
                  [
                    ...attributesSelected,
                    ...attributesToChoose.filter((item) =>
                      selectedAttributes.includes(item.field_name)
                    )
                  ],
                  'field_name'
                )
              );
              setSelectedAttributes([]);
            }}>
            &raquo;
          </button>

          <button
            onClick={() => {
              onChange(
                attributesSelected.filter((item) => !selectedAttributes.includes(item.field_name))
              );
              setSelectedAttributes([]);
            }}>
            &laquo;
          </button>
        </div>

        {renderList(t('Wybrane'), attributesSelected)}
      </div>

      {error && <div className={styles.error}>{error}</div>}
    </>
  );
};

export default Attributes;
