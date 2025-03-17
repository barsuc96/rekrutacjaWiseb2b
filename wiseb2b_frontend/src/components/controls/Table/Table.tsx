// komponent tabelki

import React, { Key, ReactElement, ReactNode } from 'react';
import classnames from 'classnames';

import { useRWD } from 'hooks';
import { Pagination } from 'components/controls';

import { ArrowLongThinIcon } from 'assets/icons';
import styles from 'theme/components/controls/Table/Table.module.scss';

export interface ISorter<T> {
  by: keyof T;
  direction: 'asc' | 'desc';
}

export interface IColumn<T> {
  title: ReactNode;
  key?: string;
  dataIndex?: keyof T;
  renderCell?: (record: T, index: number) => ReactNode;
  sortBy?: string;
  width?: number;
  align?: 'left' | 'right' | 'center';
  isMobileHidden?: boolean;
}

// typ danych wejściowych
interface IProps<T> {
  columns: IColumn<T>[];
  dataSource: T[];
  mobileItem?: (record: T, index: number) => ReactNode;
  expandedRowContents?: {
    id: number;
    content: ReactNode;
  }[];
  rowKey: keyof T;
  onSortChange?: (sorter: ISorter<T>) => void;
  sorter?: ISorter<T>;
  pagination?: {
    page: number;
    pagesCount: number;
    onChange: (page: number) => void;
  };
}

// eslint-disable-next-line @typescript-eslint/no-explicit-any
const Table = <T extends Record<string, any>>({
  columns,
  rowKey,
  dataSource,
  onSortChange,
  sorter,
  pagination,
  mobileItem,
  expandedRowContents
}: IProps<T>): ReactElement => {
  const { isMobile } = useRWD();

  // funkcja obsługująca zmianę sortoweania
  const onHeaderCellClick = (fieldName: string) => {
    onSortChange?.({
      by: fieldName,
      direction: sorter?.by === fieldName && sorter.direction === 'asc' ? 'desc' : 'asc'
    });
  };

  return (
    <>
      {isMobile ? (
        <div className={styles.mobileList}>
          {dataSource.map((item, rowIndex) =>
            isMobile && mobileItem ? (
              mobileItem(item, rowIndex)
            ) : (
              <div className={styles.mobileListItem} key={item[rowKey]}>
                {columns
                  .filter((column) => !column.isMobileHidden)
                  .map((column) => (
                    <div key={(column.dataIndex || column.key) as Key} className={styles.line}>
                      {column.title ? <div className={styles.label}>{column.title}</div> : null}
                      <div>
                        {column.renderCell
                          ? column.renderCell(item, rowIndex)
                          : column.dataIndex && item[column.dataIndex]}
                      </div>
                    </div>
                  ))}
              </div>
            )
          )}
        </div>
      ) : (
        <table className={styles.table}>
          <thead>
            <tr>
              {columns.map((column) => (
                <th
                  className={classnames(styles.th, {
                    [styles['text-align-center']]: !column.align,
                    [styles['cursor-pointer']]: !!column.sortBy
                  })}
                  style={{ textAlign: column.align, cursor: 'inherit', width: `${column.width}px` }}
                  key={(column.dataIndex || column.key) as Key}
                  onClick={() => {
                    typeof column.dataIndex !== 'undefined' &&
                      !!column.sortBy &&
                      onHeaderCellClick(column.sortBy);
                  }}
                  align={column.align}>
                  <>
                    {!!column.sortBy && (
                      <span className={styles.sortWrapper}>
                        <ArrowLongThinIcon
                          className={classnames(styles.sortArrow, {
                            [styles.current]:
                              sorter?.by === column.dataIndex && sorter?.direction === 'desc'
                          })}
                        />
                        <ArrowLongThinIcon
                          className={classnames(styles.sortArrow, styles.down, {
                            [styles.current]:
                              sorter?.by === column.dataIndex && sorter?.direction === 'asc'
                          })}
                        />
                      </span>
                    )}
                    <span>{column.title}</span>
                  </>
                </th>
              ))}
            </tr>
          </thead>
          <tbody>
            {dataSource.map((item, rowIndex) => {
              const expandedContent = expandedRowContents?.find(
                (expandedRowData) => expandedRowData.id === item[rowKey]
              );
              return (
                <React.Fragment key={item[rowKey]}>
                  <tr>
                    {columns.map((column) => {
                      return (
                        <td
                          className={styles.td}
                          key={(column.dataIndex || column.key) as Key}
                          align={column.align}>
                          <>
                            {column.renderCell
                              ? column.renderCell(item, rowIndex)
                              : column.dataIndex && item[column.dataIndex]}
                          </>
                        </td>
                      );
                    })}
                  </tr>
                  {!!expandedContent && (
                    <tr className="expanded-row">
                      <td colSpan={columns.length}>{expandedContent.content}</td>
                    </tr>
                  )}
                </React.Fragment>
              );
            })}
          </tbody>
        </table>
      )}

      {pagination && (
        <Pagination
          pagesCount={pagination.pagesCount}
          page={pagination.page}
          onChange={pagination.onChange}
        />
      )}
    </>
  );
};

export default Table;
