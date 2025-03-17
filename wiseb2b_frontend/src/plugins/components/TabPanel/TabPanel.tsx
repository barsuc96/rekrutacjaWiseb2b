import React, { FC, useState } from 'react';
import classnames from 'classnames';

import { IDynamicComponent, IName } from 'plugins/api/types';
import { TablePanel, EditPanel, ListHorizontalPanel } from 'plugins/components';

import { PageTitle } from 'components/controls';

import 'plugins/theme/components/TabPanel/TabPanel.scss';

// typ danych wejściowych
interface IProps {
  title: string;
  names?: IName[];
  components?: IDynamicComponent[];
  pageSymbol: string;
  requestParams?: object;
}

const TabPanel: FC<IProps> = ({ title, names, components, pageSymbol, requestParams }) => {
  // lista tabów
  const tabs =
    names?.map((name: IName) => ({
      key: name.component_symbol,
      label: name.name
    })) || [];

  // rodzaj aktyenej listy
  const [activeTab, setActiveTab] = useState<string>(tabs?.[0].key || '');

  const activeComponent = components?.find(
    (component: IDynamicComponent) => component.symbol === activeTab
  );

  const renderActiveComponent = () => {
    if (activeComponent?.type === 'tablePanel') {
      return (
        <TablePanel
          filters={activeComponent.filters}
          actions={activeComponent.actions}
          columns={activeComponent.columns}
          loadDataParams={activeComponent.load_data_params}
          pageSymbol={pageSymbol}
          componentSymbol={activeComponent.symbol}
          requestParams={requestParams}
        />
      );
    }

    if (activeComponent?.type === 'editPanel') {
      return (
        <EditPanel
          title={''}
          fields={activeComponent.fields}
          actions={activeComponent.actions}
          isModal={false}
          loadDataParams={activeComponent.load_data_params}
          pageSymbol={pageSymbol}
          componentSymbol={activeComponent.symbol}
          requestParams={requestParams}
        />
      );
    }

    if (activeComponent?.type === 'listHorizontalPanel') {
      return (
        <ListHorizontalPanel
          pageSymbol={pageSymbol}
          components={activeComponent.components}
          requestParams={requestParams}
        />
      );
    }

    return <div className="tabPlaceholder" />;
  };

  const handleTabChange = (tab: string) => {
    // ustawienie delay, żeby odmontować i zamontować komponenty
    setTimeout(() => {
      setActiveTab(tab);
    }, 10);
  };

  return (
    <div className="listWrapper">
      <PageTitle title={title} />
      <div className="tabs">
        {tabs.map((tab) => (
          <div
            key={tab.key}
            className={classnames('tab', {
              active: tab.key === activeTab
            })}
            onClick={() => (setActiveTab(''), handleTabChange(tab.key))}>
            {tab.label}
          </div>
        ))}
      </div>
      <div className="contentWrapper">{renderActiveComponent()}</div>
    </div>
  );
};

export default TabPanel;
