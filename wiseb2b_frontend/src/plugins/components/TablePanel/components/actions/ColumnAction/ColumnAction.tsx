/* eslint-disable @typescript-eslint/no-explicit-any */
import React, { FC, useState } from 'react';
import { useLocation } from 'react-router-dom';
import classnames from 'classnames';
import qs from 'query-string';

import { useAppNavigate } from 'hooks';
import { IDynamicAction } from 'plugins/api/types';
import * as Components from 'components/containers';
import { Modal } from 'components/controls';

import { paramsToUrlParser } from 'plugins/util/parser';
import { DynamicPage } from 'plugins/pages';

import styles from 'theme/pages/Clients/Clients.module.scss';

// typ danych wejściowych
interface IProps {
  action: IDynamicAction;
  refetch: () => void;
  item: any;
}

type IComponentKeys = 'ClientForm' | 'Order';

const ColumnAction: FC<IProps> = ({ action, refetch, item }) => {
  const { search } = useLocation();
  const navigate = useAppNavigate();

  const [open, setOpen] = useState(false);

  // import komponentu po nazwie w definicji akcji
  const componentKey = action?.method_params?.component_name;
  const Component =
    typeof componentKey === 'string' ? Components[componentKey as IComponentKeys] : null;

  const handleButtonClick = () => {
    if (action.method === 'OpenModalComponent' || action.method === 'OpenModalDynamicPage') {
      setOpen(true);

      return;
    }

    if (action.method === 'Redirect' && action.method_params?.page_url) {
      const paramsList = paramsToUrlParser(action.method_params?.params_list, item);

      navigate(action.method_params?.page_url + (paramsList ? `?${paramsList}` : ''));

      return;
    }
  };

  const handleSuccess = () => {
    setOpen(false);

    if (action.refresh_data_mode === 'ONSUCCESS') {
      refetch();
    }
  };

  const renderModalContent = () => {
    if (action.method === 'OpenModalComponent') {
      const requestParams = action.method_params?.params_list
        ? qs.parse(paramsToUrlParser(action.method_params?.params_list, item))
        : {};
      return (
        <Modal onClose={() => setOpen(false)} title="">
          {Component && (
            <Component
              onCancel={() => setOpen(false)}
              onSuccess={() => handleSuccess()}
              {...requestParams}
            />
          )}
        </Modal>
      );
    }

    if (action.method === 'OpenModalDynamicPage') {
      const requestParams = action.method_params?.params_list
        ? qs.parse(paramsToUrlParser(action.method_params?.params_list, item))
        : {};

      return (
        <Modal fullWidth onClose={() => setOpen(false)} title="">
          <DynamicPage
            pageSymbol={action.method_params?.dynamic_page + search || ''}
            componentParams={{ fnCallback: () => setOpen(false), isModal: false }}
            requestParams={requestParams}
          />
        </Modal>
      );
    }

    return null;
  };

  return (
    <>
      <span
        onClick={() => handleButtonClick()}
        className={classnames(styles.colored, styles.primary, styles.cliackable)}>
        {item[action.column_field_symbol || '']}
      </span>
      {open && renderModalContent()}
    </>
  );
};

export default ColumnAction;
