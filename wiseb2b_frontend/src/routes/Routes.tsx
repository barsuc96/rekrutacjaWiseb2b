// Ruting aplikacji
import React from 'react';
import { Route, Routes as RouterRoutes, Navigate } from 'react-router-dom';
import { useTranslation } from 'react-i18next';

import useRoutes from './useRoutes';
import {
  DashboardSubLayout,
  DefaultLayout,
  MainLayout,
  ManagementPanelLayout
} from 'components/layouts';
import {
  ProfileLoading,
  Agreements,
  ChangePasswordForm,
  NewVersionPopup
} from 'components/containers';
import { Modal } from 'components/controls';
import * as Pages from 'pages';
import Confirmation from 'pages/Checkout/components/Confirmation';

const Routes = () => {
  const { t } = useTranslation();
  const {
    isProfileLoading,
    isAuthenticated,
    redirectUrl,
    urlPrefix,
    isOpenProfileReady,
    profile,
    notificationModal,
    dismissNotificationModal
  } = useRoutes();

  // strona z informacją o pobieraniu danych zalogowanego użytkownika
  if (isProfileLoading || isOpenProfileReady) {
    return (
      <DefaultLayout>
        <ProfileLoading />
      </DefaultLayout>
    );
  }

  // wymuszenie zmiany hasła
  if (profile?.change_password && profile?.role !== 'ROLE_OPEN_PROFILE') {
    return (
      <DefaultLayout>
        <Modal title={t('Ustawienie hasła')}>
          <ChangePasswordForm />
        </Modal>
      </DefaultLayout>
    );
  }

  if (profile?.consents_required && profile?.role !== 'ROLE_OPEN_PROFILE') {
    return (
      <DefaultLayout>
        <Modal title="" onClose={() => null}>
          <Agreements userId={profile.id} />
        </Modal>
      </DefaultLayout>
    );
  }

  return (
    <>
      <RouterRoutes>
        <Route element={isAuthenticated ? <MainLayout /> : <DefaultLayout />}>
          <Route path={`${urlPrefix}/login`} element={<Pages.Login />} />
          <Route path={`${urlPrefix}/forgot-password`} element={<Pages.ForgotPassword />} />
          <Route path={`${urlPrefix}/reset-password/:token`} element={<Pages.ResetPassword />} />
        </Route>
        <Route element={isAuthenticated ? <MainLayout /> : <Navigate to={`${urlPrefix}/login`} />}>
          <Route path={`${urlPrefix}`} element={<Pages.Home />} />
          <Route path={`${urlPrefix}/download/export/:hash`} element={<Pages.DownloadExport />} />
          {profile?.role !== 'ROLE_OPEN_PROFILE' ? (
            <>
              <Route path={`${urlPrefix}/cart/:id`} element={<Pages.Cart />} />
              <Route path={`${urlPrefix}/checkout/:cartId`} element={<Pages.Checkout />} />
              <Route
                path={`${urlPrefix}/checkout/success/:cartId`}
                element={<Confirmation cartId={37} />}
              />
              <Route
                path={`${urlPrefix}/dashboard`}
                element={<DashboardSubLayout urlPrefix={urlPrefix} />}>
                <Route index element={<Pages.Dashboard />} />
                <Route path="carts" element={<Pages.Carts />} />
                <Route path="carts/import" element={<Pages.CartsImport />} />
                <Route path="clients" element={<Pages.Clients />} />
                <Route path="deliveries" element={<Pages.Deliveries />} />
                <Route path="documents" element={<Pages.Documents />} />
                <Route path="export" element={<Pages.Export />} />
                <Route path="faq" element={<Pages.Faq />} />
                <Route path="consent" element={<Pages.Consent />} />
                <Route path="message-settings" element={<Pages.MessageSettings />} />
                <Route path="my-account" element={<Pages.MyAccount />} />
                <Route path="offers/dedicated" element={<Pages.OffersDedicated />} />
                <Route path="offers/periodic" element={<Pages.OffersPeriodic />} />
                <Route path="orders" element={<Pages.Orders />} />
                <Route path="orders/:orderId" element={<Pages.Order />} />
                <Route path="payments" element={<Pages.Payments />} />
                <Route path="receivers" element={<Pages.Receivers />} />
                <Route path="shopping-lists" element={<Pages.ShoppingLists />} />
                <Route path="terms" element={<Pages.Terms />} />
                <Route path="users" element={<Pages.Users />} />
                <Route path="cms/sections" element={<Pages.CmsSections />} />
                <Route path="cms/sections/:id" element={<Pages.CmsSection />} />
                <Route path="cms/articles" element={<Pages.CmsArticles />} />
                <Route path="cms/articles/:id" element={<Pages.CmsArticle />} />
                <Route path="cms/articles/create" element={<Pages.CmsArticle />} />
                <Route path="cms/media" element={<Pages.CmsMedia />} />
                <Route path="*" element={<Pages.UrlResolver />} />
              </Route>
            </>
          ) : (
            <>
              <Route path={`${urlPrefix}/cart/:id`} element={<Navigate to="/" replace />} />
              <Route path={`${urlPrefix}/checkout/:cartId`} element={<Navigate to="/" replace />} />
              <Route
                path={`${urlPrefix}/checkout/success/:cartId`}
                element={<Navigate to="/" replace />}
              />
              <Route path={`${urlPrefix}/dashboard/*`} element={<Navigate to="/" replace />} />
            </>
          )}
          <Route path={`${urlPrefix}/wip`} element={<Pages.WorkInProgress />} />
          <Route path={`${urlPrefix}/products`} element={<Pages.Products />} />
          <Route path={`${urlPrefix}/products/:id`} element={<Pages.Product />} />
          <Route path={`${urlPrefix}/register`} element={<Pages.Register />} />
          <Route path="confirm_register" element={<Pages.RegisterEmailConfirm />} />
          <Route path={`${urlPrefix}/thankyou_register`} element={<Pages.ThankYouRegister />} />
          <Route path="payment/success" element={<Pages.PaymentSuccess />} />
          <Route path={`${urlPrefix}/payment/status`} element={<Pages.PaymentStatus />} />
          <Route path={`${urlPrefix}/*`} element={<Pages.UrlResolver />} />
        </Route>
        <Route
          element={
            profile?.role === 'ROLE_ADMIN' || profile?.role === 'ROLE_TRADER' ? (
              <ManagementPanelLayout />
            ) : (
              <Navigate to="/" />
            )
          }>
          {/* Panel administratora/tradera */}
          <Route path={`${urlPrefix}/managment_panel`}>
            <Route index element={<Pages.PanelDashboard />} />
            <Route path="cms/sections" element={<Pages.CmsSections />} />
            <Route path="cms/sections/:id" element={<Pages.CmsSection />} />
            <Route path="cms/articles" element={<Pages.CmsArticles />} />
            <Route path="cms/articles/:id" element={<Pages.CmsArticle />} />
            <Route path="cms/articles/create" element={<Pages.CmsArticle />} />
            <Route path="cms/media" element={<Pages.CmsMedia />} />
            <Route path="dynamic_page/:pageSymbol" element={<Pages.PanelDynamicPage />} />
          </Route>
        </Route>
        <Route path="*" element={<Navigate to={redirectUrl} replace />} />
      </RouterRoutes>

      {notificationModal && (
        <Modal title={notificationModal.title} onClose={dismissNotificationModal}>
          <div dangerouslySetInnerHTML={{ __html: notificationModal.content }} />
        </Modal>
      )}

      <NewVersionPopup />
    </>
  );
};

export default Routes;
