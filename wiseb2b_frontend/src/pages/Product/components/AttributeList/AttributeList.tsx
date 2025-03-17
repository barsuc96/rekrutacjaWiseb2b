// list atrybutów

import React, { FC } from 'react';
import { Check2, X, FileEarmark, FileEarmarkPdf } from 'react-bootstrap-icons';
import { Grid } from '@mui/material';
import classnames from 'classnames';
import { Link } from 'react-router-dom';

import { ITechnicalAttribute, ILogisticAttribute, IProductFile } from 'api/types';

import styles from 'theme/pages/Product/components/AttributeList/AttributeList.module.scss';

// typ danych wejściowych
interface IProps {
  attributes: Array<ITechnicalAttribute | ILogisticAttribute | IProductFile>;
}

const AttributeList: FC<IProps> = ({ attributes }) => {
  // funcja sprawdzająca czy atrybut jest plikiem
  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  const isFile = (test: any): test is IProductFile => typeof test.extension === 'string';

  // funcja renderująca wartość atrybutu
  const renderValue = (attribute: ITechnicalAttribute | ILogisticAttribute) => {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    const isTechnicalAttribute = (test: any): test is ITechnicalAttribute =>
      typeof test.type === 'string';

    return isTechnicalAttribute(attribute) && attribute.type === 'boolean' ? (
      attribute.value ? (
        <Check2 className={styles.green} />
      ) : (
        <X className={styles.red} />
      )
    ) : (
      <>
        {!isTechnicalAttribute(attribute) && <img src={attribute.icon} alt="" />} {attribute.value}
      </>
    );
  };

  return (
    <div
      className={classnames(
        styles.attributeList,
        'StylePath-Pages-Product-components-AttributeList'
      )}>
      <Grid className={styles.listContainer} container spacing={{ xs: 0, lg: 3 }}>
        {attributes.map((attribute) => (
          <Grid className={styles.item} item key={attribute.name} xs={12} lg={4}>
            {isFile(attribute) ? (
              <div className={styles.attributeFile}>
                {attribute.label}:
                {attribute.extension === 'pdf' ? <FileEarmarkPdf /> : <FileEarmark />}
                <Link className={styles.link} to={attribute.url}>
                  <strong>{attribute.name}</strong>
                </Link>
              </div>
            ) : (
              <div className={styles.attributeItem}>
                <div className={styles.attributeName}>{attribute.name}</div>
                <div className={styles.attributeValue}>{renderValue(attribute)}</div>
              </div>
            )}
          </Grid>
        ))}
      </Grid>
    </div>
  );
};

export default AttributeList;
