// wrapper elementu formularza
import React, { FC, PropsWithChildren } from 'react';
import classnames from 'classnames';

import styles from 'theme/components/controls/FormElement/FormElement.module.scss';

interface IFormProps extends PropsWithChildren {
  halfWidth?: boolean;
}

const FormElement: FC<IFormProps> = ({ children, halfWidth }) => {
  return (
    <div
      className={classnames(
        styles.wrapperComponent,
        { [styles.formElementHalfWidth]: halfWidth },
        'StylePath-Components-Controls-FormElement'
      )}>
      {children}
    </div>
  );
};

export default FormElement;
