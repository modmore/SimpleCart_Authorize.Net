<?php
/* @var modX $modx */

if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_UPGRADE:
        case xPDOTransport::ACTION_INSTALL:
            $modx =& $object->xpdo;

            $corePath = $modx->getOption('core_path') . 'components/simplecart_authorizenet/';

            $certs = file_get_contents('https://curl.haxx.se/ca/cacert.pem');
            if (!empty($certs)) {
                if (false !== file_put_contents($corePath . 'vendor/guzzle/guzzle/src/Guzzle/Http/Resources/cacert.pem', $certs)) {
                    $modx->log(modX::LOG_LEVEL_INFO, 'Updated cacert.pem for legacy integrations');
                }
                else {
                    $modx->log(modX::LOG_LEVEL_WARN, 'Could not write updated cacert.pem');
                }
            }
            else {
                $modx->log(modX::LOG_LEVEL_WARN, 'Could not download latest cacert.pem');
            }

            break;
    }

}
return true;

