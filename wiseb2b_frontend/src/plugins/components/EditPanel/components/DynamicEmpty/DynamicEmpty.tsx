import React, { FC } from 'react';
import { Grid } from '@mui/material';

import { IDynamicUiField } from 'plugins/api/types';

// typ danych wejściowych
interface IProps {
  fields: IDynamicUiField[];
  field: IDynamicUiField;
  index: number;
}

const DynamicEmpty: FC<IProps> = ({ field, fields, index }) => {
  const calculatedWidth = field.width || 12 - (fields[index - 1]?.width || 12);

  return (
    <Grid item xs={calculatedWidth} className="gridItem" key={index}>
      <div className="dynamicEmptty"></div>
    </Grid>
  );
};

export default DynamicEmpty;
