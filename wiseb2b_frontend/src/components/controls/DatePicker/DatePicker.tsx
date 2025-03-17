// selektor daty

import React, { FC, useEffect, useState, MouseEvent, ChangeEvent } from 'react';
import { Trans } from 'react-i18next';
import { parseISO, format, setHours, setMinutes } from 'date-fns';
import { DayPicker } from 'react-day-picker';
import Popover from '@mui/material/Popover';
import { Calendar } from 'react-bootstrap-icons';
import classnames from 'classnames';

import { CloseIcon } from 'assets/icons';

import styles from 'theme/components/controls/DatePicker/DatePicker.module.scss';
import 'react-day-picker/dist/style.css';

// typ danych wejściowych
interface IProps {
  date?: string;
  onChange?: (date: string | null) => void;
  clearable?: boolean;
  isTimePicker?: boolean;
}

const DatePicker: FC<IProps> = ({ date, onChange, clearable, isTimePicker }) => {
  // lokalna data
  const [localDate, setLocalDate] = useState<Date>();

  //  lokalny czas
  const [timeValue, setTimeValue] = useState<string>(
    date ? format(new Date(date), 'HH:mm') : '00:00'
  );

  // element html z kalendarzami
  const [popoverAnchor, setPopoverAnchor] = useState<HTMLElement | null>(null);

  // ustawienie lokalnej daty przy zmianie danych z props'ów
  useEffect(() => {
    setTimeValue(date ? format(new Date(date), 'HH:mm') : '00:00');
    setDateFromProps();
  }, [date]);

  // funkcja ustawiająca lokalnej daty na dane z propsów
  const setDateFromProps = () => {
    setLocalDate(date ? parseISO(date) : undefined);
  };

  // fonkcja wykonywana po wybraniu daty
  const handleOnSelectDate = (selectedDate: Date) => {
    if (isTimePicker) {
      const [hours, minutes] = timeValue.split(':').map((str) => parseInt(str, 10));
      const newSelectedDate = setHours(setMinutes(selectedDate || new Date(), minutes), hours);
      setLocalDate(newSelectedDate);
      onChange?.(format(newSelectedDate, 'yyyy-MM-dd HH:mm'));
    } else {
      setLocalDate(selectedDate);
      onChange?.(format(selectedDate, 'yyyy-MM-dd'));
    }

    setPopoverAnchor(null);
  };

  const handleOnClosePopover = () => {
    setPopoverAnchor(null);
    isTimePicker && localDate
      ? onChange?.(format(localDate, 'yyyy-MM-dd HH:mm'))
      : setDateFromProps();
  };

  const handleClear = (e: MouseEvent<HTMLButtonElement>) => {
    e.stopPropagation();
    onChange?.(null);
  };

  const handleTimeChange = (e: ChangeEvent<HTMLInputElement>) => {
    const time = e.target.value;
    const [hours, minutes] = time.split(':').map((str) => parseInt(str, 10));
    const newSelectedDate = setHours(setMinutes(localDate || new Date(), minutes), hours);
    setLocalDate(newSelectedDate);
    setTimeValue(time);
  };

  return (
    <>
      <button
        className={classnames(styles.button, 'StylePath-Components-Controls-DatePicker')}
        onClick={(event) => setPopoverAnchor(event.currentTarget)}>
        {localDate ? (
          <span>{format(localDate, isTimePicker ? 'dd.MM.yyyy HH:mm' : 'dd.MM.yyyy')}</span>
        ) : (
          <span className={styles.placeholder}>
            <Trans>Wybierz datę</Trans>
          </span>
        )}
        {clearable && (
          <button className={styles.clear} onClick={handleClear}>
            <CloseIcon />
          </button>
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
        {isTimePicker && (
          <div className={styles.timePicker}>
            <input type="time" value={timeValue} onChange={handleTimeChange} />
          </div>
        )}

        <DayPicker
          selected={localDate}
          defaultMonth={localDate}
          onDayClick={(date?: Date) => date && handleOnSelectDate(date)}
        />
      </Popover>
    </>
  );
};

export default DatePicker;
