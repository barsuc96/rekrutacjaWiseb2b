// główny layout - dolna belka nagłówka

import React, { useRef, useState, useCallback } from 'react';
import { useTranslation } from 'react-i18next';
import { Grid } from '@mui/material';
import { ChevronDown, PatchCheck, PlusCircle, DashCircle } from 'react-bootstrap-icons';
import classnames from 'classnames';

import { useGetLayoutCategories } from 'api';
import { ICategoryListItem } from 'api/types';
import { useClickOutside, useAppNavigate } from 'hooks';
import { Container, Button, Link, Loader } from 'components/controls';

import styles from 'theme/components/layouts/MainLayout/components/HeaderBottomBar/HeaderBottomBar.module.scss';

const HeaderBottomBar = () => {
  const { t } = useTranslation();
  const navigate = useAppNavigate();

  // czy rozwinięty jest panel z kategoriami
  const [isPopupOpened, setIsPopupOpened] = useState(false);

  // panel z kategoriami
  const popupContentRef = useRef<HTMLDivElement>(null);

  // przycisk rozwijający panel z kategoriami
  const triggerRef = useRef<HTMLDivElement>(null);

  // zamykanięcie panelu z kategoriami po kliknięciu na zewnątrz
  useClickOutside([popupContentRef, triggerRef], () => setIsPopupOpened(false));

  // lista ID'ków rozwiniętych kategorii
  const [expandedCategoryIds, setExpandedCategoryIds] = useState<number[]>([]);

  // pobranie listy kategorii
  const { data: categoriesData, isLoading: isCategoriesLoading } = useGetLayoutCategories({
    page: 1,
    limit: 999,
    search_keyword: ''
  });

  // funkcja renderująca drzewo kategorii
  const renderCategoryTree = useCallback(
    (category: ICategoryListItem) => {
      // funkcja rekurencyjna renderująca  listę wraz z rozwiniętymi podkategoriami
      const renderTree = (categories: ICategoryListItem[] = [], level = 0) =>
        categories.map((category) => (
          <div
            key={category.id}
            className={classnames(styles.category, { [styles.main]: level === 0 })}>
            {category.subcategories_total_count > 0 && (
              <>
                {expandedCategoryIds.includes(category.id) ||
                category.subcategories.some((item) => item.id === undefined) ? (
                  <DashCircle
                    onClick={() =>
                      setExpandedCategoryIds((prevState) =>
                        prevState.filter((categoryId) => categoryId !== category.id)
                      )
                    }
                  />
                ) : (
                  <PlusCircle
                    onClick={() =>
                      setExpandedCategoryIds((prevState) => [...prevState, category.id])
                    }
                  />
                )}
              </>
            )}
            <Link
              to={`/${category.url_link}`}
              onClick={() => {
                setIsPopupOpened(false);
              }}
              className={classnames({ [styles.chosen]: category.id === undefined })}>
              {category.name} <span className={styles.counter}>({category.products_count})</span>
            </Link>

            {category.subcategories_total_count > 0 &&
              (expandedCategoryIds.includes(category.id) ||
                category.subcategories.some((item) => item.id === undefined)) &&
              renderTree(category.subcategories, level + 1)}
          </div>
        ));

      return renderTree([category]);
    },
    [expandedCategoryIds, undefined]
  );

  return (
    <div
      className={classnames(
        styles.componentWrapper,
        'StylePath-Components-Layouts-MainLayout-Components-HeaderBottomBar'
      )}>
      <Container>
        <div className={styles.bottomBar}>
          <div>
            <span ref={triggerRef} className={styles.categoriesWrapper}>
              <Button size="large" onClick={() => setIsPopupOpened(!isPopupOpened)}>
                <span className={styles.categoryButtonLabel}>
                  {t('Kategorie')} <ChevronDown />
                </span>
              </Button>
            </span>

            {categoriesData?.items.map(
              (category, i) =>
                !category.show_category_button && (
                  <Link
                    key={i}
                    to={`/${category.url_link}`}
                    className={classnames(styles.link, styles.big)}>
                    <PatchCheck /> {category.label}
                  </Link>
                )
            )}
          </div>
          <div>
            <Link
              to="#"
              className={styles.link}
              onClick={(event) => {
                event.preventDefault();
                navigate('/wip');
              }}>
              {t('Aktualności')}
            </Link>
            <Link
              to="#"
              className={styles.link}
              onClick={(event) => {
                event.preventDefault();
                navigate('/wip');
              }}>
              {t('Baza wiedzy')}
            </Link>
            <Link
              to="#"
              className={styles.link}
              onClick={(event) => {
                event.preventDefault();
                navigate('/wip');
              }}>
              {t('Do pobrania')}
            </Link>
          </div>
        </div>
      </Container>

      {isPopupOpened && (
        <div className={styles.categoriesPopup}>
          <div className={styles.content} ref={popupContentRef}>
            <Container>
              <div
                className={classnames(
                  styles.wrapperComponent,
                  'StylePath-Components-Layouts-MainLayout-Components-Categories'
                )}>
                {isCategoriesLoading && <Loader />}
                <Grid container>
                  {categoriesData?.items.map((category, i) => (
                    <>
                      {category.categories_list &&
                        category.show_category_button &&
                        category.categories_list.map((category) => (
                          <Grid item key={i} sm={Math.floor(12 / 4)}>
                            {renderCategoryTree(category)}
                          </Grid>
                        ))}
                    </>
                  ))}
                </Grid>
              </div>
            </Container>
          </div>
        </div>
      )}
    </div>
  );
};

export default HeaderBottomBar;
