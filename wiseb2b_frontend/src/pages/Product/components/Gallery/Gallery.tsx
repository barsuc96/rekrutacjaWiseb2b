// galeria zdjęć

import React, { FC, useState } from 'react';
import classnames from 'classnames';
import Slider from 'react-slick';

import { IImage, IProductLabel } from 'api/types';
import { Label } from 'components/controls';

import styles from 'theme/pages/Product/components/Gallery/Gallery.module.scss';

// typ danych wejściowych
interface IProps {
  images: IImage[];
  labels: IProductLabel[];
}

const Gallery: FC<IProps> = ({ images, labels }) => {
  // index aktualnego zdjęcia (z tablicy zdjęć)
  const [currentImageIndex, setCurrentImageIndex] = useState(0);

  return (
    <div
      className={classnames(styles.wrapperComponent, 'StylePath-Pages-Product-components-Gallery')}>
      <div
        className={styles['main-image']}
        style={{
          backgroundImage: `url(${images[currentImageIndex]?.big || ''})`
        }}>
        {labels.map((label) => (
          <Label key={label.type} label={label} /> 
        ))}
      </div>

      {images.length > 1 && (
        <Slider slidesToShow={images.length > 4 ? 4 : images.length}>
          {images.map((image, index) => (
            <div className={styles.thumb} key={index} onClick={() => setCurrentImageIndex(index)}>
              <div
                className={classnames(styles.image, {
                  [styles.active]: index === currentImageIndex
                })}
                style={{ backgroundImage: `url(${image.thumb})` }}
              />
            </div>
          ))}
        </Slider>
      )}
    </div>
  );
};

export default Gallery;
