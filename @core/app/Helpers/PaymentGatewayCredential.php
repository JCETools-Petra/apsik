<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Xgenious\Paymentgateway\Facades\XgPaymentGateway;

class PaymentGatewayCredential
{
    public static function exchange_rate_usd_to_inr(){
        return  get_static_option('site_usd_to_inr_exchange_rate') ?? 74;
    }

    public static function exchange_rate_usd_to_ngn(){
        return  get_static_option('site_usd_to_ngn_exchange_rate') ?? 74;
    }
    //site_inr_to_usd_exchange_rate
    public static function exchange_rate_usd(){
        return  get_static_option('site_'.strtolower(getenv('SITE_GLOBAL_CURRENCY')).'_to_usd_exchange_rate') ?? 74;
    }
    public static function get_global_currency(){
        return get_static_option('site_global_currency');
    }
    public static function get_paypal_credential() : object
    {
        $mode = get_static_option('paypal_test_mode') == 'on' ? true : false;
        
        $paypal_client_id = $mode ? get_static_option('paypal_sandbox_client_id') : get_static_option('paypal_live_client_id');
        $paypal_client_secret = $mode? get_static_option('paypal_sandbox_client_secret') : get_static_option('paypal_live_client_secret');
        $paypal_app_id = $mode ? get_static_option('paypal_sandbox_app_id') : get_static_option('paypal_live_app_id');
        $paypal = XgPaymentGateway::paypal();
        $paypal->setClientId($paypal_client_id);
        $paypal->setClientSecret($paypal_client_secret);
        $paypal->setAppId($paypal_app_id);
        $paypal->setCurrency(self::get_global_currency());
        $paypal->setEnv($mode);
        $paypal->setExchangeRate(self::exchange_rate_usd());

        return $paypal;
    }

    public static function get_paytm_credential() : object
    {
        $mode = get_static_option('paypal_test_mode') == 'on' ? true : false;
        $paytm = XgPaymentGateway::paytm();
        $paytm->setMerchantId(getenv('PAYTM_MERCHANT_ID'));
        $paytm->setMerchantKey(getenv('PAYTM_MERCHANT_KEY'));
        $paytm->setMerchantWebsite(getenv('PAYTM_MERCHANT_WEBSITE'));
        $paytm->setChannel(getenv('PAYTM_CHANNEL'));
        $paytm->setIndustryType(getenv('PAYTM_INDUSTRY_TYPE'));
        $paytm->setCurrency(self::get_global_currency());
        $paytm->setEnv($mode);
        $paytm->setExchangeRate(self::exchange_rate_usd_to_inr());

        return $paytm;
    }

    public static function get_stripe_credential() : object
    {
        $stripe = XgPaymentGateway::stripe();
        $stripe->setSecretKey(getenv('STRIPE_SECRET_KEY'));
        $stripe->setPublicKey(getenv('STRIPE_PUBLIC_KEY'));
        $stripe->setCurrency(self::get_global_currency());
        $stripe->setEnv(getenv('STRIPE_TEST_MODE'));
        $stripe->setExchangeRate(self::exchange_rate_usd_to_inr());

        return $stripe;
    }

    public static function get_razorpay_credential() : object
    {
        $razorpay = XgPaymentGateway::razorpay();
        $razorpay->setApiKey(getenv('RAZORPAY_API_KEY'));
        $razorpay->setApiSecret(getenv('RAZORPAY_API_SECRET'));
        $razorpay->setCurrency(self::get_global_currency());
        $razorpay->setEnv(getenv('RAZORPAY_TESTMODE'));
        $razorpay->setExchangeRate(self::exchange_rate_usd_to_inr());

        return $razorpay;
    }

    public static function get_paystack_credential() : object
    {
        $paystack = XgPaymentGateway::paystack();
        $paystack->setPublicKey(getenv('PAYSTACK_PUBLIC_KEY'));
        $paystack->setSecretKey(getenv('PAYSTACK_SECRET_KEY'));
        $paystack->setMerchantEmail(getenv('MERCHANT_EMAIL'));
        $paystack->setCurrency(self::get_global_currency());
        $paystack->setEnv(getenv('PAYSTACK_TEST_MODE'));
        $paystack->setExchangeRate(self::exchange_rate_usd_to_ngn());

        return $paystack;
    }

