/* eslint-disable @typescript-eslint/no-unused-vars */
// url resolver

import React from 'react';
import { useParams, useLocation } from 'react-router-dom';

import { DynamicPage } from 'plugins/pages';

const PanelDynamicPage = () => {
  const { pageSymbol } = useParams();
  const { search } = useLocation();

  const renderContent = () => {
    return <DynamicPage pageSymbol={pageSymbol + search || ''} />;
  };

  return renderContent();
};

export default PanelDynamicPage;
