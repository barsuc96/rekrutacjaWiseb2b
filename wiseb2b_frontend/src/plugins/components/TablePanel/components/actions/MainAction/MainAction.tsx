/* eslint-disable @typescript-eslint/no-explicit-any */
import React, { FC, useState } from 'react';
import { useLocation } from 'react-router-dom';
import classnames from 'classnames';
import { Pencil, PlusCircle } from 'react-bootstrap-icons';
import qs from 'query-string';
import mapValues from 'lodash/mapValues';

import { useAppNavigate } from 'hooks';
import { IDynamicAction } from 'plugins/api/types';
import { useCallCommand } from 'plugins/api';
import { loadDataUrlParser, paramsToUrlParser } from 'plugins/util/parser';
import * as Components from 'components/containers';
import { DynamicPage } from 'plugins/pages';

import { Modal } from 'components/controls';

import { TrashIcon } from 'assets/icons';

import styles from 'theme/pages/Clients/Clients.module.scss';

// typ danych wejściowych
interface IProps {
  action: IDynamicAction;
  refetch: () => void;
  item: any;
}

type IComponentKeys = 'ClientForm' | 'Order';
type IParamsList = { id?: string; mode?: string };

const MainActon: FC<IProps> = ({ action, refetch, item }) => {
  const navigate = useAppNavigate();
  const { search } = useLocation();

  const [open, setOpen] = useState(false);

  // import komponentu po nazwie w definicji akcji
  const componentKey = action?.method_params?.component_name;
  const Component =
    typeof componentKey === 'string' ? Components[componentKey as IComponentKeys] : null;

  // lista parametrów
  const { id, mode, ...restParams }: IParamsList = qs.parse(
    action.method_params?.params_list.replaceAll(';', '&') || ''
  );

  // poprawenie wartości parametru id
  const propsId = item[id?.replace(':', '') || ''];

  // lista propsów
  const paramsProps = mode === 'EDIT' ? { id: parseInt(propsId) } : {};

  // otrzymywanie endpointu do metody callCommand
  const callCommandUrl = loadDataUrlParser(
    action.method_params?.command_url || '',
    qs.parse(search)
  );

  // wywołanie metody CallCommand
  const { mutate: callCommand } = useCallCommand(callCommandUrl || '');

  // parsowanie parametrów do obiektu
  const paramsObject = mapValues(
    restParams,
    (param: string) => item[param?.replace(':', '') || '']
  );

  // parsowanie parametrów do urla
  const paramsUrl = qs.stringify({ ...paramsObject, mode });

  const handleButtonClick = () => {
    if (action.method === 'OpenModalComponent' || action.method === 'OpenModalDynamicPage') {
      setOpen(true);
    }

    if (action.method === 'Delete') {
      console.log('DELETE');
    }

    if (action.method === 'Redirect') {
      if (
        action.type === 'CONTEXT_MAIN' ||
        action.type === 'CONTEXT_ADDITIONAL' ||
        action.type === 'COLUMN'
      ) {
        action.method_params?.page_url &&
          navigate(action.method_params.page_url + (paramsUrl ? `?${paramsUrl}` : ''));
      }
    }

    if (action.method === 'CallCommand') {
      callCommand(paramsObject);
    }
  };

  const handleSuccess = () => {
    setOpen(false);

    if (action.refresh_data_mode === 'ONSUCCESS') {
      refetch();
    }
  };

  const renderButtonIcon = () => {
    if (action.style === 'ACTION_EDIT') {
      return <Pencil />;
    }

    if (action.style === 'DELETE') {
      return <TrashIcon />;
    }

    if (action.style === 'ACTION_PLUS') {
      return <PlusCircle />;
    }

    return null;
  };

  const renderModalContent = () => {
    if (action.method === 'OpenModalComponent') {
      console.log(paramsProps);
      return (
        <Modal onClose={() => setOpen(false)} title="">
          {Component && (
            <Component
              onCancel={() => setOpen(false)}
              onSuccess={() => handleSuccess()}
              {...paramsProps}
            />
          )}
        </Modal>
      );
    }

    if (action.method === 'OpenModalDynamicPage') {
      const requestParams = action.method_params?.params_list
        ? qs.parse(
            paramsToUrlParser(action.method_params?.params_list, { ...item, ...qs.parse(search) })
          )
        : {};

      return (
        <Modal fullWidth onClose={() => setOpen(false)} title="">
          <DynamicPage
            pageSymbol={action.method_params?.dynamic_page + search || ''}
            componentParams={{
              fnCallback: () => setOpen(false),
              isModal: false,
              refetchFn: refetch
            }}
            requestParams={requestParams}
          />
        </Modal>
      );
    }

    return null;
  };

  return (
    <>
      <button
        onClick={() => handleButtonClick()}
        className={classnames(styles.editButton, action.style)}>
        {renderButtonIcon()}
      </button>
      {open && renderModalContent()}
    </>
  );
};

export default MainActon;
