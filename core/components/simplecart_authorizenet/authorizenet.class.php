<?php
require_once 'vendor/autoload.php';
class SimpleCartAuthorizenetPaymentGateway extends SimpleCartGateway {
    public function submit() {
        $gateway = $this->initAuthorizeNet();
        if (!$gateway) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[SimpleCart/Authorize.Net] Unable of instantiating the Authorize.net gateway', '', __METHOD__, __FILE__, __LINE__);
            return false;
        }

        $parameters = $this->getParameters();
        $response = $gateway->purchase($parameters)->send();

        if ($response->isSuccessful()) {

            // Payment was successful
            print_r($response);

        } elseif ($response->isRedirect()) {

            // Redirect to offsite payment gateway
            $response->redirect();

        } else {

            // Payment failed
            echo $response->getMessage();
        }

        return false;
    }

    public function verify() {
        $gateway = $this->initAuthorizeNet();
        if (!$gateway) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[SimpleCart/Authorize.Net] Unable of instantiating the Authorize.net gateway', '', __METHOD__, __FILE__, __LINE__);
            return false;
        }

        $parameters = $this->getParameters();
        $response = $gateway->completePurchase($parameters)->send();

        var_dump($response->getData(), $response->getMessage(), $response->isSuccessful(), $response->getTransactionReference());

        $this->order->addLog('Authorize.net Reference', $response->getTransactionReference());
        $this->order->addLog('Authorize.net Message', $response->getMessage());

        if ($response->isSuccessful()) {
            $this->order->setStatus('finished');
            $this->order->save();
            return true;
        }
        else {
            $this->order->setStatus('payment_failed');
            $this->order->save();
            return false;
        }
    }

    /**
     * @return \Omnipay\AuthorizeNet\SIMGateway
     */
    protected function initAuthorizeNet() {
        $loginId = $this->getProperty('login_id');
        $transactionKey = $this->getProperty('transaction_key');
        $testMode = (bool)$this->getProperty('test_mode', true, 'isset');

        /** @var Omnipay\AuthorizeNet\SIMGateway $gateway */
        $gateway = \Omnipay\Omnipay::create('AuthorizeNet_SIM');
        $gateway->setApiLoginId($loginId);
        $gateway->setTransactionKey($transactionKey);
        $gateway->setTestMode($testMode);
        $gateway->setDeveloperMode($testMode);

        return $gateway;
    }

    /**
     * @return \Omnipay\Common\CreditCard
     */
    public function getCard()
    {
        $cardData = array();
        $billingAddress = $address = $this->order->getAddress('order');
        if (is_array($address)) {
            $cardData = array_merge($cardData, array(
                'firstName' => $address['firstname'],
                'lastName' => $address['lastname'],
                'email' => $address['email'],
                'type' => 'billing',
                'billingAddress1' => $address['address1'],
                'billingAddress2' => $address['address2'] . $address['address3'],
                'billingCity' => $address['city'],
                'billingPostcode' => $address['zip'],
                'billingState' => $address['state'],
                'billingCountry' => $address['country'],
                'billingPhone' => $address['phone'],
            ));
        }

        $address = $this->order->getAddress('delivery');
        if (!is_array($address)) {
            $address = $billingAddress;
        }
        if (is_array($address)) {
            $cardData = array_merge($cardData, array(
                'firstName' => $address['firstname'],
                'lastName' => $address['lastname'],
                'email' => $address['email'],
                'type' => 'delivery',
                'shippingAddress1' => $address['address1'],
                'shippingAddress2' => $address['address2'] . $address['address3'],
                'shippingCity' => $address['city'],
                'shippingPostcode' => $address['zip'],
                'shippingState' => $address['state'],
                'shippingCountry' => $address['country'],
                'shippingPhone' => $address['phone'],
            ));
        }

        $card = new \Omnipay\Common\CreditCard($cardData);

        return $card;
    }

    public function getCurrency()
    {
        $api_currency = $this->simplecart->currency->get('name');
        if(empty($api_currency)) {
            $api_currency = $this->getProperty('currency', 'EUR');
        }
        return $api_currency;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        $parameters = array(
            'amount' => $this->order->get('total'),
            'returnUrl' => $this->getRedirectUrl(),
            'currency' => $this->getCurrency(),
            'card' => $this->getCard(),
        );

        return $parameters;
    }
}