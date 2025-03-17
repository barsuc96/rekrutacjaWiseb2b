// komponent przycisku do uploadu

import React, { ChangeEventHandler, FC, useRef } from 'react';
import classnames from 'classnames';

import styles from 'theme/components/controls/FileUploader/FileUploader.module.scss';
import { FolderOpenIcon, PaperclipIcon } from 'assets/icons';

// typ danych wejściowych
interface IProps {
  onChange: ChangeEventHandler<HTMLInputElement>;
  file: string;
  buttonLabel: string;
}

const FileUploader: FC<IProps> = ({ onChange, file, buttonLabel }) => {
  const hiddenInput = useRef<HTMLInputElement>(null);

  const handleClick = () => {
    hiddenInput.current?.click();
  };

  return (
    <div
      className={classnames(styles.wrapperComponent, 'StylePath-Components-Controls-FileUploader')}>
      <button className={styles.button} onClick={handleClick}>
        <FolderOpenIcon />
        <span className={styles.label}> {buttonLabel}</span>
      </button>
      <input type="file" ref={hiddenInput} onChange={onChange} style={{ display: 'none' }} />

      {file && (
        <div className={styles.file}>
          <PaperclipIcon /> {file}
        </div>
      )}
    </div>
  );
};

export default FileUploader;
