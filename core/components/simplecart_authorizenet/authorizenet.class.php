<?php

use Omnipay\AuthorizeNet\AIMGateway;
use Omnipay\AuthorizeNet\Message\AIMResponse;
use Omnipay\Common\CreditCard;
use Omnipay\Omnipay;

require_once 'vendor/autoload.php';
class SimpleCartAuthorizenetPaymentGateway extends SimpleCartGateway {

    public function view()
    {
        $chunk = $this->getProperty('form_chunk', 'scAuthorizeNetForm');
        return $this->modx->getChunk($chunk, [
            'js_url' => (bool)$this->getProperty('test_mode') ? 'https://jstest.authorize.net/v1/Accept.js' : 'https://js.authorize.net/v1/Accept.js',
            'login_id' => $this->getProperty('login_id'),
            'client_key' => $this->getProperty('client_key'),
            'method_id' => $this->method->get('id'),
        ]);
    }

    public function submit() {
        $this->modx->lexicon->load('simplecart:cart', 'simplecart:methods');

        $gateway = $this->initAuthorizeNet();
        if (!$gateway) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[SimpleCart/Authorize.Net] Unable of instantiating the Authorize.net gateway', '', __METHOD__, __FILE__, __LINE__);
            return false;
        }

        $parameters = $this->getParameters();
        try {
            $request = $gateway->purchase($parameters);
            /** @var AIMResponse $response */
            $response = $request->send();
        } catch (Exception $e) {
            $this->order->addLog('Auth.net Error', $e->getMessage());
            $this->modx->log(modX::LOG_LEVEL_ERROR, 'Error preparing Authorize.net transaction: ' . $e->getMessage() . ' // ' . $e->getTraceAsString() . ' // ' . print_r($parameters, true), '', __METHOD__, __FILE__, __LINE__);
            return false;
        }

        // Log information about the transaction
        $reference = $response->getTransactionReference(false);
        $this->order->addLog('Auth.net Transaction', $reference->getTransId());
        $this->order->addLog('Auth.net Result Code', $response->getResultCode());
        $this->order->addLog('Auth.net Reason Code', $response->getReasonCode());
        $this->order->addLog('Auth.net Message', $response->getMessage());
        $this->order->addLog('Auth.net Card', $reference->getCard()['number']);

        // Update the status and return true or false, depending on the state
        if ($response->isSuccessful()) {
            $this->order->addLog('Auth.net Success', 1);
            $this->order->setStatus('finished');
            $this->order->save();
            return true;
        }
        $this->order->setStatus('payment_failed');
        $this->order->save();
        return false;
    }

    public function verify() {
        return (int)$this->order->getLog('Auth.net Result Code') === 1;
    }

    /**
     * Set up the OmniPay Gateway instance for the SIM integration with Authorize.net
     *
     * @return AIMGateway
     */
    protected function initAuthorizeNet() {
        $loginId = $this->getProperty('login_id');
        $transactionKey = $this->getProperty('transaction_key');
        $testMode = (bool)$this->getProperty('test_mode', true, 'isset');

        /** @var AIMGateway $gateway */
        $gateway = Omnipay::create('AuthorizeNet_AIM');
        $gateway->setApiLoginId($loginId);
        $gateway->setTransactionKey($transactionKey);
        $gateway->setTestMode($testMode);
        $gateway->setDeveloperMode($testMode);

        return $gateway;
    }

    /**
     * Creates the OmniPay CreditCard instance; basically an array of user information. No actual cards here.
     *
     * @return CreditCard
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

        $card = new CreditCard($cardData);

        return $card;
    }

    /**
     * Grab the currency
     *
     * @return string
     */
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
        $phs = array();
        $codes = array();
        $names = array();
        /** @var simpleCartOrderProduct[] $products */
        $products = $this->order->getMany('Product');
        foreach ($products as $product) {
            $codes[] = $product->get('productcode');
            $names[] = $product->get('title');
        }
        $phs['productcodes'] = implode(', ', $codes);
        $phs['productnames'] = implode(', ', $names);

        $content = $this->modx->lexicon('simplecart.methods.yourorderat');
        $chunk = $this->modx->newObject('modChunk');
        $chunk->setCacheable(false);
        $chunk->setContent($content);
        $description = $chunk->process($phs);

        $parameters = array(
            'amount' => $this->order->get('total'),
            'invoiceNumber' => $this->order->get('id'),
            'currency' => $this->getCurrency(),
            'card' => $this->getCard(),
            'description' => $description,
            'transactionId' => $this->order->get('id'),
            'clientIp' => $_SERVER['REMOTE_ADDR'],
            'opaqueDataDescriptor' => !empty($_REQUEST['dataDescriptor']) ? $_REQUEST['dataDescriptor'] : '',
            'opaqueDataValue' => !empty($_REQUEST['dataValue']) ? $_REQUEST['dataValue'] : '',
        );

        // Add the MODX User ID if available
        if ($this->modx->user && $this->modx->user->get('id') > 0) {
            $parameters['customerId'] = $this->modx->user->get('id');
        }

        return $parameters;
    }
}