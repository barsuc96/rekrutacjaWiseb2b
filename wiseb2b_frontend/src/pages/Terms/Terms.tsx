// strona regulaminu

import React, { useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';
import classnames from 'classnames';

import { reduxActions, useDispatch } from 'store';
import { useGetTerms } from 'api';
import { IAgreement } from 'api/types';
import { PageTitle, Collapse } from 'components/controls';

import styles from 'theme/pages/Terms/Terms.module.scss';

const DashboardTerms = () => {
  const { t } = useTranslation();
  const dispatch = useDispatch();

  // pobranie danych regulaminu
  const { data: termsData } = useGetTerms();

  // Lista rozwiniętych sekcji
  const [activeSections, setActiveSections] = useState<string[]>(['']);

  useEffect(() => {
    dispatch(
      reduxActions.setBreadcrumbs([
        { name: t('Dashboard'), path: '/dashboard' },
        { name: t('Regulamin') }
      ])
    );
  }, []);

  const toggleSection = (sectionProperty: string) => {
    activeSections.includes(sectionProperty)
      ? setActiveSections((prevState) => prevState.filter((item) => item !== sectionProperty))
      : setActiveSections((prevState) => [...prevState, sectionProperty]);
  };

  const renderAgreement = (agreement: IAgreement) => {
    return (
      <Collapse
        title={agreement.header}
        open={activeSections.includes(agreement.header)}
        onClick={() => toggleSection(agreement.header)}>
        <h4>{agreement.sub_header}</h4>
        <div dangerouslySetInnerHTML={{ __html: agreement.content }} />
      </Collapse>
    );
  };

  return (
    <div className={classnames(styles.wrapperComponent, 'StylePath-Pages-Terms')}>
      <PageTitle title={t('Regulamin')} />

      <div className={styles.content}>
        {termsData?.user_agreement && renderAgreement(termsData.user_agreement)}
        {termsData?.user_coookies_agreement && renderAgreement(termsData.user_coookies_agreement)}
        {termsData?.user_data_processing_agreement &&
          renderAgreement(termsData.user_data_processing_agreement)}
        {termsData?.user_rodo_agreement && renderAgreement(termsData.user_rodo_agreement)}
      </div>
    </div>
  );
};

export default DashboardTerms;