    public static function get_mollie_credential() : object
    {
        $mollie = XgPaymentGateway::mollie();
        $mollie->setApiKey(getenv('MOLLIE_KEY'));
        $mollie->setCurrency(self::get_global_currency());
        $mollie->setEnv(getenv('MOLLIE_TEST_MODE'));
        $mollie->setExchangeRate(self::exchange_rate_usd_to_inr());

        return $mollie;
    }

    public static function get_flutterwave_credential() : object
    {
        $flutterwave = XgPaymentGateway::flutterwave();
        $flutterwave->setPublicKey(getenv('FLW_PUBLIC_KEY'));
        $flutterwave->setSecretKey(getenv('FLW_SECRET_KEY'));
        $flutterwave->setCurrency(self::get_global_currency());
        $flutterwave->setEnv(getenv('FLW_TEST_MODE'));
        $flutterwave->setExchangeRate(self::exchange_rate_usd_to_ngn());

        return $flutterwave;
    }

    public static function get_midtrans_credential() : object
    {
        $midtrans = XgPaymentGateway::midtrans();
        $midtrans->setClientKey(getenv('MIDTRANS_CLIENT_KEY'));
        $midtrans->setServerKey(getenv('MIDTRANS_SERVER_KEY'));
        $midtrans->setCurrency(self::get_global_currency());
        $midtrans->setEnv(getenv('MIDTRANS_ENVIRONTMENT'));
        $midtrans->setExchangeRate(get_static_option('site_usd_to_idr_exchange_rate'));

        return $midtrans;
    }

    public static function get_payfast_credential() : object
    {
        $payfast = XgPaymentGateway::payfast();
        $payfast->setMerchantId(getenv('PF_MERCHANT_ID'));
        $payfast->setMerchantKey(getenv('PF_MERCHANT_KEY'));
        $payfast->setPassphrase(getenv('PAYFAST_PASSPHRASE'));
        $payfast->setCurrency(self::get_global_currency());
        $payfast->setEnv(getenv('PF_MERCHANT_ENV'));
        $payfast->setExchangeRate(get_static_option('site_usd_to_zar_exchange_rate'));

        return $payfast;
    }

    public static function get_cashfree_credential() : object
    {
        $cashfree = XgPaymentGateway::cashfree();
        $cashfree->setAppId(getenv('CASHFREE_APP_ID'));
        $cashfree->setSecretKey(getenv('CASHFREE_SECRET_KEY'));
        $cashfree->setCurrency(self::get_global_currency());
        $cashfree->setEnv(getenv('CASHFREE_TEST_MODE'));
        $cashfree->setExchangeRate(self::exchange_rate_usd_to_inr());

        return $cashfree;
    }

    public static function get_instamojo_credential() : object
    {
        $instamojo = XgPaymentGateway::instamojo();
        $instamojo->setClientId(getenv('INSTAMOJO_CLIENT_ID'));
        $instamojo->setSecretKey(getenv('INSTAMOJO_CLIENT_SECRET'));
        $instamojo->setCurrency(self::get_global_currency());
        $instamojo->setEnv(getenv('INSTAMOJO_TEST_MODE'));
        $instamojo->setExchangeRate(self::exchange_rate_usd_to_inr());

        return $instamojo;
    }

    public static function get_marcadopago_credential() : object
    {
        $marcadopago = XgPaymentGateway::marcadopago();
        $marcadopago->setClientId(getenv('MERCADO_PAGO_CLIENT_ID'));
        $marcadopago->setClientSecret(getenv('MERCADO_PAGO_CLIENT_SECRET'));
        $marcadopago->setCurrency(self::get_global_currency());
        $marcadopago->setEnv(getenv('MERCADO_PAGO_TEST_MODE'));
        $marcadopago->setExchangeRate(get_static_option('site_usd_to_brl_exchange_rate'));

        return $marcadopago;
    }

