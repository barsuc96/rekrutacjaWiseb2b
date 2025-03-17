import { merge } from 'lodash';
import { ICommandResponseError } from '../types';

export const transformApiErrorsToFormik = (errors: ICommandResponseError['error_fields']) =>
  errors?.reduce((output, item) => {
    const errorItem = item.property_path
      .split('.')
      .reverse()
      .reduce((acc, pathPart, currentIndex) => {
        return { [pathPart]: currentIndex === 0 ? item.message : acc };
      }, {});

    return merge(output, errorItem);
  }, {}) || {};
