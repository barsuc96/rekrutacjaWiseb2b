// strona 404

import React from 'react';

import { useAppNavigate } from 'hooks';

import { Button, Container } from 'components/controls';

import styles from 'theme/pages/NoMatch/NoMatch.module.scss';

const NoMatch = () => {
  const navigate = useAppNavigate();

  return (
    <Container>
      <div className={styles.wrapperComponent}>
        <h1>404</h1>
        <p>Nie znaleziono takiej strony</p>
        <p>
          Przepraszamy, nie możemy znaleźć strony, której szukasz! Naciśnij przycisk poniżej, aby
          wrócić do strony głównej.
        </p>
        <Button onClick={() => navigate('/')}>Powrót do strony głównej</Button>
      </div>
    </Container>
  );
};

export default NoMatch;
