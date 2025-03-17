/* eslint-disable @typescript-eslint/no-explicit-any */
import React, { FC } from 'react';
import { ThreeDotsVertical } from 'react-bootstrap-icons';

import { IDynamicAction } from 'plugins/api/types';

import { DropDown } from 'components/controls';

import { AdditionalActionCell } from './components';

// typ danych wejściowych
interface IProps {
  additionalActions: IDynamicAction[];
  item: any;
  refetchTableData: () => void;
  requestParams?: object;
}

const AdditionalAction: FC<IProps> = ({
  additionalActions,
  item,
  refetchTableData,
  requestParams
}) => {
  return (
    <>
      <DropDown
        label={
          <div>
            <ThreeDotsVertical />
          </div>
        }
        withDropdownIcon={false}
        items={additionalActions.map((additionalAction) => ({
          label: (
            <AdditionalActionCell
              additionalAction={additionalAction}
              item={item}
              refetchTableData={refetchTableData}
              requestParams={requestParams}
            />
          )
        }))}
      />
    </>
  );
};

export default AdditionalAction;
