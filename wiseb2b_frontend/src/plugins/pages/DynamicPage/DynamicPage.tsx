import React, { FC, useEffect, useState } from 'react';
import isEqual from 'lodash/isEqual';

import { useGetDynamicUIPageDefinition } from 'plugins/api/endpoints';
import { IComponentParams, IDynamicUIPageDefinitionResponse } from 'plugins/api/types';
import { useNotifications, usePrevious } from 'hooks';

import { Container, PageTitle, Loader } from 'components/controls';

import { EditPanel, TablePanel, TabPanel } from 'plugins/components';

// typ danych wejściowych
interface IProps {
  pageSymbol: string;
  requestParams?: object;
  componentParams?: IComponentParams;
}

const DynamicPage: FC<IProps> = ({ pageSymbol, requestParams, componentParams }) => {
  const { showSuccessMessage, showErrorMessage } = useNotifications();
  const [isError, setIsError] = useState(false);

  const [pageDefinition, setPageDefinition] = useState<IDynamicUIPageDefinitionResponse | null>(
    null
  );

  const prevPageSymbol = usePrevious(pageSymbol);
  const prevRequestParams = usePrevious(requestParams);

  // pobieranie definicji komponentów
  const { refetch: getDynamicDefinition, isRefetching } = useGetDynamicUIPageDefinition(
    pageSymbol,
    requestParams,
    {
      enabled: false,
      onSuccess: (data) => {
        setIsError(false);

        if (data?.status === 0) {
          if (data?.show_message && data?.message) {
            if (data?.message_style === 'success') {
              showSuccessMessage(data?.message);

              return;
            }

            if (data?.message_style === 'error') {
              showErrorMessage(data?.message);

              return;
            }

            showErrorMessage(data?.message);
          }

          componentParams?.onErrorCallback?.();

          return;
        }

        setPageDefinition(data);
      },
      onError: () => {
        setIsError(true);
      }
    }
  );

  useEffect(() => {
    if (!isEqual(pageSymbol, prevPageSymbol) || !isEqual(requestParams, prevRequestParams)) {
      getDynamicDefinition();
    }
  }, [pageSymbol, requestParams, prevPageSymbol, prevRequestParams]);

  const renderContent = () => {
    return pageDefinition?.page_definition?.components.map((component, i) => {
      if (component.type === 'editPanel') {
        return (
          <>
            <EditPanel
              title={component.label}
              fields={component.fields}
              actions={component.actions}
              componentsList={pageDefinition?.page_definition?.components}
              loadDataParams={component.load_data_params}
              componentParams={componentParams}
              requestParams={requestParams}
              isModal={componentParams?.isModal}
              pageSymbol={pageSymbol}
              componentSymbol={component.symbol}
              key={i}
            />
          </>
        );
      }

      if (component.type === 'tablePanel') {
        return (
          <TablePanel
            title={component.label}
            filters={component.filters}
            actions={component.actions}
            columns={component.columns}
            loadDataParams={component.load_data_params}
            componentParams={componentParams}
            pageSymbol={pageSymbol}
            componentSymbol={component.symbol}
            key={i}
          />
        );
      }

      if (component.type === 'tabPanel') {
        return (
          <TabPanel
            title={component.label}
            names={component.names}
            components={component.components}
            pageSymbol={pageSymbol}
            requestParams={requestParams}
            key={i}
          />
        );
      }

      return null;
    });
  };

  const renderError = () => {
    return (
      <Container>
        <div>
          <h1>Brak definicji widoku</h1>
          <h2>Symbole definicji widoku:</h2>
          <p>
            page_symbol: <b>{pageSymbol}</b>
          </p>
        </div>
      </Container>
    );
  };

  return (
    <>
      {!isError ? (
        <div>
          {pageDefinition?.page_definition?.title && (
            <PageTitle title={pageDefinition.page_definition.title} />
          )}

          {isRefetching ? <Loader /> : renderContent()}
        </div>
      ) : (
        renderError()
      )}
    </>
  );
};

export default DynamicPage;
