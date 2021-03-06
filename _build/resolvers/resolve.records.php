<?php

/** @var modX|xPDO $modx */
$modx =& $transport->xpdo;
$success = false;

// load package
$modelPath = $modx->getOption('simplecart.core_path', null, $modx->getOption('core_path') . 'components/simplecart/') . 'model/';
$modx->addPackage('simplecart', $modelPath);

switch($options[xPDOTransport::PACKAGE_ACTION]) {

    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:

        $modx->log(modX::LOG_LEVEL_INFO, 'Creating payment gateway records...');

        // get count for next sort
        $count = $modx->getCount('simpleCartMethod', array('type' => 'payment'));
        $properties = array();

        $modx->log(modX::LOG_LEVEL_INFO, 'Currently ' . $count .' method(s) installed...');

        // create paypal payment method
		$method = $modx->getObject('simpleCartMethod', array('name' => 'authorizenet', 'type' => 'payment'));
		if(empty($method) || !is_object($method)) {

            $modx->log(modX::LOG_LEVEL_INFO, '... Creating Gateway records');

			$method = $modx->newObject('simpleCartMethod');
			$method->set('name', 'authorizenet');
			$method->set('price_add', null);
			$method->set('type', 'payment');
			$method->set('sort_order', ($count+2));
			$method->set('ignorefree', false);
			$method->set('allowremarks', false);
			$method->set('default', false);
			$method->set('active', false);
            $method->save();
		}

        $list = array(
            'currency' => 'USD',
            'login_id' => '',
            'client_key' => '',
            'transaction_key' => '',
            'test_mode' => 1,
            'form_chunk' => 'scAuthorizeNetForm'
        );

        foreach ($list as $key => $defaultValue) {

            // add some config records
            $property = $modx->getObject('simpleCartMethodProperty', array('method' => $method->get('id'), 'name' => $key));
            if (empty($property) || !is_object($property)) {

                $modx->log(modX::LOG_LEVEL_INFO, '... Creating "' . $key . '" property for Authorize.net method');

                $property = $modx->newObject('simpleCartMethodProperty');
                $property->set('method', $method->get('id'));
                $property->set('name', $key);
                $property->set('value', $defaultValue);
                $property->save();
            }
        }

        $removed = [
            'hash_secret',
            'live_endpoint',
            'developer_endpoint',
        ];
        foreach ($removed as $removedPropertyKey) {
            $removedProperty = $modx->getObject('simpleCartMethodProperty', ['method' => $method->get('id'), 'name' => $removedPropertyKey]);
            if ($removedProperty instanceof simpleCartMethodProperty) {
                $modx->log(modX::LOG_LEVEL_INFO, '... Removed no longer used "' . $removedPropertyKey . '" property (old value: ' . $removedProperty->get('value') . ')');
                $removedProperty->remove();
            }
        }

        $success = true;
        break;

    case xPDOTransport::ACTION_UNINSTALL:

        $modx->log(modX::LOG_LEVEL_INFO, 'Remove Authorize.net method records...');

        /** @var simpleCartMethod $method */
        $method = $modx->getObject('simpleCartMethod', array('name' => 'authorizenet', 'type' => 'payment'));
		if(!empty($method) || is_object($method)) {
            $method->remove();
        }

        $success = true;
        break;
}

return $success;