// strona rozpoczęcia procesu resetu hasła

import React, { useEffect, useState } from 'react';
import classnames from 'classnames';
import { Trans } from 'react-i18next';
import { useLocation } from 'react-router-dom';
import qs from 'query-string';

import { useAppNavigate } from 'hooks';
import { usePostUserRegisterEmailConfirm } from 'api';
import { Button, Container } from 'components/controls';

import styles from 'theme/pages/ThankYouRegister/ThankYouRegister.module.scss';

const RegisterEmailConfirm = () => {
  const { search } = useLocation();
  const navigate = useAppNavigate();

  const [message, setMessage] = useState('');

  const { mutate } = usePostUserRegisterEmailConfirm({
    onSuccess: (data) => {
      setMessage(data.message);
    }
  });

  const hash = qs.parse(search)?.hash;

  useEffect(() => {
    if (hash && typeof hash === 'string') {
      mutate({ hash });
    }
  }, []);

  return (
    <div className={classnames(styles.wrapperComponent, 'StylePath-Pages-RegisterEmailConfirm')}>
      <Container>
        <h3>{message}</h3>
        {message && (
          <div className={styles.actions}>
            <Button onClick={() => navigate('/')}>
              <Trans>Powrót na stronę główną</Trans>
            </Button>
          </div>
        )}
      </Container>
    </div>
  );
};

export default RegisterEmailConfirm;
