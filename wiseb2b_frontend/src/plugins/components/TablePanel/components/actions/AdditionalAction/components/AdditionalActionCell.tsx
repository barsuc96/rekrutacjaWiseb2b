/* eslint-disable @typescript-eslint/no-explicit-any */
import React, { FC, useState } from 'react';
import { useLocation } from 'react-router-dom';
import { Trans } from 'react-i18next';
import qs from 'query-string';
import mapValues from 'lodash/mapValues';

import { useAppNavigate } from 'hooks';

import { Modal, Button } from 'components/controls';

import { useCallCommand, useDelete } from 'plugins/api';
import { IDynamicAction } from 'plugins/api/types';
import { loadDataUrlParser } from 'plugins/util/parser';

import 'plugins/theme/components/TablePanel/TablePanel.scss';

// typ danych wejściowych
interface IProps {
  additionalAction: IDynamicAction;
  item: any;
  refetchTableData: () => void;
  requestParams?: object;
}

type IParamsList = { id?: string; mode?: string };

const AdditionalActionCell: FC<IProps> = ({
  additionalAction,
  item,
  refetchTableData,
  requestParams
}) => {
  const navigate = useAppNavigate();
  const { search } = useLocation();

  const [open, setOpen] = useState(false);

  const endpointUrl = loadDataUrlParser(additionalAction.method_params?.command_url || '', {
    ...item,
    ...requestParams,
    ...qs.parse(search)
  });

  // lista parametrów
  const { id, mode, ...restParams }: IParamsList = qs.parse(
    additionalAction.method_params?.params_list?.replaceAll(';', '&') || ''
  );

  // poprawenie wartości parametru id
  // const propsId = item[id?.replace(':', '') || ''];

  // lista propsów
  // const paramsProps = mode === 'EDIT' ? { id: parseInt(propsId) } : {};

  // parsowanie parametrów do obiektu
  const paramsObject = mapValues(
    restParams,
    (param: string) => item[param?.replace(':', '') || '']
  );

  // parsowanie parametrów do urla
  const paramsUrl = qs.stringify({ ...paramsObject, mode });

  // wywołanie metody CallCommand
  const { mutate: callCommand } = useCallCommand(endpointUrl || '', {
    onSuccess: () => {
      refetchTableData();
    }
  });

  // wywołanie metody Delete
  const { mutate: deleteAction } = useDelete(endpointUrl || '', {
    onSuccess: () => {
      setOpen(false);
      refetchTableData();
    },
    onError: () => {
      setOpen(false);
    }
  });

  const handleActionClick = (additionalAction: IDynamicAction) => {
    // obsłużenie akcji CallCommand
    if (additionalAction.method === 'CallCommand') {
      callCommand({});
    }

    // obsłużenie akcji Delete
    if (additionalAction.method === 'Delete') {
      setOpen(true);
    }

    if (additionalAction.method === 'Redirect') {
      additionalAction.method_params?.page_url &&
        navigate(additionalAction.method_params.page_url + (paramsUrl ? `?${paramsUrl}` : ''));
    }
  };

  const handleDelete = () => {
    deleteAction({});
  };

  return (
    <>
      <div
        onClick={(e) => {
          e.stopPropagation();
          handleActionClick(additionalAction);
        }}>
        {additionalAction.label}
      </div>
      {open && (
        <Modal title={additionalAction.label} onClose={() => setOpen(false)}>
          {' '}
          <div className="confirmationModalActions">
            <Button onClick={() => setOpen(false)} ghost color="secondary">
              <Trans>Anuluj</Trans>
            </Button>
            <Button onClick={() => handleDelete()} color="danger">
              <Trans>Usuń</Trans>
            </Button>
          </div>
        </Modal>
      )}
    </>
  );
};

export default AdditionalActionCell;
