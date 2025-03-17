/* eslint-disable @typescript-eslint/no-unused-vars */
/* eslint-disable @typescript-eslint/no-explicit-any */
import React, { FC, useState, useEffect } from 'react';
import { useLocation } from 'react-router-dom';
import qs from 'query-string';
import mapValues from 'lodash/mapValues';
import keyBy from 'lodash/keyBy';
import { Grid } from '@mui/material';

import { reduxActions, useDispatch, useSelector } from 'store';
import { useGetLayoutLanguages } from 'api';
import {
  IDynamicUiField,
  IComponentParams,
  IDynamicAction,
  ILoadDataParam,
  IDynamicComponent
} from 'plugins/api/types';
import { Modal } from 'components/controls';
import { useLoadData } from 'plugins/api/endpoints';
import { loadDataUrlParser } from 'plugins/util/parser';
import { SubComponent } from 'plugins/components';

import { DynamicSelect } from './components/DynamicSelect';
import { DynamicList } from './components/DynamicList';
import { DynamicDecimal } from './components/DynamicDecimal';
import { DynamicText } from './components/DynamicText';
import { DynamicTextArea } from './components/DynamicTextArea';
import { DynamicCheckbox } from './components/DynamicCheckbox';
import { DynamicTranslations } from './components/DynamicTranslations';
import { DynamicDate } from './components/DynamicDate';
import { DynamicEmpty } from './components/DynamicEmpty';
import { DynamicFile } from './components/DynamicFile';
import { Action } from './components/Action';

import 'plugins/theme/components/EditPanel/EditPanel.scss';

// typ danych wejściowych
interface IProps {
  title: string;
  isModal?: boolean;
  fields: IDynamicUiField[];
  actions: IDynamicAction[];
  componentParams?: IComponentParams;
  requestParams?: object;
  loadDataParams?: ILoadDataParam;
  pageSymbol: string;
  componentSymbol: string;
  componentsList?: IDynamicComponent[];
  isSubComponent?: boolean;
}

