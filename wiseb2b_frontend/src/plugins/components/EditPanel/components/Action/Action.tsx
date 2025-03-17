/* eslint-disable @typescript-eslint/no-explicit-any */
import React, { FC } from 'react';
import { useLocation } from 'react-router-dom';
import qs from 'query-string';

import { useAppNavigate } from 'hooks';
import { IDynamicAction, IComponentParams } from 'plugins/api/types';
import { useCallCommand, useAdd, useUpdate } from 'plugins/api';
import {
  loadDataUrlParser,
  paramsToUrlParser,
  paramsListToRequestParser
} from 'plugins/util/parser';

import { Button } from 'components/controls';

// typ danych wejściowych
interface IProps {
  action: IDynamicAction;
  handleClose: () => void;
  refetchEditPanelData: () => void;
  componentParams?: IComponentParams;
  requestParams?: any;
  requestState?: any;
  pageSymbol: string;
}

const Action: FC<IProps> = ({
  action,
  handleClose,
  refetchEditPanelData,
  requestParams,
  requestState,
  componentParams,
  pageSymbol
}) => {
  const { search, pathname } = useLocation();
  const navigate = useAppNavigate();

  const getPosition = (string: string, subString: string, index: number) => {
    return string.split(subString, index).join(subString).length;
  };

  const urlPrefix = pathname.slice(0, getPosition(pathname, '/', 2));

  // endpoint url dla komendy Add
  const addUrl = loadDataUrlParser(action.method_params?.command_url || '', {
    ...requestParams,
    ...qs.parse(search)
  });

  const { mutate: callCommand } = useCallCommand(addUrl || '', {
    onSuccess: () => {
      if (action.refresh_data_mode === 'ONSUCCESS') {
        refetchEditPanelData();
        componentParams?.refetchFn?.();
        handleClose();
      }
    }
  });

  const { mutate: add } = useAdd(addUrl || '', {
    onSuccess: (data) => {
      if (action?.method_params?.result_params_to_save) {
        const paramsUrl = paramsToUrlParser(action.method_params?.result_params_to_save, {
          ...qs.parse(window.location.search),
          ...data.data
        });

        paramsUrl && navigate(pathname.replace(`${urlPrefix}`, '') + `?${paramsUrl}`);
      }

      if (action.refresh_data_mode === 'ONSUCCESS') {
        refetchEditPanelData();
        componentParams?.fnCallback?.();
        componentParams?.refetchFn?.();
      }
    }
  });

  const { mutate: update } = useUpdate(addUrl || '', {
    onSuccess: () => {
      if (action.refresh_data_mode === 'ONSUCCESS') {
        refetchEditPanelData();
        componentParams?.fnCallback?.();
        componentParams?.refetchFn?.();
      }
    }
  });

  const handleAction = () => {
    if (action.method === 'Cancel') {
      handleClose();
    }

    if (action.method === 'Redirect' && action.method_params?.page_url) {
      navigate(action.method_params?.page_url);
    }

    if (action.method === 'CallCommand') {
      const request = paramsListToRequestParser(
        action.method_params?.params_list || '',
        requestState[pageSymbol]
      );

      callCommand(request);
    }

    if (action.method === 'Add') {
      const request = paramsListToRequestParser(
        action.method_params?.params_list || '',
        requestState[pageSymbol]
      );

      add(request);
    }

    if (action.method === 'Update') {
      const request = paramsListToRequestParser(
        action.method_params?.params_list || '',
        requestState[pageSymbol]
      );

      update(request);
    }
  };

  return <Button onClick={handleAction}>{action.label}</Button>;
};

export default Action;
