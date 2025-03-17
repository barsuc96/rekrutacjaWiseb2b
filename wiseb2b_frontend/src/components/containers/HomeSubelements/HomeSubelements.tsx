import React, { useState } from 'react';
import classnames from 'classnames';

import { useRWD } from 'hooks';
import { useGetHomeSubelementsTypes, useGetHomeSubelementItems } from 'api';
import { ProductSlider } from 'components/containers';
import { Container, Loader } from 'components/controls';

import styles from 'theme/pages/Home/components/Tabs/Tabs.module.scss';

const HomeSubelements = () => {
  // Aktualnie wybrany typ subelementu
  const [activeSubelementId, setActiveSubelementId] = useState<number | null>(null);

  const { isMobile, isDesktop } = useRWD();

  // Pobranie typów subelementów
  const { data: homeSubelementsTypesData } = useGetHomeSubelementsTypes(
    {
      page: 1,
      limit: 999
    },
    {
      onSuccess: (data) => {
        // Ustawienie pierwszego typ subelementu jako wybrany
        setActiveSubelementId(data.items[0]?.id || null);
      }
    }
  );

  // Pobranie listy produktów po zmianie/wybraniu typu subelementu
  const { data: homeSubelementItemsData, isLoading: isHomeSubelementItemsLoading } =
    useGetHomeSubelementItems(
      activeSubelementId || 0,
      {
        page: 1,
        limit: 999
      },
      {
        enabled: !!activeSubelementId
      }
    );

  return (
    <div className={classnames(styles.wrapperComponent, 'StylePath-Pages-Home-components-Tabs')}>
      {homeSubelementsTypesData && (
        <div className={styles.productTabsWrapper}>
          <div className={styles.tabsBar}>
            <Container>
              <div className={styles.tabs}>
                {homeSubelementsTypesData.items.map((item) => (
                  <div
                    key={item.id}
                    className={classnames(styles.tab, {
                      [styles.active]: item.id === activeSubelementId
                    })}
                    onClick={() => {
                      setActiveSubelementId(item.id);
                    }}>
                    {item.name}
                  </div>
                ))}
              </div>
            </Container>
          </div>
          <Container>
            <div className={styles.sliderWrapper}>
              {isHomeSubelementItemsLoading && <Loader />}

              {(homeSubelementItemsData?.items.length || 0) > 0 && (
                <ProductSlider
                  products={homeSubelementItemsData?.items || []}
                  itemsPerSlide={isMobile ? 1 : isDesktop ? 4 : 6}
                />
              )}
            </div>
          </Container>
        </div>
      )}
    </div>
  );
};

export default HomeSubelements;
