// strona rozpoczęcia procesu resetu hasła

import React from 'react';
import classnames from 'classnames';
import { Trans } from 'react-i18next';

import { useAppNavigate } from 'hooks';
import { Button, Container } from 'components/controls';

import styles from 'theme/pages/ThankYouRegister/ThankYouRegister.module.scss';

const ThankYouRegister = () => {
  const navigate = useAppNavigate();

  return (
    <div className={classnames(styles.wrapperComponent, 'StylePath-Pages-ThankYouRegister')}>
      <Container>
        <h3>
          <Trans>
            Dziękujemy za złożenie formularza rejestracyjnego. Na podanego maila został wysłany mail
            z potwierdzeniem adresu rejestracyjnego. Proces rejestracji będzie kontynuowany po
            potwierdzeniu adresu mailowego.
          </Trans>
        </h3>
        <div className={styles.actions}>
          <Button onClick={() => navigate('/')}>
            <Trans>Powrót na stronę główną</Trans>
          </Button>
        </div>
      </Container>
    </div>
  );
};

export default ThankYouRegister;
