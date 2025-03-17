// wrapper o szerokości wyświetlanej zawartości

import React, { FC, PropsWithChildren } from 'react';
import { Container as ContainerMui } from '@mui/material';
import { useRWD } from 'hooks';

const Container: FC<PropsWithChildren> = ({ children }) => {
  const { isMobile } = useRWD();

  return <ContainerMui maxWidth="xl" disableGutters={isMobile}>{children}</ContainerMui>;
};

export default Container;
