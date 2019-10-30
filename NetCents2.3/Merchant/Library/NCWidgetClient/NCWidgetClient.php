<?php

namespace NetCents\Merchant\Library\NCWidgetClient;

use NetCents\Merchant\Library\NCPaymentData;

include_once('httpful.phar');

class NCWidgetClient
{
    private $paymentData;

    function __construct($paymentData)
    {
        $this->paymentData = $paymentData;
    }

    private function nc_get_api_url($host_url)
    {
        $parsed = parse_url($host_url);
        if ($host_url == 'https://merchant.net-cents.com') {
            $api_url = 'https://api.net-cents.com';
        } else if ($host_url == 'https://gateway-staging.net-cents.com') {
            $api_url = 'https://api-staging.net-cents.com';
        } else if ($host_url == 'https://gateway-test.net-cents.com') {
            $api_url = 'https://api-test.net-cents.com';
        } else {
            $api_url = $parsed['scheme'] . '://' . 'api.' . $parsed['host'];
        }
        return $api_url;
    }

    function encryptData()
    {
        $payload = array(
            'external_id' => $this->paymentData->externalId,
            'amount' => $this->paymentData->amount,
            'currency_iso' => $this->paymentData->currencyIso,
            'callback_url' => $this->paymentData->callbackUrl,
            'first_name' => $this->paymentData->firstName,
            'last_name' => $this->paymentData->lastName,
            'email' => $this->paymentData->email,
            'webhook_url' => $this->paymentData->webhookUrl,
            'merchant_id' => $this->paymentData->apiKey,
            'hosted_payment_id' => $this->paymentData->widgetId,
            'data_encryption' => array(
                'external_id' => $this->paymentData->externalId,
                'amount' => $this->paymentData->amount,
                'currency_iso' => $this->paymentData->currencyIso,
                'callback_url' => $this->paymentData->callbackUrl,
                'first_name' => $this->paymentData->firstName,
                'last_name' => $this->paymentData->lastName,
                'webhook_url' => $this->paymentData->webhookUrl,
                'email' => $this->paymentData->email,
                'merchant_id' => $this->paymentData->apiKey,
                'hosted_payment_id' => $this->paymentData->widgetId,
            ),
        );
        $api_url = $this->nc_get_api_url($this->paymentData->merchantUrl);
        $formHandler =  new \Httpful\Handlers\FormHandler();
        $data = $formHandler->serialize($payload);

        $response =  \Httpful\Request::post($api_url . '/widget/v2/encrypt')
            ->body($data)
            ->addHeader('Authorization', 'Basic ' .  base64_encode($this->paymentData->apiKey . ':' . $this->paymentData->secretKey))
            ->send();
        return $response;
    }
}
