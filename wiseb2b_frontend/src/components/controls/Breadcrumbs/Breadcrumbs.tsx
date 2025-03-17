// breadcrumbs'y

import React from 'react';
import classnames from 'classnames';
import { House, ChevronRight } from 'react-bootstrap-icons';

import { useSelector } from 'store';
import { useAppNavigate } from 'hooks';
import { Container } from 'components/controls';

import styles from 'theme/components/controls/Breadcrumbs/Breadcrumbs.module.scss';

const Breadcrumbs = ({ isLoading = false }: { isLoading?: boolean }) => {
  const navigate = useAppNavigate();
  const { breadcrumbs } = useSelector((state) => state.ui);

  return (
    <div
      className={classnames(styles.componentWrapper, 'StylePath-Components-Controls-Breadcrumbs')}
      itemScope
      itemType="http://schema.org/BreadcrumbList">
      <Container>
        <div className={styles.breadcrumbs}>
          <House onClick={() => navigate('/')} className={styles.home} />

          {!isLoading &&
            breadcrumbs.map(({ name, path }) => (
              <span
                key={`${path}-${name}`}
                className={styles.breadcrumb}
                itemProp="itemListElement"
                itemScope
                itemType="http://schema.org/ListItem">
                <meta itemProp="item" content={path} />
                <ChevronRight className={styles.arrow} />
                <span
                  onClick={() => path && navigate(path)}
                  className={classnames(styles.name, { [styles.disabled]: !path })}
                  itemProp="name">
                  {name}
                </span>
              </span>
            ))}
        </div>
      </Container>
    </div>
  );
};

export default Breadcrumbs;
