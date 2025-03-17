// strona główna

import React, { useState, useEffect } from 'react';
import classnames from 'classnames';
import { Helmet } from 'react-helmet';
import { useTranslation, Trans } from 'react-i18next';
import each from 'lodash/each';

import { reduxActions, useDispatch } from 'store';
import { useGetContract, usePostContractAgreementToggle } from 'api';

import { HtmlBlock } from 'components/containers/HtmlBlock';

import { Container, Modal, Button, Checkbox } from 'components/controls';

import styles from 'theme/pages/Home/Home.module.scss';

const Home = () => {
  const { t } = useTranslation();
  const { i18n } = useTranslation();
  const dispatch = useDispatch();

  // wybrane zgody
  const [selectedContractIds, setSelectedContractIds] = useState<number[]>([]);

  const [isModalVisible, setIsModalVisible] = useState(true);

  useEffect(() => {
    dispatch(reduxActions.setSearchFilterCategory({ id: null, name: '', url_link: null }));
  }, []);

  const { data: contractData } = useGetContract({
    page: 1,
    limit: 999,
    context: 'HOME_PAGE',
    currentContext: 'HOME_PAGE',
    onlyMustAccept: true
  });

  // zmiana zgód
  const { mutate: updateContractAgreement } = usePostContractAgreementToggle({
    onSuccess: () => {
      setIsModalVisible(false);
    }
  });

  // ustawienie aktywnych zgód
  useEffect(() => {
    if (contractData) {
      const contractIds: number[] = [];
      each(contractData?.items, (contract) => {
        if (contract.has_active_agree) {
          contractIds.push(contract.id);
        }
      });

      setSelectedContractIds(contractIds);
    }
  }, [contractData]);

  const supportedLangs = i18n.options.supportedLngs || [];

  const handleContractClick = (contractId: number) => {
    if (selectedContractIds.some((item) => item === contractId)) {
      setSelectedContractIds((prevState) => prevState.filter((id) => id !== contractId));
      return;
    }

    setSelectedContractIds((prevState) => [...prevState, contractId]);
  };

  const handleUpdateContractAgreement = () => {
    if (contractData) {
      const items = contractData.items.map((contract) => ({
        contractId: contract.id,
        contextAgreement: contract.contexts,
        isAgree: !!selectedContractIds.find((contractId) => contractId === contract.id)
      }));

      updateContractAgreement({ items });
    }
  };

  const renderContrants = () =>
    contractData?.items.map((contract, i) => (
      <div key={i}>
        <div>
          <b>{contract.name}</b>
        </div>
        <div>
          <Checkbox
            onClick={() => handleContractClick(contract.id)}
            checked={!!selectedContractIds.find((id) => id === contract.id)}
          />{' '}
          <span dangerouslySetInnerHTML={{ __html: contract.testimony }} />{' '}
          {contract.user_must_accept && <b>*</b>}
        </div>
        {contract.content && (
          <div
            className={styles.consentContent}
            dangerouslySetInnerHTML={{ __html: contract.content }}
          />
        )}
      </div>
    ));

  return (
    <div className={classnames(styles.wrapperComponent, 'StylePath-Pages-Home')}>
      <Helmet>
        <title>WiseB2B</title>
        <link rel="canonical" href={window.location.href} />
        {supportedLangs.map((language, i) => {
          if (language !== 'cimode')
            return (
              <link
                key={i}
                rel="alternate"
                href={`${window.location.origin}/${language}`}
                hrefLang={language}
              />
            );
        })}
      </Helmet>
      <Container>
        <HtmlBlock sectionId="HOMEPAGE" />
      </Container>
      {contractData && !!contractData.items.length && isModalVisible && (
        <Modal title={t('Zgody')}>
          {renderContrants()}
          {contractData.items.some((contract) => contract.user_must_accept) && (
            <div className={styles.consentRequired}>
              <b>*</b> - <Trans>Zgoda Wymagana</Trans>
            </div>
          )}
          <div className={styles.consentActions}>
            <Button onClick={handleUpdateContractAgreement}>
              <Trans>Zapisz</Trans>
            </Button>
          </div>
        </Modal>
      )}
    </div>
  );
};

export default Home;
