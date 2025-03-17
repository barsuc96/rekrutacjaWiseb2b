// główny layout - środkowa belka nagłówka

import React, { useState } from 'react';
import { useTranslation } from 'react-i18next';
import { useLocation } from 'react-router-dom';
import { Person } from 'react-bootstrap-icons';
import classnames from 'classnames';

import { useAppNavigate } from 'hooks';
import { useDispatch, useSelector, reduxActions } from 'store';
import { Search, CartsButton } from 'components/containers';
import { Container, Link } from 'components/controls';
import ShoppingListButton from 'components/containers/ShoppingListButton';

import logo from 'assets/logo.svg';

import styles from 'theme/components/layouts/MainLayout/components/HeaderMain/HeaderMain.module.scss';

const HeaderMain = () => {
  const dispatch = useDispatch();
  const location = useLocation();
  const { t } = useTranslation();

  const navigate = useAppNavigate();
  const { profile } = useSelector((state) => state.auth);

  const [trigerReset, setTriggerReset] = useState(false);

  return (
    <div
      className={classnames(
        styles.componentWrapper,
        'StylePath-Components-Layouts-MainLayout-Components-HeaderMain'
      )}>
      <Container>
        <div className={styles.main}>
          <button
            onClick={() => {
              navigate('/');

              setTimeout(() => {
                dispatch(reduxActions.setSearchKeyword(''));
                setTriggerReset(true);
              }, 10);
            }}
            className={styles.logo}>
            <img src={logo} alt="logo" />
          </button>

          <Search trigerReset={trigerReset} setTriggerReset={setTriggerReset} />

          <div className={styles.buttons}>
            {profile?.role !== 'ROLE_OPEN_PROFILE' && (
              <>
                <Link
                  to="/dashboard"
                  className={classnames(styles.link, {
                    [styles.active]: location.pathname.includes('dashboard')
                  })}>
                  <Person /> {t('Twoje konto')}
                </Link>
                <ShoppingListButton />
                <CartsButton />
              </>
            )}
          </div>
        </div>
      </Container>
    </div>
  );
};

export default HeaderMain;
