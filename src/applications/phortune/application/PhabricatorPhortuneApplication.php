<?php

final class PhabricatorPhortuneApplication extends PhabricatorApplication {

  public function getName() {
    return pht('Phortune');
  }

  public function getBaseURI() {
    return '/phortune/';
  }

  public function getShortDescription() {
    return pht('Accounts and Billing');
  }

  public function getIcon() {
    return 'fa-diamond';
  }

  public function getTitleGlyph() {
    return "\xE2\x97\x87";
  }

  public function getApplicationGroup() {
    return self::GROUP_UTILITIES;
  }

  public function isPrototype() {
    return true;
  }

  public function getRoutes() {
    return array(
      '/phortune/' => array(
        '' => 'PhortuneLandingController',
        'card/(?P<id>\d+)/' => array(
          'edit/' => 'PhortunePaymentMethodEditController',
          'disable/' => 'PhortunePaymentMethodDisableController',
        ),
        'cart/(?P<id>\d+)/' => array(
          '' => 'PhortuneCartViewController',
          'checkout/' => 'PhortuneCartCheckoutController',
          '(?P<action>print)/' => 'PhortuneCartViewController',
          '(?P<action>cancel|refund)/' => 'PhortuneCartCancelController',
          'update/' => 'PhortuneCartUpdateController',
        ),
        'account/' => array(
          '' => 'PhortuneAccountListController',

          $this->getEditRoutePattern('edit/')
            => 'PhortuneAccountEditController',

          '(?P<accountID>\d+)/' => array(
            '' => 'PhortuneAccountOverviewController',
            'details/' => 'PhortuneAccountDetailsController',
            'methods/' => array(
              '' => 'PhortuneAccountPaymentMethodController',
              '(?P<id>\d+)/' => 'PhortuneAccountPaymentMethodViewController',
              'new/' => 'PhortunePaymentMethodCreateController',
            ),
            'orders/' => array(
              '' => 'PhortuneAccountOrdersController',
              $this->getQueryRoutePattern('list/')
                => 'PhortuneAccountOrderListController',
            ),
            'charges/' => array(
              '' => 'PhortuneAccountChargesController',
              $this->getQueryRoutePattern('list/')
                => 'PhortuneAccountChargeListController',
            ),
            'subscriptions/' => array(
              '' => 'PhortuneAccountSubscriptionController',
              '(?P<subscriptionID>\d+)/' => array(
                '' => 'PhortuneAccountSubscriptionViewController',
                'autopay/(?P<methodID>\d+)/'
                  => 'PhortuneAccountSubscriptionAutopayController',
                $this->getQueryRoutePattern('orders/')
                  => 'PhortuneAccountOrderListController',
              ),
            ),
            'managers/' => array(
              '' => 'PhortuneAccountManagersController',
              'add/' => 'PhortuneAccountAddManagerController',
            ),
            'addresses/' => array(
              '' => 'PhortuneAccountEmailAddressesController',
              '(?P<id>\d+)/' => 'PhortuneAccountEmailViewController',
              $this->getEditRoutePattern('edit/')
                => 'PhortuneAccountEmailEditController',
            ),
          ),
        ),
        'product/' => array(
          '' => 'PhortuneProductListController',
          'view/(?P<id>\d+)/' => 'PhortuneProductViewController',
          'edit/(?:(?P<id>\d+)/)?' => 'PhortuneProductEditController',
        ),
        'provider/' => array(
          'edit/(?:(?P<id>\d+)/)?' => 'PhortuneProviderEditController',
          'disable/(?P<id>\d+)/' => 'PhortuneProviderDisableController',
          '(?P<id>\d+)/(?P<action>[^/]+)/'
            => 'PhortuneProviderActionController',
        ),
        'merchant/' => array(
          '(?:query/(?P<queryKey>[^/]+)/)?' => 'PhortuneMerchantListController',
          'picture/(?:(?P<id>\d+)/)?' => 'PhortuneMerchantPictureController',
          $this->getEditRoutePattern('edit/')
            => 'PhortuneMerchantEditController',
          'orders/(?P<merchantID>\d+)/(?:query/(?P<queryKey>[^/]+)/)?'
            => 'PhortuneCartListController',
          'manager/' => array(
            '(?:(?P<id>\d+)/)?' => 'PhortuneMerchantManagerController',
            'add/(?:(?P<id>\d+)/)?' => 'PhortuneMerchantAddManagerController',
          ),
          '(?P<merchantID>\d+)/' => array(
            'cart/(?P<id>\d+)/' => array(
              '' => 'PhortuneCartViewController',
              '(?P<action>cancel|refund)/' => 'PhortuneCartCancelController',
              'update/' => 'PhortuneCartUpdateController',
              'accept/' => 'PhortuneCartAcceptController',
            ),
            'subscription/' => array(
              '(?:query/(?P<queryKey>[^/]+)/)?'
                => 'PhortuneSubscriptionListController',
              'view/(?P<id>\d+)/'
                => 'PhortuneAccountSubscriptionViewController',
              'order/(?P<subscriptionID>\d+)/'
                => 'PhortuneCartListController',
            ),
            'invoice/' => array(
              'new/' => 'PhortuneMerchantInvoiceCreateController',
            ),
          ),
          '(?P<id>\d+)/' => 'PhortuneMerchantViewController',
        ),
      ),
    );
  }

  protected function getCustomCapabilities() {
    return array(
      PhortuneMerchantCapability::CAPABILITY => array(
        'caption' => pht('Merchant accounts can receive payments.'),
        'default' => PhabricatorPolicies::POLICY_ADMIN,
      ),
    );
  }

}