    public static function get_squareup_credential() : object
    {
        $squareup = XgPaymentGateway::squareup();
        $squareup->setLocationId(getenv('SQUAREUP_LOCATION_ID'));
        $squareup->setAccessToken(getenv('SQUAREUP_ACCESS_TOKEN'));
        $squareup->setApplicationId('12515');
        $squareup->setCurrency(self::get_global_currency());
        $squareup->setEnv(getenv('SQUAREUP_ACCESS_TEST_MODE'));
        $squareup->setExchangeRate(self::exchange_rate_usd_to_inr());

        return $squareup;
    }

    public static function get_cinetpay_credential() : object
    {
        $cinetpay = XgPaymentGateway::cinetpay();
        $cinetpay->setAppKey(getenv('CINETPAY_API_KEY'));
        $cinetpay->setSiteId(getenv('CINETPAY_SITE_ID'));
        $cinetpay->setCurrency(self::get_global_currency());
        $cinetpay->setEnv(getenv('CINETPAY_TEST_MODE'));
        $cinetpay->setExchangeRate(self::exchange_rate_usd_to_inr());

        return $cinetpay;
    }

    public static function get_paytabs_credential() : object
    {
        $paytabs = XgPaymentGateway::paytabs();
        $paytabs->setProfileId(getenv('PAYTABS_PROFILE_ID'));
        $paytabs->setRegion(getenv('PAYTABS_REGION'));
        $paytabs->setServerKey(getenv('PAYTABS_SERVER_KEY'));
        $paytabs->setCurrency(self::get_global_currency());
        $paytabs->setEnv(getenv('PAYTABS_TEST_MODE'));
        $paytabs->setExchangeRate(74);

        return $paytabs;
    }

    public static function get_billplz_credential() : object
    {
        $billplz = XgPaymentGateway::billplz();
        $billplz->setKey(getenv('BILLPLZ_KEY'));
        $billplz->setVersion(getenv('BILLPLZ_VERSION'));
        $billplz->setXsignature(getenv('BILLPLZ_X_SIGNATURE'));
        $billplz->setCollectionName(getenv('BILLPLZ_COLLECTION_NAME'));
        $billplz->setCurrency(self::get_global_currency());
        $billplz->setEnv(getenv('BILLPLZ_TEST_MODE'));
        $billplz->setExchangeRate(get_static_option('site_usd_to_myr_exchange_rate'));

        return $billplz;
    }

    public static function get_zitopay_credential() : object
    {

        $zitopay = XgPaymentGateway::zitopay();
        $zitopay->setUsername(get_static_option('zitopay_username'));
        $zitopay->setCurrency(self::get_global_currency());
        $zitopay->setEnv(get_static_option('zitopay_test_mode'));
        $zitopay->setExchangeRate(get_static_option('site_usd_to_myr_exchange_rate'));

        return $zitopay;
    }

    public static function get_toyyibpay_credential() : object
    {
        $toyyibpay = XgPaymentGateway::toyyibpay();
        $toyyibpay->setUserSecretKey(get_static_option('toyyibpay_set_user_secret_key'));
        $toyyibpay->setCategoryCode(get_static_option('toyyibpay_set_category_code'));
        $toyyibpay->setCurrency(self::get_global_currency());
        $toyyibpay->setEnv(get_static_option('toyyibpay_test_mode'));

        return $toyyibpay;
    }

    public static function get_pagalipay_credential() : object
    {
        $pagali = XgPaymentGateway::pagalipay();
        $pagali->setPageId(get_static_option('pagalipay_set_page_id'));
        $pagali->setEntityId(get_static_option('pagalipay_set_entity_id'));
        $pagali->setCurrency(self::get_global_currency());
        $pagali->setEnv(get_static_option('pagalipay_test_mode'));

        return $pagali;
    }

    public static function get_authorizenet_credential() : object
    {

        $authorizenet = XgPaymentGateway::authorizenet();
        $authorizenet->setMerchantLoginId(get_static_option('authorizenet_set_merchant_login_id'));
        $authorizenet->setMerchantTransactionId(get_static_option('authorizenet_set_merchant_transaction_id'));
        $authorizenet->setCurrency(self::get_global_currency());
        $authorizenet->setEnv(get_static_option('authorizenet_test_mode'));

        return $authorizenet;
    }




}