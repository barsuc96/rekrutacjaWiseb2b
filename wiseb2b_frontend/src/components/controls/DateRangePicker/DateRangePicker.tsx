// selektor zakresu dat

import React, { FC, useEffect, useState } from 'react';
import { Trans } from 'react-i18next';
import { parseISO, format } from 'date-fns';
import { DateRange, DayPicker } from 'react-day-picker';
import Popover from '@mui/material/Popover';
import { Calendar } from 'react-bootstrap-icons';
import classnames from 'classnames';

import { useRWD } from 'hooks';

import styles from 'theme/components/controls/DateRangePicker/DateRangePicker.module.scss';
import 'react-day-picker/dist/style.css';

// typ danych wejściowych
interface IProps {
  dateRange?: {
    from?: string;
    to?: string;
  };
  onChange?: (dateRange: NonNullable<Required<IProps['dateRange']>>) => void;
}

const DateRangePicker: FC<IProps> = ({ dateRange, onChange }) => {
  const { isMobile } = useRWD();

  // czy zmiana zakresu jest w trakcie (pierwsze kliknięcie)
  const [isNewSelection, setIsNewSelection] = useState(true);

  // lokalny zakres dat
  const [range, setRange] = useState<DateRange>();

  // element html z kalendarzami
  const [popoverAnchor, setPopoverAnchor] = useState<HTMLElement | null>(null);

  // ustawienie lokalnego zakresu dat przy zmianie danych z props'ów
  useEffect(() => {
    setDateRangeFromProps();
  }, [dateRange]);

  // funkcja ustawiająca lokalnego zakresu dat na dane z propsów
  const setDateRangeFromProps = () => {
    const rangeFrom = dateRange?.from;
    const rangeTo = dateRange?.to;

    setRange({
      from: rangeFrom ? parseISO(rangeFrom) : undefined,
      to: rangeTo ? parseISO(rangeTo) : undefined
    });
  };

  // fonkcja wykonywana po wybraniu daty
  const handleOnSelectDate = (range?: DateRange, selectedDate?: Date) => {
    if (isNewSelection || !range) {
      setRange({
        from: selectedDate
      });
      setIsNewSelection(false);
    } else {
      setRange(range);
      setIsNewSelection(true);
      setPopoverAnchor(null);
      range.from &&
        range.to &&
        onChange?.({
          from: format(range.from, 'yyyy-MM-dd'),
          to: format(range.to, 'yyyy-MM-dd')
        });
    }
  };

  const handleOnClosePopover = () => {
    setPopoverAnchor(null);
    setDateRangeFromProps();
  };

  return (
    <>
      <button
        onClick={({ currentTarget }) => setPopoverAnchor(currentTarget)}
        className={classnames(styles.button, 'StylePath-Components-Controls-DateRangePicker')}>
        {range?.from && range?.to ? (
          <span>
            {format(range.from, 'dd.MM.yyyy')}
            {range.to && ` - ${format(range.to, 'dd.MM.yyyy')}`}
          </span>
        ) : (
          <span className={styles.placeholder}>
            <Trans>Wybierz zakres dat</Trans>
          </span>
        )}
        <Calendar />
      </button>
      <Popover
        anchorEl={popoverAnchor}
        open={!!popoverAnchor}
        onClose={handleOnClosePopover}
        anchorOrigin={{
          vertical: 'bottom',
          horizontal: 'center'
        }}
        transformOrigin={{
          vertical: 'top',
          horizontal: 'center'
        }}>
        <DayPicker
          mode="range"
          numberOfMonths={isMobile ? 1 : 2}
          selected={range}
          onSelect={handleOnSelectDate}
        />
      </Popover>
    </>
  );
};

export default DateRangePicker;
