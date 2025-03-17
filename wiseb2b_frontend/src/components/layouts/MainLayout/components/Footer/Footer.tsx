// stopka głównego layoutu

import React, { useState } from 'react';
import { Trans } from 'react-i18next';
import { ChevronDown } from 'react-bootstrap-icons';
import classnames from 'classnames';

import { Container } from 'components/controls';
import { Link } from 'components/controls';

import facebookLogo from 'assets/facebook.svg';
import googleLogo from 'assets/google.svg';
import instagramLogo from 'assets/instagram.svg';
import mapImage from 'assets/footer-map.png';
import senteLogo from 'assets/logo-sente.svg';
import mastercardLogo from 'assets/mastercard.svg';
import paypalLogo from 'assets/paypal.svg';
import pinterestLogo from 'assets/pinterest.svg';
import visaLogo from 'assets/visa.svg';

import styles from 'theme/components/layouts/MainLayout/components/Footer/Footer.module.scss';

const Footer = () => {
  const [expandedSections, setExpandedSections] = useState<string[]>([]);

  const toggleSection = (section: string) =>
    setExpandedSections((prevState) =>
      prevState.includes(section)
        ? prevState.filter((item) => item !== section)
        : [...prevState, section]
    );

  return (
    <div
      className={classnames(
        styles.componentWrapper,
        'StylePath-Components-Layouts-MainLayout-Components-Footer'
      )}
      itemScope
      itemType="http://schema.org/Organization">
      <meta itemProp="url" content={window.location.href} />
      <Container>
        <div className={styles.contentWrapper}>
          <div>
            <section>
              <span
                onClick={() => toggleSection('info')}
                className={classnames(styles.title, {
                  [styles.open]: expandedSections.includes('info')
                })}>
                <Trans>Informacje</Trans>
                <ChevronDown />
              </span>
              <ul className={classnames({ [styles.hidden]: !expandedSections.includes('info') })}>
                <li>
                  <Link to="/wip">
                    <Trans>O nas</Trans>
                  </Link>
                </li>
                <li>
                  <Link to="/wip">
                    <Trans>Regulamin</Trans>
                  </Link>
                </li>
                <li>
                  <Link to="/wip">
                    <Trans>Polityka prywatności</Trans>
                  </Link>
                </li>
                <li>
                  <Link to="/wip">
                    <Trans>Nasi partnerzy</Trans>
                  </Link>
                </li>
              </ul>
            </section>
            <section>
              <span
                onClick={() => toggleSection('support')}
                className={classnames(styles.title, {
                  [styles.open]: expandedSections.includes('support')
                })}>
                <Trans>Obsługa klienta</Trans>
                <ChevronDown />
              </span>
              <ul
                className={classnames({ [styles.hidden]: !expandedSections.includes('support') })}>
                <li>
                  <Link to="/wip">
                    <Trans>Zwroty i reklamacje</Trans>
                  </Link>
                </li>
                <li>
                  <Link to="/wip">
                    <Trans>Sposoby dostawy</Trans>
                  </Link>
                </li>
                <li>
                  <Link to="/wip">
                    <Trans>Czas realizacji zamówienia</Trans>
                  </Link>
                </li>
                <li>
                  <Link to="/wip">
                    <Trans>Dostępność towarów</Trans>
                  </Link>
                </li>
                <li>
                  <Link to="/wip">
                    <Trans>Gwarancja</Trans>
                  </Link>
                </li>
              </ul>
            </section>
            <section>
              <span
                onClick={() => toggleSection('contact')}
                className={classnames(styles.title, {
                  [styles.open]: expandedSections.includes('contact')
                })}>
                <Trans>Kontakt</Trans>
                <ChevronDown />
              </span>
              <ul
                className={classnames({ [styles.hidden]: !expandedSections.includes('contact') })}>
                <meta itemProp="name" content="Sente" />
                <li itemProp="telephone">
                  <Trans>Nr telefonu</Trans> +48 648 538 662
                </li>
                <li itemProp="location">
                  <Trans>Adres siedziby</Trans>:
                  <br />
                  Skierniewicka 12,
                  <br />
                  50-119 Wrocław
                </li>
                <li itemProp="vatID">NIP: 653-001-77-68</li>
              </ul>
            </section>
          </div>

          <div>
            <section>
              <span
                onClick={() => toggleSection('departments')}
                className={classnames(styles.title, {
                  [styles.open]: expandedSections.includes('departments')
                })}>
                <Trans>Nasze oddziały</Trans>
                <ChevronDown />
              </span>
              <ul className={styles.hidden}>
                <li>Nowy Jork</li>
                <li>Londyn</li>
                <li>Warszawa</li>
                <li>Sydney</li>
                <li>Berlin</li>
              </ul>
            </section>

            <div
              className={classnames(styles.mapWrapper, {
                [styles.hidden]: !expandedSections.includes('departments')
              })}>
              <img src={mapImage} alt="map" />
            </div>
          </div>
        </div>
      </Container>

      <div className={styles.bottomBar}>
        <Container>
          <div className={styles.contentWrapper}>
            <div className={styles.lists}>
              <section>
                <Trans>Rodzaje płatności</Trans>
                <span className={styles.list}>
                  <img src={visaLogo} alt="visa" />
                  <img src={mastercardLogo} alt="mastercard" />
                  <img src={paypalLogo} alt="paypal" />
                </span>
              </section>
              <section>
                <Trans>Obserwuj nas na</Trans>
                <span className={styles.list}>
                  <Link to="/wip">
                    <img src={facebookLogo} alt="facebook" />
                  </Link>
                  <Link to="/wip">
                    <img src={googleLogo} alt="google" />
                  </Link>
                  <Link to="/wip">
                    <img src={instagramLogo} alt="instagram" />
                  </Link>
                  <Link to="/wip">
                    <img src={pinterestLogo} alt="pinterest" />
                  </Link>
                </span>
              </section>
            </div>

            <section className={styles.powered}>
              powered by
              <a href="https://sente.pl" target="_blank" rel="noreferrer">
                <img src={senteLogo} alt="logo Sente" />
                <meta itemProp="logo" content={senteLogo} />
              </a>
            </section>
          </div>
        </Container>
      </div>
    </div>
  );
};

export default Footer;
