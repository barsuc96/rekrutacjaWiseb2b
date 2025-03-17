import React, { FC } from 'react';

import { IDynamicComponent } from 'plugins/api/types';

import { EditPanel, TablePanel } from 'plugins/components';

import 'plugins/theme/components/SubComponent/SubComponent.scss';

// typ danych wejściowych
interface IProps {
  pageSymbol: string;
  components?: IDynamicComponent[];
  requestParams?: object;
}

const ListHorizontalPanel: FC<IProps> = ({ pageSymbol, components, requestParams }) => {
  const renderContent = () => {
    if (components) {
      return components?.map((component) => {
        if (component.type === 'tablePanel') {
          return (
            <TablePanel
              filters={component.filters}
              actions={component.actions}
              columns={component.columns}
              loadDataParams={component.load_data_params}
              pageSymbol={pageSymbol}
              componentSymbol={component.symbol}
              requestParams={requestParams}
            />
          );
        }

        if (component.type === 'editPanel') {
          return (
            <EditPanel
              title={''}
              fields={component.fields}
              actions={component.actions}
              isModal={false}
              loadDataParams={component.load_data_params}
              pageSymbol={pageSymbol}
              componentSymbol={component.symbol}
              requestParams={requestParams}
            />
          );
        }

        return null;
      });
    }

    return null;
  };

  return <div>{renderContent()}</div>;
};

export default ListHorizontalPanel;
