// główny layout

import React, { useCallback, useEffect, useRef, useState } from 'react';
import { useLocation } from 'react-router-dom';
import { Outlet } from 'react-router';
import { ChevronUp } from 'react-bootstrap-icons';
import classnames from 'classnames';

import { useRWD } from 'hooks';
import { useSelector } from 'store';
import {
  Footer,
  HeaderMain,
  HeaderBottomBar,
  HeaderTopBar,
  BottomMenu,
  MenuPopup
} from './components';

import styles from 'theme/components/layouts/MainLayout/Main.module.scss';

const Main = () => {
  const location = useLocation();
  const { profile } = useSelector((state) => state.auth);
  const { isMobileMenu } = useSelector((state) => state.ui);

  const { isMobile } = useRWD();

  // ref głównego kontentu aplikacji
  const scrollableContentRef = useRef<HTMLDivElement>(null);

  // czy jest widoczny przycisk powrotu na górę strony
  const [isBackToTopButton, setIsBackToTopButton] = useState(false);

  // funkcja skrolująca stronę do góry
  const scrollToTop = useCallback(
    () => scrollableContentRef.current?.scrollTo({ top: 0, left: 0, behavior: 'smooth' }),
    [scrollableContentRef.current]
  );

  // przeskrolowanie strony do góry przy przejściu między stronami
  useEffect(() => {
    scrollToTop();
  }, [scrollableContentRef.current, location.key]);

  return (
    <div
      className={classnames(
        styles.componentWrapper,
        'StylePath-Components-Layouts-MainLayout-Main'
      )}>
      {(!isMobile || location.pathname.split('/').filter(item => item).length<2) && (
        <header>
          <HeaderTopBar />
          <HeaderMain />
          <HeaderBottomBar />
        </header>
      )}

      <div
        ref={scrollableContentRef}
        onScroll={() =>
          scrollableContentRef.current?.scrollTop && scrollableContentRef.current?.scrollTop > 500
            ? setIsBackToTopButton(true)
            : setIsBackToTopButton(false)
        }
        // Ta klasa jest używana w innych komponentach - nie zmieniać!
        className={classnames('scrollableContent', styles.content)}>
        <main>
          <Outlet />
        </main>
        <footer>
          <Footer />
        </footer>
        <BottomMenu />
      </div>

      {isMobile && isMobileMenu && profile?.role !== 'ROLE_OPEN_PROFILE' && <MenuPopup />}

      {isBackToTopButton && (
        <button onClick={scrollToTop} className={styles.backToTop}>
          <ChevronUp />
        </button>
      )}
    </div>
  );
};

export default Main;
