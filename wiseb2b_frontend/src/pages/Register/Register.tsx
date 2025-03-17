// strona regulaminu

import React from 'react';
import classnames from 'classnames';
import { Trans } from 'react-i18next';

import { useRWD } from 'hooks';
import { RegisterForm } from 'components/containers';
import { Container, Breadcrumbs, PageTitle } from 'components/controls';

import styles from 'theme/pages/Register/Register.module.scss';

const Register = () => {
  const { isMobile } = useRWD();

  return (
    <div className={classnames(styles.wrapperComponent, 'StylePath-Pages-Register')}>
      <Container>
        {isMobile && <Breadcrumbs />}
        <div className={styles.contentWrapper}>
          <div className={styles.content}>
            <PageTitle title={<Trans>Rejestracja</Trans>} />
            <RegisterForm />
          </div>
        </div>
      </Container>
    </div>
  );
};

export default Register;
