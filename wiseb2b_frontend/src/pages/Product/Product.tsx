// Strona pojedynczego produktu

import React, { useEffect, useMemo, useRef, useState } from 'react';
import { Helmet } from 'react-helmet';
import { useTranslation } from 'react-i18next';
import { useParams, useLocation } from 'react-router-dom';
import classnames from 'classnames';
import animateScrollTo from 'animated-scroll-to';
import { Grid, Tabs, Tab } from '@mui/material';

import { useRWD } from 'hooks';
import {
  useGetProduct,
  useGetProductSubelementsTypes,
  useGetProductSubelementItems,
  useGetProductBreadcrumbs
} from 'api';
import { IProductsBreadcrumbsRequest } from 'api/types';
import { reduxActions, useDispatch, useSelector } from 'store';
import { Container, Breadcrumbs, Loader, Collapse } from 'components/controls';
import { AddToCartButton, ProductSlider, Search } from 'components/containers';
import {
  Details,
  Prices,
  Gallery,
  AttributeList,
  OthersAlsoBought,
  LastViewed
} from './components';

import 'pure-react-carousel/dist/react-carousel.es.css';
import styles from 'theme/pages/Product/Product.module.scss';

const Product = ({ id }: { id?: string }) => {
  const { t } = useTranslation();
  const dispatch = useDispatch();
  const { profile } = useSelector((state) => state.auth);

  const { isMobile, isDesktop } = useRWD();

  // referencja do paska nawigacji
  const navWrapperRef = useRef<HTMLDivElement>(null);

  // referencja do sekcji pełny opis
  const fullDescriptionRef = useRef<HTMLDivElement>(null);

  // referencja do sekcji z technicznymi atrybutami
  const technicalAttributesRef = useRef<HTMLDivElement>(null);

  // referencja do sekcji z informacjami o dostawie
  const logisticAttributesRef = useRef<HTMLDivElement>(null);

  // referencja do sekcji z plikami
  const filesRef = useRef<HTMLDivElement>(null);

  // referencja do sekcji z subelementami
  const subelementsRef = useRef<HTMLDivElement>(null);

  // ID produktu (przekształcony na int)
  const { id: paramId } = useParams();
  const productId = useMemo(() => parseInt(paramId || id || ''), [id]);

  // ID kategorii lub fraza wyszukiwania potrzebne jako param w breadcrumbs
  const { state } = useLocation();
  const { categoryId, searchKeywords } = (state || {}) as IProductsBreadcrumbsRequest;

  // Aktualnie wybrany tab (główna nawigacja)
  const [activeTab, setActiveTab] = useState<string | number>('id');

  // Aktualnie wybrany typ subelementu
  const [activeSubelementId, setActiveSubelementId] = useState<number | null>(null);

  // Lista rozwiniętych sekcji
  const [activeSections, setActiveSections] = useState<string[]>(['description_full']);

  // czy jest nawigacja w trakcie sticky
  const [isStickyNav, setIsStickyNav] = useState(false);

  // Pobranie produktu
  const { data: productData, isLoading: isProductLoading } = useGetProduct(productId, {
    enabled: !!productId
  });

  // Pobranie typów subelementów
  const { data: productSubelementsTypesData } = useGetProductSubelementsTypes(
    productId,
    {
      page: 1,
      limit: 999
    },
    {
      enabled: !!productId,
      onSuccess: (data) => {
        // Ustawienie pierwszego typ subelementu jako wybrany
        setActiveSubelementId(data.items[0]?.id);
      }
    }
  );

  // Pobranie listy produktów po zmianie/wybraniu typu subelementu
  const { data: productSubelementItemsData, isLoading: isProductSubelementItemsLoading } =
    useGetProductSubelementItems(
      productId,
      activeSubelementId || 0,
      {
        page: 1,
        limit: 999
      },
      {
        enabled: !!productId && !!activeSubelementId
      }
    );

  // Pobranie listy breadcrumbs'ów danego produktu
  const { data: productBreadcrumbsData } = useGetProductBreadcrumbs(
    productId,
    {
      ...(categoryId ? { categoryId } : {}),
      ...(searchKeywords ? { searchKeywords } : {})
    },
    {
      enabled: !!productId
    }
  );

  // Ustawienie breadcrums'ów do wyświetlenia JCZ: w jakim momencie ? Po załadowaniu danych ?
  useEffect(() => {
    dispatch(
      reduxActions.setBreadcrumbs(
        productBreadcrumbsData
          ? productBreadcrumbsData.items.map((item) => ({
              name: item.name,
              path: item.url_link ? `/${item.url_link}` : undefined
            }))
          : [
              {
                name: 'Produkty',
                path: '/products'
              },
              {
                name: productData?.title || 'Produkt'
              }
            ]
      )
    );
  }, [productData, productBreadcrumbsData]);

  // ustawianie informacji czy navigacyjny ma być sticky (przyklejony do headera)
  useEffect(() => {
    const observer = new IntersectionObserver(([e]) => setIsStickyNav(e.intersectionRatio < 1), {
      threshold: [1]
    });

    navWrapperRef.current && observer.observe(navWrapperRef.current);

    return () => {
      navWrapperRef.current && observer.unobserve(navWrapperRef.current);
    };
  }, [navWrapperRef.current]);

  // Zwinięcie/Rozwinięcie sekcji
  // Przy rozwinięciu zaznaczenie odpowiedniego tab'a w pasku nawigacji
  const toggleSection = (sectionProperty: string) => {
    activeSections.includes(sectionProperty)
      ? setActiveSections((prevState) => prevState.filter((item) => item !== sectionProperty))
      : (setActiveSections((prevState) => [...prevState, sectionProperty]),
        setActiveTab(sectionProperty));
  };

  // Elementy głównej nawigacji
  const navTabs: { property: string | number; label: string; collapse?: boolean; tab?: boolean }[] =
    useMemo(
      () => [
        { property: 'id', label: t('Produkt') },
        { property: 'description_full', label: t('Pełny opis'), collapse: true },
        { property: 'technical_attributes', label: t('Dane techniczne'), collapse: true },
        { property: 'logistic_attributes', label: t('Dane logistyczne'), collapse: true },
        { property: 'files', label: t('Do pobrania'), collapse: true },
        ...(productSubelementsTypesData?.items.map((subelement) => ({
          property: subelement.id,
          label: t(subelement.name),
          tab: true
        })) || [])
      ],
      [productSubelementsTypesData]
    );

  // pobranie referencji do sekcji na podstawie nazwy pola w produkcie
  const getRefElement = (elementName: string) =>
    elementName === 'description_full'
      ? fullDescriptionRef
      : elementName === 'technical_attributes'
      ? technicalAttributesRef
      : elementName === 'logistic_attributes'
      ? logisticAttributesRef
      : elementName === 'files'
      ? filesRef
      : elementName === 'subelements'
      ? subelementsRef
      : null;

  // funkcja odpowiedzialna za przeskrolowanie strony do odpowiedniej sekcji
  const scrollToElement = (elementName: string) => {
    const element = getRefElement(elementName)?.current;

    element
      ? animateScrollTo(element, {
          elementToScroll: document.getElementsByClassName('scrollableContent')[0],
          verticalOffset: isStickyNav ? -110 : -48
        })
      : animateScrollTo(0, {
          elementToScroll: document.getElementsByClassName('scrollableContent')[0]
        });

    setActiveSections([...activeSections, elementName]);
  };

  return (
    <div
      className={classnames(styles.wrapperComponent, 'StylePath-Pages-Product')}
      itemScope
      itemType="http://schema.org/Product">
      {productData && (
        <Helmet>
          <title>{productData.title}</title>
          <link rel="canonical" href={window.location.href} />
        </Helmet>
      )}
      <div className={styles.searchWrapper}>
        <Search />
      </div>
      <Breadcrumbs />

      {isProductLoading && <Loader />}

      {productData && (
        <>
          <div className={styles.productDetailsWrapper}>
            <Container>
              <Grid container>
                <Grid item md={3} xs={12}>
                  <Gallery images={productData.images} labels={productData.labels} />
                </Grid>

                <Grid item md={5} xs={12}>
                  <Details
                    product={productData}
                    scrollToDescription={() => {
                      setActiveTab('description_full');
                      setActiveSections([...activeSections, 'description_full']);
                      scrollToElement('description_full');
                    }}
                  />
                </Grid>

                <Grid item md={4} xs={12}>
                  <Prices product={productData} />
                </Grid>
              </Grid>
            </Container>
          </div>

          <div className={styles.productNavWrapper} ref={navWrapperRef}>
            {isStickyNav && (
              <div className={styles.productInfoBarWrapper}>
                <Container>
                  <div className={styles.productInfoBar}>
                    <div className={styles.info}>
                      <div
                        className={styles.image}
                        style={{ backgroundImage: `url('${productData.images[0].thumb}')` }}
                      />
                      <span itemProp="name" className={styles.title}>
                        {productData.title}
                      </span>
                    </div>

                    <div className="addToCartSmallButtonWrapper">
                      <AddToCartButton
                        quantity={1}
                        unitId={productData.units[0]?.unit_id}
                        productId={productData.id}
                        disabled={profile?.role === 'ROLE_OPEN_PROFILE'}
                      />
                    </div>
                  </div>
                </Container>
              </div>
            )}

            <Container>
              <div className={styles.tabsWrapper}>
                <Tabs
                  value={activeTab}
                  onChange={(event, property) => {
                    setActiveTab(property);
                    typeof property === 'number'
                      ? (setActiveSubelementId(property), scrollToElement('subelements'))
                      : (setActiveSections([...activeSections, property]),
                        scrollToElement(property));
                  }}>
                  {navTabs.map((section) => (
                    <Tab
                      label={section.label}
                      key={section.property}
                      className="item"
                      value={section.property}
                    />
                  ))}
                </Tabs>
              </div>
            </Container>
          </div>

          <div className={styles.othersAlsoBoughtWrapper}>
            <Container>
              <OthersAlsoBought productId={productId} />
            </Container>
          </div>

          <div className={styles.productExtendedWrapper}>
            {navTabs
              .filter((item) => item.collapse)
              .map((item) => (
                <div
                  ref={getRefElement(typeof item.property === 'string' ? item.property : '')}
                  key={item.property}>
                  <Collapse
                    open={activeSections.includes(item.property.toString())}
                    title={item.label}
                    onClick={() => toggleSection(item.property.toString())}>
                    {item.property === 'description_full' && (
                      <div
                        className={styles.fullDescription}
                        dangerouslySetInnerHTML={{ __html: productData.description_full }}
                      />
                    )}
                    {item.property === 'technical_attributes' && (
                      <AttributeList attributes={productData.technical_attributes} />
                    )}
                    {item.property === 'logistic_attributes' && (
                      <AttributeList attributes={productData.logistic_attributes} />
                    )}
                    {item.property === 'files' && <AttributeList attributes={productData.files} />}
                  </Collapse>
                </div>
              ))}
          </div>

          {productSubelementsTypesData && (
            <div className={styles.productTabsWrapper} ref={subelementsRef}>
              <div className={styles.tabsBar}>
                <Container>
                  <div className={styles.tabs}>
                    {productSubelementsTypesData.items.map((item) => (
                      <div
                        key={item.id}
                        className={classnames(styles.tab, {
                          [styles.active]: item.id === activeSubelementId
                        })}
                        onClick={() => {
                          setActiveSubelementId(item.id);
                          setActiveTab(item.id);
                        }}>
                        {item.name}
                      </div>
                    ))}
                  </div>
                </Container>
              </div>
              <Container>
                <div className={styles.sliderWrapper}>
                  {isProductSubelementItemsLoading && <Loader />}

                  {productSubelementItemsData && productSubelementItemsData.items.length > 0 && (
                    <ProductSlider
                      products={productSubelementItemsData.items}
                      itemsPerSlide={isMobile ? 1 : isDesktop ? 4 : 6}
                    />
                  )}
                </div>
              </Container>
            </div>
          )}

          <div className={styles.lastViewedWrapper}>
            <Container>
              <LastViewed productId={productId} />
            </Container>
          </div>
        </>
      )}
    </div>
  );
};

export default Product;
