import React, { FC, useEffect } from 'react';
import { useSelector } from 'store';
import { useLocation } from 'react-router-dom';
import qs from 'query-string';

import { paramsListToRequestParser } from 'plugins/util/parser';
import { useGetDynamicUIComponentDefinition } from 'plugins/api/endpoints';
import { EditPanel } from 'plugins/components';

import 'plugins/theme/components/SubComponent/SubComponent.scss';

// typ danych wejściowych
interface IProps {
  componentSymbol: string;
  pageSymbol: string;
  parentComponentSymbol: string;
  paramsList?: string;
  requestParams?: object;
}

const SubComponent: FC<IProps> = ({
  componentSymbol,
  parentComponentSymbol,
  pageSymbol,
  paramsList,
  requestParams
}) => {
  // pobieranie globalnego stanu kontekstu danych
  const { contextData } = useSelector((state) => state.dynamicPage);

  const { search } = useLocation();

  // pobieranie danych kontekstu dla komponentu
  const componentContextData = contextData?.[pageSymbol]?.[parentComponentSymbol];

  // pobieranie danych kontekstu z url
  const urlContext = qs.parse(search, { parseNumbers: true });

  const params = { ...componentContextData, ...urlContext, ...requestParams };

  const request = paramsListToRequestParser(paramsList || '', {
    params
  });

  const { data: componentData, refetch: getComponentData } = useGetDynamicUIComponentDefinition(
    componentSymbol,
    request,
    { enabled: false }
  );

  useEffect(() => {
    if (componentContextData) {
      getComponentData();
    }
  }, [componentContextData]);

  const renderContent = () => {
    if (componentData?.component.type === 'editPanel') {
      return (
        <EditPanel
          title={componentData?.component.label}
          fields={componentData?.component.fields}
          actions={componentData?.component.actions}
          loadDataParams={componentData?.component.load_data_params}
          componentSymbol={componentData?.component.symbol}
          isModal={false}
          pageSymbol={pageSymbol}
          isSubComponent
        />
      );
    }

    return null;
  };

  return <div className="SubComponent">{renderContent()}</div>;
};

export default SubComponent;