const EditPanel: FC<IProps> = ({
  title,
  isModal = true,
  fields,
  componentParams,
  requestParams,
  actions,
  loadDataParams,
  pageSymbol,
  componentSymbol,
  componentsList,
  isSubComponent
}) => {
  const { search } = useLocation();
  const dispatch = useDispatch();

  // czy jest widoczny modal
  const [isVisible, setIsVisible] = useState(componentParams?.isEditorInitialVisible);

  // pobieranie globalnego stanu kontekstu danych
  const { contextData } = useSelector((state) => state.dynamicPage);

  const loadDataUrl = loadDataUrlParser(loadDataParams?.get_url || '', {
    ...requestParams,
    ...qs.parse(search)
  });

  // pobranie danych tabeli
  const { data: editPanelData, refetch: refetchEditPanelData } = useLoadData(
    loadDataUrl,
    {},
    { enabled: false }
  );

  // pobranie listy dostępnych języków
  const { data: languagesData } = useGetLayoutLanguages();

  // pobranie danych początkowych
  useEffect(() => {
    if (loadDataParams) {
      refetchEditPanelData();
    }
  }, [loadDataParams]);

  // ustalenie początkowego stanu requestState
  useEffect(() => {
    const arr = fields?.map((field) => {
      if (field.type === 'translation' && field.value === null) {
        return {
          [field.field_symbol]: []
        };
      }

      return {
        [field.field_symbol]: (editPanelData as any)?.[field.field_symbol] || field.value
      };
    });

    dispatch(
      reduxActions.setContextData({
        [pageSymbol]: {
          [componentSymbol]: mapValues(
            keyBy(arr, (key: string) => Object.keys(key)[0]),
            (value) => Object.values(value)[0]
          )
        }
      })
    );
  }, [editPanelData]);

  useEffect(() => {
    setIsVisible(componentParams?.isEditorInitialVisible);
  }, [componentParams?.isEditorInitialVisible]);

  const handleClose = () => {
    setIsVisible(false), componentParams?.fnCallback?.(false);
  };

  const renderContent = (field: IDynamicUiField, i: number) => {
    const { value, field_symbol, ...restFields } = field;
    const mapField = {
      field_symbol,
      ...restFields,
      value: (editPanelData as any)?.[field_symbol] || value
    };

    if (field.type === 'select') {
      return (
        <Grid item xs={field.width} key={i} className="gridItem" justifyContent="flex-start">
          <DynamicSelect
            field={mapField}
            pageSymbol={pageSymbol}
            componentSymbol={componentSymbol}
          />
        </Grid>
      );
    }

    if (field.type === 'list') {
      return (
        <Grid item xs={field.width} key={i} className="gridItem" justifyContent="flex-start">
          <DynamicList field={mapField} pageSymbol={pageSymbol} componentSymbol={componentSymbol} />
        </Grid>
      );
    }

    if (field.type === 'decimal' || field.type === 'integer' || field.type === 'float') {
      return (
        <Grid item xs={field.width} key={i} className="gridItem" justifyContent="flex-start">
          <DynamicDecimal
            field={mapField}
            pageSymbol={pageSymbol}
            componentSymbol={componentSymbol}
          />
        </Grid>
      );
    }

    if (field.type === 'text' || field.type === 'string') {
      return (
        <Grid item xs={field.width} key={i} className="gridItem" justifyContent="flex-start">
          <DynamicText field={mapField} pageSymbol={pageSymbol} componentSymbol={componentSymbol} />
        </Grid>
      );
    }

    if (field.type === 'textarea') {
      return (
        <Grid item xs={field.width} key={i} className="gridItem" justifyContent="flex-start">
          <DynamicTextArea
            field={mapField}
            pageSymbol={pageSymbol}
            componentSymbol={componentSymbol}
          />
        </Grid>
      );
    }

    if (field.type === 'translation') {
      return (
        <Grid item xs={field.width} key={i} className="gridItem" justifyContent="flex-start">
          <DynamicTranslations
            field={mapField}
            pageSymbol={pageSymbol}
            componentSymbol={componentSymbol}
            languagesData={languagesData?.items}
          />
        </Grid>
      );
    }

    if (field.type === 'boolean') {
      return (
        <Grid item xs={field.width} key={i} className="gridItem" justifyContent="flex-start">
          <DynamicCheckbox
            field={mapField}
            pageSymbol={pageSymbol}
            componentSymbol={componentSymbol}
          />
        </Grid>
      );
    }

    if (field.type === 'date') {
      return (
        <Grid item xs={field.width} key={i} className="gridItem" justifyContent="flex-start">
          <DynamicDate field={mapField} pageSymbol={pageSymbol} componentSymbol={componentSymbol} />
        </Grid>
      );
    }

    if (field.type === 'file') {
      return (
        <Grid item xs={field.width} key={i} className="gridItem" justifyContent="flex-start">
          <DynamicFile field={mapField} pageSymbol={pageSymbol} componentSymbol={componentSymbol} />
        </Grid>
      );
    }

    if (field.type === 'empty') {
      return <DynamicEmpty fields={fields} field={field} index={i} />;
    }

    return null;
  };

  const renderSubComponent = () => {
    const subComponentsActionList = actions?.filter((action) => !!action.sub_components_to_refresh);

    return (subComponentsActionList || []).map((subComponentAction) => {
      const activeSubcomponent = componentsList?.find(
        (component) => component.symbol === subComponentAction.sub_components_to_refresh
      );
      if (activeSubcomponent) {
        return (
          <SubComponent
            componentSymbol={subComponentAction.sub_components_to_refresh || ''}
            pageSymbol={pageSymbol}
            parentComponentSymbol={componentSymbol}
            paramsList={activeSubcomponent.params_list}
            requestParams={requestParams}
          />
        );
      }

      return null;
    });
  };

  const renderActions = (action: IDynamicAction, i: number) => {
    if (action.type) {
      return (
        <Action
          key={i}
          action={action}
          handleClose={() => handleClose()}
          refetchEditPanelData={refetchEditPanelData}
          requestParams={requestParams}
          requestState={contextData}
          componentParams={componentParams}
          pageSymbol={pageSymbol}
        />
      );
    }

    return null;
  };

  const renderEditPanel = () => {
    return (
      <div className="EditPanel-ContentWrapper">
        {title && <div>{isSubComponent ? <h3>{title}</h3> : <h2>{title}</h2>}</div>}

        <div className="EditPanel-Content">
          <Grid container columnSpacing={3} rowSpacing={2}>
            {fields?.map((field, i) => renderContent(field, i))}
          </Grid>
        </div>

        <div className="EditPanel-ActionWrapper">
          {actions && actions.map((action, i) => renderActions(action, i))}
        </div>

        {renderSubComponent()}
      </div>
    );
  };

  if (isVisible && isModal) {
    return (
      <Modal title={title} onClose={() => handleClose()}>
        {renderEditPanel()}
      </Modal>
    );
  }

  return renderEditPanel();
};

export default EditPanel;
