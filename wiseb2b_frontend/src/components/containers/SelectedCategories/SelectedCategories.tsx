// lista wybranych kategorii

import React from 'react';
import { Trans } from 'react-i18next';
import classnames from 'classnames';

import { Link } from 'components/controls';
import { useGetHomeCategories } from 'api';

import styles from 'theme/pages/Home/components/SelectedCategories/SelectedCategories.module.scss';

export const SelectedCategories = () => {
  const { data: homeCategoriesData } = useGetHomeCategories();

  return (
    <div className={classnames(styles.wrapperComponent, 'StylePath-Pages-Home-components-SelectedCategories')}>
      <h2 className={styles.title}>
        <Trans>Wybrane kategorie</Trans>
      </h2>

      <div className={styles.categoryWrapper}>
        {(homeCategoriesData?.items || []).map((category) => (
          <Link className={styles.item} key={category.id} to={`/products?category_id=${category.id}`}>
            <div className={styles.border}>
              <img className={styles.img} src={category.image} />
            </div>

            <div className={styles.name}>{category.name}</div>
          </Link>
        ))}
      </div>
    </div>
  );
};

export default SelectedCategories;
