// komponent selekt'a

import React, { ReactNode, useMemo } from 'react';
import { useTranslation } from 'react-i18next';
import ReactSelect, { Options } from 'react-select';
import CreatableSelect from 'react-select/creatable';
import { ChevronDown } from 'react-bootstrap-icons';
import classnames from 'classnames';

import { ErrorMessage } from 'components/controls';

import sassStyles from 'theme/components/controls/Select/Select.module.scss';

import defaultStyles from 'theme/components/controls/Select/styles/defaultStyles/defaultStyles.module.scss';
import borderedStyles from 'theme/components/controls/Select/styles/borderedStyles/borderedStyles.module.scss';
import borderlessStyles from 'theme/components/controls/Select/styles/borderlessStyles/borderlessStyles.module.scss';
import smallStyles from 'theme/components/controls/Select/styles/smallStyles/smallStyles.module.scss';

interface IOption<T> {
  value: number | string;
  label: ReactNode;
  item: T | null;
}

// typ danych wejściowych
interface IProps<T> {
  placeholder?: ReactNode;
  onChange: (item: T | null) => void;
  value?: number | string | null;
  selectedOption?: any;
  error?: string;
  options: Options<IOption<T>>;
  variant?: 'default' | 'bordered' | 'small' | 'borderless';
  disabled?: boolean;
  onCreateOption?: (name: string) => void;
  clearable?: boolean;
  isMulti?: boolean;
  isSpecial?: boolean;
  separatorMultiChoice?: string;
}

// TODO zmienić tu podejście do styli
// eslint-disable-next-line @typescript-eslint/no-explicit-any
const Select = <T extends Record<string, any>>({
  onChange,
  value,
  selectedOption,
  options = [],
  placeholder,
  variant = 'default',
  disabled,
  clearable,
  isMulti,
  onCreateOption,
  error,
  isSpecial,
  separatorMultiChoice
}: IProps<T>) => {
  const { t } = useTranslation();

  // zaznaczona opcja
  const currentOption = useMemo(() => {
    if (isMulti && typeof value === 'string') {
      return value.split(separatorMultiChoice || '|').map((name) =>
        options.find((option) => {
          return option.item?.value === name;
        })
      );
    }

    return selectedOption || options.find((option) => option.value === value) || null;
  }, [value, options, selectedOption]);

  // strzałka dropdown'u
  const DropdownIndicator = () => <ChevronDown />;

  // wybranie wariantu
  const styles =
    variant === 'bordered'
      ? borderedStyles
      : variant === 'small'
      ? smallStyles
      : variant === 'borderless'
      ? borderlessStyles
      : defaultStyles;

  // typ komponentu: CreatableSelect - z opcją tworzenia nowej opcji, ReactSelect - domyślny selekt
  const SelectComponent = useMemo(
    () => (onCreateOption ? CreatableSelect : ReactSelect),
    [onCreateOption]
  );

  return (
    <div
      className={classnames(
        sassStyles.wrapperComponent,
        { [sassStyles.isSpecial]: !!isSpecial },
        'StylePath-Components-Controls-Select'
      )}>
      <SelectComponent
        options={options}
        placeholder={placeholder || t('Wybierz...')}
        classNames={{
          container: () => styles.container,
          control: ({ menuIsOpen }): string =>
            classnames(styles.control, { [styles.controlOpen]: menuIsOpen }),
          option: ({ isSelected }): string =>
            classnames(styles.option, { [styles.optionSelected]: isSelected }),
          indicatorSeparator: (): string => styles.indicatorSeparator,
          indicatorsContainer: (): string => styles.indicatorsContainer,
          dropdownIndicator: (): string => styles.dropdownIndicator,
          valueContainer: (): string => styles.valueContainer,
          singleValue: (): string => styles.singleValue,
          placeholder: (): string => styles.placeholder,
          menu: (): string => styles.menu,
          menuList: (): string => styles.menuList
        }}
        onChange={(option) => {
          isMulti ? onChange(option) : onChange(option?.item || null);
        }}
        value={currentOption}
        components={{ DropdownIndicator }}
        isSearchable={!!onCreateOption}
        isDisabled={disabled}
        onCreateOption={onCreateOption}
        formatCreateLabel={(label) => `${t('Stwórz')} "${label}"`}
        isClearable={clearable}
        isMulti={isMulti}
      />

      {error && <ErrorMessage>{error}</ErrorMessage>}
    </div>
  );
};

export default Select;
