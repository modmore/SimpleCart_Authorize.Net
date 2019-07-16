<?php

/**
 * @var modX|xPDO $modx
 */
$modx =& $transport->xpdo;
$success = false;

switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:

        // load package
        $modelPath = $modx->getOption('simplecart.core_path', null, $modx->getOption('core_path') . 'components/simplecart/') . 'model/';
        $modx->addPackage('simplecart', $modelPath);

        /** @var simpleCartMethod $method */
        $method = $modx->getObject('simpleCartMethod', array('name' => 'authorizenet', 'type' => 'payment'));
		if(empty($method) || !is_object($method)) {
            $modx->log(modX::LOG_LEVEL_ERROR, '[SimpleCart] Failed to find newly created record for the Authorize.net payment method');
            return false;
        }

        $configs = array(
            'currency',
            'client_key',
            'login_id',
            'transaction_key',
            'test_mode',
        );

        foreach ($configs as $key) {
            if (isset($options[$key])) {
                /** @var simpleCartMethodProperty $property */
                $property = $modx->getObject('simpleCartMethodProperty', array('method' => $method->get('id'), 'name' => $key));
                if (!empty($property) && is_object($property) && !empty($options[$key])) {
                    $property->set('value', $options[$key]);
                    $property->save();
                }
            }
        }

        $success = true;
        break;

    case xPDOTransport::ACTION_UNINSTALL:

        break;
}

return $success;