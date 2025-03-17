// strona eksportu danych

import React, { useEffect, useMemo, useState } from 'react';
import classnames from 'classnames';
import { PlusCircle, DashCircle } from 'react-bootstrap-icons';
import { Trans, useTranslation } from 'react-i18next';

import { reduxActions, useDispatch } from 'store';
import { useGetExportCategories } from 'api';
import { ICategoryListItem } from 'api/types';
import { PageTitle, Button, Modal } from 'components/controls';
import { ExportForm, PeriodicList } from './components';
import { useRWD } from 'hooks';

import styles from 'theme/pages/Export/Export.module.scss';

const Export = () => {
  const { t } = useTranslation();
  const dispatch = useDispatch();
  const { isMobile } = useRWD();

  // lista rozwiniętych kategorii (IDki)
  const [expandedCategoryIds, setExpandedCategoryIds] = useState<number[]>([]);

  // wybrana kategoria
  const [chosenCategory, setChosenCategory] = useState<Partial<ICategoryListItem> | null>(null);

  // czy jest modal z listą eksportów okresowych
  const [isPeriodicListModal, setIsPeriodicListModal] = useState(false);

  // pobranie listy kategorii
  const { data: categoriesData } = useGetExportCategories();

  // ustawienie breadcrumbs'ów na start strony
  useEffect(() => {
    dispatch(
      reduxActions.setBreadcrumbs([
        { name: t('Dashboard'), path: '/dashboard' },
        { name: t('Export danych') }
      ])
    );
  }, []);

  // kategorie gotowe do wyświetlenia
  const categories = useMemo(() => {
    const renderCategories = (items: ICategoryListItem[], level = 0) =>
      (items || []).map((item) => (
        <div key={item.id} className={classnames(styles.category, { [styles.main]: level === 0 })}>
          {item.subcategories_total_count > 0 && (
            <>
              {expandedCategoryIds.includes(item.id) ? (
                <DashCircle
                  onClick={() =>
                    setExpandedCategoryIds((prevState) =>
                      prevState.filter((categoryId) => categoryId !== item.id)
                    )
                  }
                />
              ) : (
                <PlusCircle
                  onClick={() => setExpandedCategoryIds((prevState) => [...prevState, item.id])}
                />
              )}
            </>
          )}
          <button onClick={() => setChosenCategory(item)}>{item.name}</button>

          {item.subcategories_total_count > 0 &&
            expandedCategoryIds.includes(item.id) &&
            renderCategories(item.subcategories, level + 1)}
        </div>
      ));

    return renderCategories(categoriesData?.items || []);
  }, [categoriesData, expandedCategoryIds]);

  return (
    <div className={classnames(styles.wrapperComponent, 'StylePath-Pages-Export')}>
      <PageTitle title={t('Export danych')} />

      <div className={styles.body}>
        <div className={styles.title}>
          <Trans>Wybierz ofertę produktów do wygenerowania</Trans>
        </div>

        <div className={styles.categories}>{categories}</div>

        <div className={styles.buttons}>
          <Button onClick={() => setIsPeriodicListModal(true)} color="secondary" ghost>
            <Trans>Lista ustawień cyklicznego eksportu</Trans>
          </Button>

          <Button color="secondary" onClick={() => setChosenCategory({ name: t('Pełna oferta') })}>
            <Trans>Generuj pełną ofertę produktów</Trans>
          </Button>
        </div>
      </div>

      {isPeriodicListModal && (
        <Modal
          onClose={() => setIsPeriodicListModal(false)}
          title={t('Lista ustawień cyklicznego eksportu')}>
          <PeriodicList />
        </Modal>
      )}

      {!!chosenCategory && (
        <Modal
          fullScreen={isMobile}
          onClose={() => setChosenCategory(null)}
          title={`${t('Export produktów z')} "${chosenCategory.name}"`}>
          <ExportForm categoryId={chosenCategory.id} />
        </Modal>
      )}
    </div>
  );
};

export default Export;
