// strona detali zamówienia

import React, { useMemo, FC } from 'react';
import { Grid } from '@mui/material';
import { useParams } from 'react-router-dom';
import classnames from 'classnames';
// TODO WIS-1465
import { OrderHeader, OrderSummary, OrderPositions } from './components';

import styles from 'theme/pages/Order/Order.module.scss';

interface IProps {
  id?: number;
}

const DashboardOrder: FC<IProps> = ({ id }) => {
  // ID zamówienia (przekształcony na int)
  const { orderId: orderIdParam } = useParams();
  const orderId = useMemo(() => id || parseInt(orderIdParam || ''), [orderIdParam, id]);

  return (
    <div className={classnames(styles.wrapperComponent, 'StylePath-Pages-Order')}>
      <Grid columnSpacing="32px">
        <Grid item lg={12}>
          <OrderHeader orderId={orderId} />
          <OrderSummary isPanel={!!id} orderId={orderId} />
          <OrderPositions isPanel={!!id} orderId={orderId} />
        </Grid>
      </Grid>
    </div>
  );
};

export default DashboardOrder;
