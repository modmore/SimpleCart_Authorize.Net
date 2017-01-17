<?php

$_lang['simplecart.methods.payment.authorizenet'] = "Authorize.net";
$_lang['simplecart.methods.payment.authorizenet.desc'] = "Betaal uw order online met een credit card.";
$_lang['simplecart.methods.payment.authorizenet.orderdesc'] = "Uw bestelling is betaald met een credit card. De betaling is succesvol ontvangen en de order zal nu worden verwerkt.";

// properties
$_lang['simplecart.methods.payment.authorizenet.property_login_id'] = "Login ID";
$_lang['simplecart.methods.payment.authorizenet.property_login_id.desc'] = "De Login ID voor de API, beschikbaar via het Authorize.net Merchant Dashboard (of Sandbox).";

$_lang['simplecart.methods.payment.authorizenet.property_transaction_key'] = "Transaction Key";
$_lang['simplecart.methods.payment.authorizenet.property_transaction_key.desc'] = "De Transaction Key, te vinden in het Authorize.net Merchant Dashboard (of Sandbox).";

$_lang['simplecart.methods.payment.authorizenet.property_test_mode'] = "Test Modus";
$_lang['simplecart.methods.payment.authorizenet.property_test_mode.desc'] = "Stel in op 1 om de test/developer modus in te schakelen, en stel in op 0 om live transacties te gebruiken. ";

$_lang['simplecart.methods.payment.authorizenet.property_hash_secret'] = "MD5 Hash Secret";
$_lang['simplecart.methods.payment.authorizenet.property_hash_secret.desc'] = "De zogenaamde Hash Secret welke gebruikt wordt om de MD5 handtekeningen te verifiëren. <strong>Dit moet overeenkomen met de MD5 Hash zoals geconfigureerd in de Sandbox/Merchant Dashboard onder Account > MD5 Hash</strong>.";
