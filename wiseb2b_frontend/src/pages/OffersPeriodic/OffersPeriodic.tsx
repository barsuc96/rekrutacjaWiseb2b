// lista ofert dedykowanych

import React, { useEffect, useState } from 'react';
import { Grid } from '@mui/material';
import { Trans, useTranslation } from 'react-i18next';
import classnames from 'classnames';

import { reduxActions, useDispatch } from 'store';
import { useGetOffersPeriodic } from 'api';
import { SearchInput, PageTitle, Pagination } from 'components/controls';
import GridSwitcher, { IProps as IGridSwitcherProps } from 'components/controls/GridSwitcher';
import { useRWD } from 'hooks';
import { ProductItem, MobileProductItem } from 'components/containers';

import styles from 'theme/pages/OffersPeriodic/OffersPeriodic.module.scss';


const DashboardOffersDedicated = () => {
  const { t } = useTranslation();
  const dispatch = useDispatch();
  const { isMobile, isTablet } = useRWD();
  // Widok listy (boxy/linie)
  const [gridType, setGridType] = useState<IGridSwitcherProps['type']>('grid');

  // Parametry zapytania do API
  const [queryParams, setQueryParams] = useState({
    page: 1,
    limit: 20,
    search_keyword: ''
  });

  // pobranie listy ofert
  const { data: offersData } = useGetOffersPeriodic(queryParams);

  // ustawienie breadcrumbs'ów po wejściu mna stronę
  useEffect(() => {
    dispatch(
      reduxActions.setBreadcrumbs([
        { name: t('Dashboard'), path: '/dashboard' },
        { name: t('Oferty okresowe') }
      ])
    );
  }, []);

  return (
    <div className={classnames(styles.wrapperComponent, 'StylePath-Pages-OffersPeriodic')}>
      <PageTitle
        title={
          <>
            <Trans>Oferty okresowe</Trans>{' '}
            <span className={styles.thin}>({offersData?.total_count})</span>
          </>
        }
      />

      <div className={styles.header}>
        <div className={styles.searchFormWrapper}>
          <SearchInput
            placeholder={`${t('Szukaj produktu w ramach ofert okresowych')}...`}
            onChange={(search_keyword) =>
              setQueryParams((prevState) => ({ ...prevState, page: 1, search_keyword }))
            }
          />
        </div>

        <GridSwitcher type={gridType} onChange={setGridType} />
      </div>

      <Grid container spacing={'11px'} style={{ position: 'relative', minHeight: '100px' }}>
        {(offersData?.items || []).map((offer) => (
          <Grid key={offer.id} item md={gridType === 'line' ? 12 : isTablet ? 4 : 3}>
            {isMobile ? (
              <MobileProductItem product={offer} />
            ) : (
              <ProductItem product={offer} line={gridType === 'line'} />
            )}
          </Grid>
        ))}
      </Grid>

      <Pagination
        pagesCount={offersData?.total_pages || 1}
        page={queryParams.page}
        onChange={(page) => setQueryParams((prevState) => ({ ...prevState, page }))}
      />
    </div>
  );
};

export default DashboardOffersDedicated;
