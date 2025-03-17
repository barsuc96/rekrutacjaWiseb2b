// Konfiguracja dokumentacji
import path from 'path';

const backendPlugins = [
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'agreement-client-docs',
            path: path.resolve(__dirname, '../backend/Wise/Agreement/Docs/Client'),
            routeBasePath: 'docs/client/agreement',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/Agreement/Docs/Client/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'agreement-developer-docs',
            path: path.resolve(__dirname, '../backend/Wise/Agreement/Docs/Developer'),
            routeBasePath: 'docs/developer/agreement',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/Agreement/Docs/Developer/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'cart-client-docs',
            path: path.resolve(__dirname, '../backend/Wise/Cart/Docs/Client'),
            routeBasePath: 'docs/client/cart',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/Cart/Docs/Client/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'cart-developer-docs',
            path: path.resolve(__dirname, '../backend/Wise/Cart/Docs/Developer'),
            routeBasePath: 'docs/developer/cart',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/Cart/Docs/Developer/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'checkout-client-docs',
            path: path.resolve(__dirname, '../backend/Wise/Checkout/Docs/Client'),
            routeBasePath: 'docs/client/checkout',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/Checkout/Docs/Client/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'checkout-developer-docs',
            path: path.resolve(__dirname, '../backend/Wise/Checkout/Docs/Developer'),
            routeBasePath: 'docs/developer/checkout',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/Checkout/Docs/Developer/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'client-client-docs',
            path: path.resolve(__dirname, '../backend/Wise/Client/Docs/Client'),
            routeBasePath: 'docs/client/client',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/Client/Docs/Client/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'client-developer-docs',
            path: path.resolve(__dirname, '../backend/Wise/Client/Docs/Developer'),
            routeBasePath: 'docs/developer/client',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/Client/Docs/Developer/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'client-api-client-docs',
            path: path.resolve(__dirname, '../backend/Wise/ClientApi/Docs/Client'),
            routeBasePath: 'docs/client/client-api',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/ClientApi/Docs/Client/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'client-api-developer-docs',
            path: path.resolve(__dirname, '../backend/Wise/ClientApi/Docs/Developer'),
            routeBasePath: 'docs/developer/client-api',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/ClientApi/Docs/Developer/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'cms-client-docs',
            path: path.resolve(__dirname, '../backend/Wise/Cms/Docs/Client'),
            routeBasePath: 'docs/client/cms',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/Cms/Docs/Client/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'cms-developer-docs',
            path: path.resolve(__dirname, '../backend/Wise/Cms/Docs/Developer'),
            routeBasePath: 'docs/developer/cms',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/Cms/Docs/Developer/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'core-client-docs',
            path: path.resolve(__dirname, '../backend/Wise/Core/Docs/Client'),
            routeBasePath: 'docs/client/core',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/Core/Docs/Client/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'core-developer-docs',
            path: path.resolve(__dirname, '../backend/Wise/Core/Docs/Developer'),
            routeBasePath: 'docs/developer/core',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/Core/Docs/Developer/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'delivery-client-docs',
            path: path.resolve(__dirname, '../backend/Wise/Delivery/Docs/Client'),
            routeBasePath: 'docs/client/delivery',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/Delivery/Docs/Client/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'delivery-developer-docs',
            path: path.resolve(__dirname, '../backend/Wise/Delivery/Docs/Developer'),
            routeBasePath: 'docs/developer/delivery',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/Delivery/Docs/Developer/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'document-developer-docs',
            path: path.resolve(__dirname, '../backend/Wise/Document/Docs/Developer'),
            routeBasePath: 'docs/developer/document',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/Document/Docs/Developer/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'document-client-docs',
            path: path.resolve(__dirname, '../backend/Wise/Document/Docs/Client'),
            routeBasePath: 'docs/client/document',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/Document/Docs/Client/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'dynamic-ui-developer-docs',
            path: path.resolve(__dirname, '../backend/Wise/DynamicUI/Docs/Developer'),
            routeBasePath: 'docs/developer/dynamic-ui',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/DynamicUI/Docs/Developer/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'dynamic-ui-client-docs',
            path: path.resolve(__dirname, '../backend/Wise/DynamicUI/Docs/Client'),
            routeBasePath: 'docs/client/dynamic-ui',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/DynamicUI/Docs/Client/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'export-catalog-developer-docs',
            path: path.resolve(__dirname, '../backend/Wise/ExportCatalog/Docs/Developer'),
            routeBasePath: 'docs/developer/export-catalog',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/ExportCatalog/Docs/Developer/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'export-catalog-client-docs',
            path: path.resolve(__dirname, '../backend/Wise/ExportCatalog/Docs/Client'),
            routeBasePath: 'docs/client/export-catalog',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/ExportCatalog/Docs/Client/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'file-developer-docs',
            path: path.resolve(__dirname, '../backend/Wise/File/Docs/Developer'),
            routeBasePath: 'docs/developer/file',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/File/Docs/Developer/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'file-client-docs',
            path: path.resolve(__dirname, '../backend/Wise/File/Docs/Client'),
            routeBasePath: 'docs/client/file',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/File/Docs/Client/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'i18n-docs',
            path: path.resolve(__dirname, '../backend/Wise/I18n/Docs/Developer'),
            routeBasePath: 'docs/developer/i18n',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/I18n/Docs/Developer/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'i18n-client-docs',
            path: path.resolve(__dirname, '../backend/Wise/I18n/Docs/Client'),
            routeBasePath: 'docs/client/i18n',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/I18n/Docs/Client/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'message-docs',
            path: path.resolve(__dirname, '../backend/Wise/Message/Docs/Developer'),
            routeBasePath: 'docs/developer/message',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/Message/Docs/Developer/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'message-client-docs',
            path: path.resolve(__dirname, '../backend/Wise/Message/Docs/Client'),
            routeBasePath: 'docs/client/message',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/Message/Docs/Client/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'multi-store-docs',
            path: path.resolve(__dirname, '../backend/Wise/MultiStore/Docs/Developer'),
            routeBasePath: 'docs/developer/multi-store',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/MultiStore/Docs/Developer/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'multi-store-client-docs',
            path: path.resolve(__dirname, '../backend/Wise/MultiStore/Docs/Client'),
            routeBasePath: 'docs/client/multi-store',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/MultiStore/Docs/Client/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'offer-docs',
            path: path.resolve(__dirname, '../backend/Wise/Offer/Docs/Developer'),
            routeBasePath: 'docs/developer/offer',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/Offer/Docs/Developer/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'offer-client-docs',
            path: path.resolve(__dirname, '../backend/Wise/Offer/Docs/Client'),
            routeBasePath: 'docs/client/offer',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/Offer/Docs/Client/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'order-docs',
            path: path.resolve(__dirname, '../backend/Wise/Order/Docs/Developer'),
            routeBasePath: 'docs/developer/order',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/Order/Docs/Developer/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'order-client-docs',
            path: path.resolve(__dirname, '../backend/Wise/Order/Docs/Client'),
            routeBasePath: 'docs/client/order',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/Order/Docs/Client/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'order-edit-docs',
            path: path.resolve(__dirname, '../backend/Wise/OrderEdit/Docs/Developer'),
            routeBasePath: 'docs/developer/order-edit',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/OrderEdit/Docs/Developer/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'order-edit-client-docs',
            path: path.resolve(__dirname, '../backend/Wise/OrderEdit/Docs/Client'),
            routeBasePath: 'docs/client/order-edit',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/OrderEdit/Docs/Client/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'payment-docs',
            path: path.resolve(__dirname, '../backend/Wise/Payment/Docs/Developer'),
            routeBasePath: 'docs/developer/payment',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/Payment/Docs/Developer/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'payment-client-docs',
            path: path.resolve(__dirname, '../backend/Wise/Payment/Docs/Client'),
            routeBasePath: 'docs/client/payment',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/Payment/Docs/Client/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'pricing-docs',
            path: path.resolve(__dirname, '../backend/Wise/Pricing/Docs/Developer'),
            routeBasePath: 'docs/developer/pricing',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/Pricing/Docs/Developer/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'pricing-client-docs',
            path: path.resolve(__dirname, '../backend/Wise/Pricing/Docs/Client'),
            routeBasePath: 'docs/client/pricing',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/Pricing/Docs/Client/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'product-docs',
            path: path.resolve(__dirname, '../backend/Wise/Product/Docs/Developer'),
            routeBasePath: 'docs/developer/product',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/Product/Docs/Developer/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'product-client-docs',
            path: path.resolve(__dirname, '../backend/Wise/Product/Docs/Client'),
            routeBasePath: 'docs/client/product',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/Product/Docs/Client/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'receiver-docs',
            path: path.resolve(__dirname, '../backend/Wise/Receiver/Docs/Developer'),
            routeBasePath: 'docs/developer/receiver',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/Receiver/Docs/Developer/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'receiver-client-docs',
            path: path.resolve(__dirname, '../backend/Wise/Receiver/Docs/Client'),
            routeBasePath: 'docs/client/receiver',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/Receiver/Docs/Client/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'search-product-docs',
            path: path.resolve(__dirname, '../backend/Wise/SearchProduct/Docs/Developer'),
            routeBasePath: 'docs/developer/search-product',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/SearchProduct/Docs/Developer/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'search-product-client-docs',
            path: path.resolve(__dirname, '../backend/Wise/SearchProduct/Docs/Client'),
            routeBasePath: 'docs/client/search-product',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/SearchProduct/Docs/Client/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'security-docs',
            path: path.resolve(__dirname, '../backend/Wise/Security/Docs/Developer'),
            routeBasePath: 'docs/developer/security',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/Security/Docs/Developer/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'security-client-docs',
            path: path.resolve(__dirname, '../backend/Wise/Security/Docs/Client'),
            routeBasePath: 'docs/client/security',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/Security/Docs/Client/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'service-docs',
            path: path.resolve(__dirname, '../backend/Wise/Service/Docs/Developer'),
            routeBasePath: 'docs/developer/service',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/Service/Docs/Developer/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'service-client-docs',
            path: path.resolve(__dirname, '../backend/Wise/Service/Docs/Client'),
            routeBasePath: 'docs/client/service',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/Service/Docs/Client/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'shopping-list-docs',
            path: path.resolve(__dirname, '../backend/Wise/ShoppingList/Docs/Developer'),
            routeBasePath: 'docs/developer/shopping-list',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/ShoppingList/Docs/Developer/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'shopping-list-client-docs',
            path: path.resolve(__dirname, '../backend/Wise/ShoppingList/Docs/Client'),
            routeBasePath: 'docs/client/shopping-list',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/ShoppingList/Docs/Client/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'stock-docs',
            path: path.resolve(__dirname, '../backend/Wise/Stock/Docs/Developer'),
            routeBasePath: 'docs/developer/stock',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/Stock/Docs/Developer/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'stock-client-docs',
            path: path.resolve(__dirname, '../backend/Wise/Stock/Docs/Client'),
            routeBasePath: 'docs/client/stock',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/Stock/Docs/Client/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'user-docs',
            path: path.resolve(__dirname, '../backend/Wise/User/Docs/Developer'),
            routeBasePath: 'docs/developer/user',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/User/Docs/Developer/config.js'),
        },
    ],
    [
        '@docusaurus/plugin-content-docs',
        {
            id: 'user-client-docs',
            path: path.resolve(__dirname, '../backend/Wise/User/Docs/Client'),
            routeBasePath: 'docs/client/user',
            sidebarPath: path.resolve(__dirname, '../backend/Wise/User/Docs/Client/config.js'),
        },
    ],
    // Możesz dodać kolejne pluginy
];

export default backendPlugins;
