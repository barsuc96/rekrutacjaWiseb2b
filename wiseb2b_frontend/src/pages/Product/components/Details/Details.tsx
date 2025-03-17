// szczegóły produktu

import React, { FC, useState } from 'react';
import { useTranslation } from 'react-i18next';
import classnames from 'classnames';
import { Tag, ChevronDown, X, Check2 } from 'react-bootstrap-icons';
import Tooltip from '@mui/material/Tooltip';
import { Trans } from 'react-i18next';

import { useAppNavigate } from 'hooks';
import { IProduct, ITechnicalAttribute, IProductVersion } from 'api/types';
import { Select, Link, Modal, Button } from 'components/controls';

import styles from 'theme/pages/Product/components/Details/Details.module.scss';

// typ danych wejściowych
interface IProps {
  product: IProduct;
  scrollToDescription: () => void;
}

const Details: FC<IProps> = ({ product, scrollToDescription }) => {
  const navigate = useAppNavigate();
  const { t } = useTranslation();

  // pokazywanie popupa z GPSR
  const [technicalInformationVisible, setTechnicalInformationVisible] = useState(false);

  // renderowanie atrybutu
  const renderTechnicalAttributeValue = (item: ITechnicalAttribute) =>
    item.type === 'boolean' ? (
      item.value ? (
        <Check2 className={styles.green} />
      ) : (
        <X className={styles.red} />
      )
    ) : (
      item.value
    );

  const renderGpsrContent = () => {
    const gpsrData = product.payload.gpsr_supplier_info;
    return (
      <div>
        <table>
          <tr>
            <th />
            <th />
          </tr>
          <tr className={styles.gpsrDataRow}>
            <td className={styles.gpsrDataCol}>
              <Trans>Nazwa</Trans>:
            </td>
            <td className={styles.gpsrDataCol}>{gpsrData?.registered_trade_name}</td>
          </tr>
          <tr className={styles.gpsrDataRow}>
            <td className={styles.gpsrDataCol}>
              <Trans>NIP</Trans>:
            </td>
            <td className={styles.gpsrDataCol}>{gpsrData?.tax_number}</td>
          </tr>
          <tr className={styles.gpsrDataRow}>
            <td className={styles.gpsrDataCol}>
              <Trans>Email</Trans>:
            </td>
            <td className={styles.gpsrDataCol}>
              <a href={`mailto:${gpsrData?.email}`}>{gpsrData?.email}</a>
            </td>
          </tr>
          <tr className={styles.gpsrDataRow}>
            <td className={styles.gpsrDataCol}>
              <Trans>Telefon</Trans>:
            </td>
            <td className={styles.gpsrDataCol}>{gpsrData?.phone}</td>
          </tr>
          <tr className={styles.gpsrDataRow}>
            <td className={classnames(styles.gpsrDataCol, styles.gpsrAddress)}>
              <div>
                <Trans>Dane adresowe</Trans>:
              </div>
            </td>
            <td className={styles.gpsrDataCol}>
              <div>
                {gpsrData?.address.street} {gpsrData?.address.house_number}
                {gpsrData?.address.apartment_number && `/${gpsrData?.address.apartment_number}`}
                <br />
                {gpsrData?.address.postal_code}, {gpsrData?.address.city}{' '}
                {gpsrData?.address.country}
              </div>
            </td>
          </tr>
        </table>
      </div>
    );
  };

  return (
    <div
      className={classnames(styles.productDetails, 'StylePath-Pages-Product-components-Details')}>
      <div className={styles.mainContent}>
        <h1 itemProp="name" className={styles.title}>
          {product.title}
        </h1>

        <ul className={styles.list}>
          <li className={styles.listItem}>
            <Trans>INDEX</Trans>: <strong>{product.index}</strong>
          </li>
          {product.producer_name && (
            <li
              className={styles.listItem}
              itemScope
              itemProp="brand"
              itemType="http://schema.org/Brand">
              <Trans>Producent</Trans>: <strong itemProp="name">{product.producer_name}</strong>
            </li>
          )}
          <li className={styles.listItem}>
            <Trans>Kategoria</Trans>:{' '}
            <strong>
              <Link to={`/products?category_id=${product.category_id}`}>
                {product.category_name}
              </Link>
            </strong>
          </li>
        </ul>

        <div className={styles.description}>
          <span className={styles.label}>
            <Trans>Opis</Trans>
          </span>
          <div itemProp="description" className={styles.text}>
            {product.description_short}
          </div>
        </div>

        <div className={styles.technicalAttributes}>
          <div className={styles.block}>
            {product.technical_attributes
              .filter((item) => item.highlighted)
              .map((item) => (
                <div className={styles.technicalAttributeItem} key={item.name}>
                  {item.name}{' '}
                  <span className={styles.value}>{renderTechnicalAttributeValue(item)}</span>
                </div>
              ))}
          </div>

          <button onClick={scrollToDescription}>
            <Trans>Przewiń do pełnego opisu</Trans> <ChevronDown />
          </button>

          {!!product.payload.gpsr_supplier_info && (
            <button
              className={classnames(styles.sizingTable, styles.gpsrButton)}
              onClick={() => setTechnicalInformationVisible(true)}>
              <Trans>Informacja o podmiocie wprowadzającym na rynek</Trans>
            </button>
          )}
        </div>

        {(product.versions.length > 0 || product.colors.length > 0) && (
          <div className={styles.options}>
            <div className={styles.optionsTitle}>
              <Trans>Dostępne rozmiary i kolory</Trans>:
            </div>

            {product.versions.length > 0 && (
              <div className={styles.versionsWrapper}>
                <Select<IProductVersion>
                  onChange={(item) => item && navigate(`/products/${item.product_id}`)}
                  options={product.versions.map((version) => ({
                    value: version.product_id,
                    label: version.name,
                    item: version
                  }))}
                  value={product.id}
                />
              </div>
            )}

            {product.colors.length > 0 && (
              <div className={styles.colorsWrapper}>
                {product.colors.map((color) => (
                  <Tooltip title={color.name} key={color.product_id}>
                    <span
                      className={classnames(styles.color, {
                        [styles.current]: color.product_id === product.id
                      })}
                      style={{ background: color.color }}
                      onClick={() => navigate(`/products/${color.product_id}`)}
                    />
                  </Tooltip>
                ))}
              </div>
            )}
          </div>
        )}
      </div>

      {!!product.promotion && (
        <div className={styles.dashedBlock}>
          <div className={styles.promotion} style={{ color: product.promotion.color }}>
            <Tag />
            {product.promotion.name}
            <Link to={`/products${product.promotion.filters}`}>
              <Trans>Zobacz więcej</Trans>
            </Link>
          </div>
        </div>
      )}

      {technicalInformationVisible && (
        <Modal
          title={t('Informacja o dostawcy')}
          onClose={() => {
            setTechnicalInformationVisible(false);
          }}>
          {renderGpsrContent()}
          <div className={styles.confirmationModalActions}>
            <Button onClick={() => setTechnicalInformationVisible(false)}>
              <Trans>Zamknij</Trans>
            </Button>
          </div>
        </Modal>
      )}
    </div>
  );
};

export default Details;
