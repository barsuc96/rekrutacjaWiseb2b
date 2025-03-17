import qs from 'query-string';
import mapValues from 'lodash/mapValues';
import each from 'lodash/each';

const getAllIndexes = (arr: string, val: string) => {
  const indexes = [];
  let i = -1;
  while ((i = arr.indexOf(val, i + 1)) != -1) {
    indexes.push(i);
  }
  return indexes;
};

/* eslint-disable @typescript-eslint/no-explicit-any */
export const loadDataUrlParser = (commandUrl: string, item: any) => {
  const colonIndex = commandUrl?.indexOf(':') || 0;
  if (colonIndex === -1) {
    return commandUrl;
  }

  const allColons = getAllIndexes(commandUrl, ':');
  let paramsList: string[] = [];
  allColons.forEach((colon) => {
    const slash = commandUrl?.indexOf('/', colon);
    if (slash !== -1) {
      const param = commandUrl.slice(colon, slash);
      return (paramsList = [...paramsList, param]);
    }

    const param = commandUrl.slice(colon, commandUrl.length);
    paramsList = [...paramsList, param];
  });

  let endpointUrl = commandUrl;

  paramsList.forEach((param) => {
    const newValue = item[param.replace(':', '')];
    endpointUrl = endpointUrl.replace(param, newValue);
  });

  return endpointUrl;
};

type IParamsList = { mode?: string; [key: string]: any };

export const paramsToUrlParser = (params: string, data: any) => {
  // lista parametrów
  const { mode, ...restParams }: IParamsList = qs.parse(params.replaceAll(';', '&') || '');

  // parsowanie parametrów do obiektu
  const paramsObject = mapValues(restParams, (param: string, key: string) => {
    if (param?.[0] === ':') {
      const paramKey = param.replace(':', '');

      return data[paramKey];
    }
    return data[key];
  });

  // parsowanie parametrów do urla
  return qs.stringify({ ...paramsObject, mode });
};

export const paramsListToRequestParser = (paramsList: string, requestState: any) => {
  const params = paramsList.split(';');
  let req = {};
  each(requestState, (o) => (req = { ...req, ...o }));
  const request = params?.reduce(
    (acc, curr) => (((acc as any)[curr] = (req as any)[curr]), acc),
    {}
  );

  return request;
};

export const paramsListToPageUrlParser = (pageUrl: string, paramsList: string, data: any) => {
  const paramsListFormatted = qs.parse(paramsList.replaceAll(';', '&') || '');

  const paramsListObject = mapValues(paramsListFormatted, (value) => {
    if (typeof value === 'string' && value[0] === ':') {
      const key = value.replaceAll(':', '');

      return data[key];
    }

    return value;
  });

  return pageUrl + `?${qs.stringify(paramsListObject)}`;
};
