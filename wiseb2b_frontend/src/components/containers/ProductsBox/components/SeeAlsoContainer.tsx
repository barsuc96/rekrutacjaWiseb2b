import { useGetProducts } from 'api';
import { ProductItem } from 'components/containers';
import { Loader } from 'components/controls';
import { useRWD } from 'hooks';
import React from 'react';

type Props = {
  categoryId?: number;
};

const SeeAlsoContainer = ({ categoryId }: Props) => {
  const { isMobile } = useRWD();

  const { data: productsData, isLoading: isLoadingProducts } = useGetProducts({
    category_id: categoryId
  });

  return (
    <div>
      {isLoadingProducts && <Loader />}
      {productsData?.items.map((product) => {
        return <ProductItem minimalVariant={isMobile} key={product.id} product={product} />;
      })}
    </div>
  );
};

export default SeeAlsoContainer;
