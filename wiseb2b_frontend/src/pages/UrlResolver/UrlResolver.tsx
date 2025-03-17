// url resolver

import React, { useEffect } from 'react';
import { useLocation, useNavigate } from 'react-router-dom';
import { Helmet } from 'react-helmet';
import qs from 'query-string';

import { useGetLayoutUrlResolver } from 'api';

import { Products, Product, NoMatch, CmsPage } from 'pages';

interface IParams {
  category_id?: string;
  product_id?: string;
  mode?: 'PROMOTIONS' | 'NEWS' | 'BESTSELLERS';
}

const UrlResolver = () => {
  // ścieżka z url
  const { pathname, search, state } = useLocation();
  const navigate = useNavigate();

  const getPosition = (string: string, subString: string, index: number) => {
    return string.split(subString, index).join(subString).length;
  };

  const urlPrefix = pathname.slice(0, getPosition(pathname, '/', 2));

  // url key
  const urlKey = pathname.replace(urlPrefix, '').replace('/', '');

  const {
    data: urlResolverData,
    refetch: getUrlResolver,
    status
  } = useGetLayoutUrlResolver(
    { urlKey },
    {
      enabled: false,
      onSuccess: (data) => {
        const { category_id, product_id, mode, ...rest } = qs.parse(data?.parameters || '');

        const urlParams = { ...rest, ...qs.parse(search) };

        navigate(pathname + `?${qs.stringify(urlParams)}`, { replace: true, state });
      }
    }
  );

  useEffect(() => {
    getUrlResolver();
  }, [urlKey]);

  const renderContent = () => {
    const { category_id, product_id, mode, ...rest } = qs.parse(
      urlResolverData?.parameters || ''
    ) as IParams;
    if (urlResolverData?.page_type === 'PRODUCTS_LIST') {
      return <Products categoryId={category_id} mode={mode} />;
    }

    if (urlResolverData?.page_type === 'PRODUCT_DETAILS' && product_id) {
      return <Product id={product_id} />;
    }

    if (urlResolverData?.page_type === 'SUBPAGE') {
      return <CmsPage sectionId="SUBPAGE" articleId={urlResolverData.parameters} />;
    }

    if (status === 'error') {
      return <NoMatch />;
    }

    return null;
  };

  return (
    <>
      <Helmet>
        {urlResolverData?.href_lang?.map((shortcut, i) => (
          <link
            key={i}
            rel="alternate"
            href={`${window.location.origin}/${shortcut.language}/${shortcut.url_shortcut}`}
            hrefLang={shortcut.language}
          />
        ))}
      </Helmet>
      {renderContent()}
    </>
  );
};

export default UrlResolver;
