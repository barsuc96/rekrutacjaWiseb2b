/* eslint-disable @typescript-eslint/no-explicit-any */
import React, { FC, useState } from 'react';
import qs from 'query-string';
import { useLocation } from 'react-router-dom';

import { IDynamicAction, IComponentParams } from 'plugins/api/types';

import { useAppNavigate } from 'hooks';

import * as Components from 'components/containers';
import { Modal, Button } from 'components/controls';
import { DynamicPage } from 'plugins/pages';
import { paramsListToPageUrlParser } from 'plugins/util/parser';

// typ danych wejściowych
interface IProps {
  action: IDynamicAction;
  refetch: () => void;
  componentParams?: IComponentParams;
}

type ComponentKeys = 'ClientForm' | 'Order';
type IParamsList = { id?: string; mode?: string };

const NoContextAction: FC<IProps> = ({ action, refetch, componentParams }) => {
  const navigate = useAppNavigate();
  const { search } = useLocation();

  const [open, setOpen] = useState(false);

  // import komponentu po nazwie w definicji akcji
  const componentKey = action?.method_params?.component_name;
  const Component =
    typeof componentKey === 'string' ? Components[componentKey as ComponentKeys] : null;

  // lista parametrów
  const { mode, ...restParams }: IParamsList = qs.parse(
    action.method_params?.params_list?.replaceAll(';', '&') || ''
  );

  const handleButtonClick = () => {
    if (action.method === 'OpenModalComponent' || action.method === 'OpenModalDynamicPage') {
      setOpen(true);
    }

    if (action.method === 'Redirect') {
      const redirectUrl = paramsListToPageUrlParser(
        action.method_params?.page_url || '',
        action.method_params?.params_list || '',
        qs.parse(search)
      );

      action.method_params?.page_url &&
        (window.location.href = window.location.origin + redirectUrl);
    }

    if (action.method === 'Cancel' && componentParams?.fnCallback) {
      componentParams?.fnCallback(false);
    }
  };

  const handleSuccess = () => {
    setOpen(false);

    if (action.refresh_data_mode === 'ONSUCCESS') {
      refetch();
    }
  };

  const renderOpenComponentModal = () => {
    return (
      <Modal onClose={() => setOpen(false)} title="">
        {Component && (
          <Component onCancel={() => setOpen(false)} onSuccess={() => handleSuccess()} />
        )}
      </Modal>
    );
  };

  const renderOpenModalDynamicPage = () => {
    return (
      <Modal fullWidth onClose={() => setOpen(false)} title="">
        <DynamicPage
          pageSymbol={action.method_params?.dynamic_page || ''}
          componentParams={{
            fnCallback: () => setOpen(false),
            isModal: false,
            refetchFn: refetch
          }}
        />
      </Modal>
    );
  };

  const renderContent = () => {
    if (open && action.method === 'OpenModalComponent') {
      return renderOpenComponentModal();
    }

    if (open && action.method === 'OpenModalDynamicPage') {
      return renderOpenModalDynamicPage();
    }

    return null;
  };

  return (
    <>
      <Button onClick={() => handleButtonClick()}>{action.label}</Button>
      {renderContent()}
    </>
  );
};

export default NoContextAction;
