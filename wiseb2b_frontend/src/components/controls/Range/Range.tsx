import React, { FC, useState, useEffect } from 'react';
import { Range as RangeInput } from 'react-range';
import qs from 'query-string';
import { Trans } from 'react-i18next';
import { ChevronDown, ChevronUp } from 'react-bootstrap-icons';
import Popper from '@mui/material/Popper';
import ClickAwayListener from '@mui/material/ClickAwayListener';
import remove from 'lodash/remove';

import { IProductsFilter } from 'api/types';
import { Button, Input } from 'components/controls';

import styles from 'theme/components/controls/Range/Range.module.scss';

interface IFilter {
  filter_id: string;
  filter_value: string;
  filter_type?: 'singlechoice' | 'multichoice' | string;
}

// typ danych wejściowych
interface IProps {
  queryFilters: IFilter[];
  onChange: (filters: IFilter[], currentFilter: string) => void;
  filter: IProductsFilter;
}

const Range: FC<IProps> = ({ filter, queryFilters, onChange }) => {
  const [values, setValues] = useState([
    parseInt(filter.values[0].value),
    parseInt(filter.values[1].value)
  ]);

  // element html z kalendarzami
  const [popoverAnchor, setPopoverAnchor] = useState<HTMLElement | null>(null);

  // ustawianie wartości domyślnych
  useEffect(() => {
    const rangeFilter = queryFilters.find((o) => o.filter_id === filter.id);

    if (rangeFilter) {
      const rangeFilterValues = rangeFilter.filter_value.split(';');
      setValues([parseInt(rangeFilterValues[0]), parseInt(rangeFilterValues[1])]);
    }
  }, []);

  const handleClick = (event: React.MouseEvent<HTMLElement>) => {
    setPopoverAnchor(popoverAnchor ? null : event.currentTarget);
  };

  const filterAttributes = qs.parse(window.location.search)?.filter_attributes as string;
  const selectedFilters = qs.parse(filterAttributes.replaceAll('|', '&'))?.[filter.id] as string;

  const isValidRange =
    values[0] > parseFloat(filter.values[0].value) &&
    values[1] < parseFloat(filter.values[1].value);

  const renderContent = () => (
    <div className={styles.content}>
      <div>
        <div className={styles.inputWrapper}>
          <Input
            value={values[0].toString()}
            onChange={(value) => {
              if (parseInt(value) > values[1]) {
                setValues(values);

                return;
              }
              setValues([parseInt(value || filter.values[0].value), values[1]]);
            }}
          />
          <div className={styles.inputDash} />
          <Input
            value={values[1].toString()}
            onChange={(value) => {
              if (parseInt(value) > parseInt(filter.values[1].value)) {
                setValues(values);

                return;
              }

              setValues([values[0], parseInt(value || filter.values[1].value)]);
            }}
          />
        </div>
        <div className={styles.rangeWrapper}>
          {isValidRange && (
            <RangeInput
              step={1}
              min={parseFloat(filter.values[0].value)}
              max={parseFloat(filter.values[1].value)}
              values={values}
              onChange={(values) => setValues(values)}
              renderTrack={({ props, children }: any) => (
                <div
                  {...props}
                  style={{
                    ...props.style,
                    height: '4px',
                    width: '100%',
                    backgroundColor: '#D9D9D9'
                  }}>
                  <div
                    style={{
                      width: `${
                        ((values[1] - values[0]) * 100) /
                        (parseInt(filter.values[1].value) - parseInt(filter.values[0].value))
                      }%`,
                      left: `${
                        ((values[0] - parseInt(filter.values[0].value)) * 100) /
                        (parseInt(filter.values[1].value) - parseInt(filter.values[0].value))
                      }%`
                    }}
                    className={styles.innerTrack}></div>
                  {children}
                </div>
              )}
              renderThumb={({ props }) => (
                <div
                  {...props}
                  style={{
                    ...props.style,
                    height: '15px',
                    width: '15px',
                    backgroundColor: '#D9D9D9',
                    borderRadius: '50%'
                  }}
                />
              )}
            />
          )}
        </div>
      </div>
      <div className={styles.buttonWrapper}>
        <Button
          ghost
          onClick={() => {
            onChange([...remove(queryFilters, (o) => o.filter_id !== filter.id)], filter.id);
            setPopoverAnchor(null);
          }}>
          <Trans>Wyczyść</Trans>
        </Button>
        <Button
          onClick={() => {
            onChange(
              [
                ...queryFilters.filter((assignedFilter) => assignedFilter.filter_id !== filter.id),
                {
                  filter_id: filter.id,
                  filter_value: `${values[0]};${values[1]}`,
                  filter_type: filter.type
                }
              ],
              filter.id
            );

            setPopoverAnchor(null);
          }}>
          <Trans>Zastosuj</Trans>
        </Button>
      </div>
    </div>
  );

  return (
    <div className={styles.wrapperComponent}>
      <button className={styles.label} onClick={handleClick}>
        <Trans>{filter.label}</Trans>
        {selectedFilters && (
          <span className={styles.fromTo}>
            <Trans>od</Trans>: {selectedFilters.split(';')[0]} <Trans>do</Trans>:{' '}
            {selectedFilters.split(';')[1]}
          </span>
        )}
        <div className={styles.chevron}>{popoverAnchor ? <ChevronUp /> : <ChevronDown />}</div>
      </button>
      {popoverAnchor && (
        <ClickAwayListener onClickAway={() => setPopoverAnchor(null)}>
          <Popper anchorEl={popoverAnchor} open placement="bottom-start">
            {renderContent()}
          </Popper>
        </ClickAwayListener>
      )}
    </div>
  );
};

export default Range;
