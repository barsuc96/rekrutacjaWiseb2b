// paginacja list

import React, { FC } from 'react';
import PaginationMui from '@mui/material/Pagination';
import classnames from 'classnames';

import styles from 'theme/components/controls/Pagination/Pagination.module.scss';

// typ danych wejściowych
interface IProps {
  pagesCount: number;
  page: number;
  onChange: (page: number) => void;
}

const Pagination: FC<IProps> = ({ pagesCount, page, onChange }) => {
  return (
    <>
      {pagesCount > 1 && (
        <div
          className={classnames(
            styles.wrapperComponent,
            'StylePath-Components-Controls-Pagination'
          )}>
          <PaginationMui
            count={pagesCount}
            page={page}
            onChange={(_, page) => {
              onChange(page);
              window.scrollTo({ top: 0, behavior: 'smooth' });
            }}
            color="secondary"
            shape="rounded"
          />
        </div>
      )}
    </>
  );
};

export default Pagination;
