import React from 'react';

import { Counter } from 'components/controls';
import { ICartPositionListItem } from 'api/types';
import {
  useGetCartPositions,
  usePutCartPosition,
  usePostCartPositionQuantityIncrement,
  usePostCartPositionQuantityDecrement
} from 'api';

type Props = {
  cartId: number;
  queryParams: object;
  item: ICartPositionListItem;
  onChange?: () => void;
};

const QuantityWrapper = ({ item, cartId, queryParams, onChange }: Props) => {
  // pobranie listy pozycji koszyka
  const { refetch: refetchCartPositions } = useGetCartPositions(cartId, queryParams, {
    enabled: !!cartId
  });

  // aktualizacja konkretnej pozycji (zmiana ilości)
  const { mutate: updatePosition, isLoading: isQtyChangeLoading } = usePutCartPosition(cartId);

  // zwiększenie ilości konkretnej pozycji
  const { mutate: increaseQuantity, isLoading: isQtyIncrementLoading } =
    usePostCartPositionQuantityIncrement(cartId);

  // zmniejszenie ilości konkretnej pozycji
  const { mutate: decreaseQuantity, isLoading: isQtyDecrementLoading } =
    usePostCartPositionQuantityDecrement(cartId);

  // funkcja wrapująca aktualizację ilości
  const handleChangeQuantity = (
    position: ICartPositionListItem,
    newValue: number | null,
    isIncrement?: boolean
  ) => {
    const options = {
      onSuccess: () => {
        onChange?.();
        refetchCartPositions();
      }
    };

    newValue === null
      ? isIncrement
        ? increaseQuantity(
            {
              positionId: position.id
            },
            options
          )
        : decreaseQuantity(
            {
              positionId: position.id
            },
            options
          )
      : updatePosition(
          {
            positionId: position.id,
            quantity: newValue,
            unit_id: position.unit_id
          },
          options
        );
  };

  return (
    <Counter
      onChange={(value) => handleChangeQuantity(item, value)}
      onIncrease={() => handleChangeQuantity(item, null, true)}
      onDecrease={() => handleChangeQuantity(item, null, false)}
      value={item.quantity}
      disabled={isQtyChangeLoading || isQtyIncrementLoading || isQtyDecrementLoading}
    />
  );
};

export default QuantityWrapper;
