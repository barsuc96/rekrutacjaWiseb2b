// input wyszukiwania

import React, { FC, FormEvent, useEffect, useState } from 'react';
import { Search } from 'react-bootstrap-icons';
import IconButton from '@mui/material/IconButton';
import classnames from 'classnames';

import styles from 'theme/components/controls/SearchInput/SearchInput.module.scss';

// typ danych wejściowych
interface IProps {
  value?: string;
  placeholder?: string;
  onChange?: (phrase: string) => void;
}

const SearchInput: FC<IProps> = ({ onChange, placeholder, value = '' }) => {
  // aktualna fraza wyszukiwania
  const [searchQuery, setSearchQuery] = useState(value);

  // aktualizacja frazy w lokalnym sanie po zmianie prop'a value
  useEffect(() => setSearchQuery(value), [value]);

  // obsługa zatwierdzenia zmiany frazy
  const handleOnSubmit = (event: FormEvent) => {
    event.preventDefault();
    onChange?.(searchQuery);
  };

  return (
    <div
      className={classnames(styles.wrapperComponent, 'StylePath-Components-Controls-SearchInput')}>
      <form className={styles.form} onSubmit={handleOnSubmit}>
        <input
          className={styles.input}
          type="text"
          value={searchQuery}
          placeholder={placeholder}
          onChange={(event) => setSearchQuery(event.target.value)}
        />
        <IconButton type="submit" aria-label="Search" className={styles.searchButton}>
          <Search />
        </IconButton>
      </form>
    </div>
  );
};

export default SearchInput;
