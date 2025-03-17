// nakładka na component Link uwzględniająca aktualnie wybrany język

import React, { FC } from 'react';
import { Link as BaseLink, LinkProps } from 'react-router-dom';
import { useTranslation } from 'react-i18next';

const Link: FC<LinkProps> = (props) => {
  const { i18n } = useTranslation();
  const { to, ...restProps } = props;
  return <BaseLink to={`/${i18n.language}${to}`} {...restProps} />;
};

export default Link;
