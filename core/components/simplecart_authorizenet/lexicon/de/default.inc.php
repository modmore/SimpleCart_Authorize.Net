<?php

$_lang['simplecart.methods.payment.authorizenet'] = "Authorize.net";
$_lang['simplecart.methods.payment.authorizenet.desc'] = "Bezahlen Sie Ihre Bestellung online mit Kreditkarte.";
$_lang['simplecart.methods.payment.authorizenet.orderdesc'] = "Sie haben die Bestellung mit Kreditkarte abgeschlossen. Wir haben die Zahlung erhalten und Ihre Bestellung wird verschickt.";

// properties
$_lang['simplecart.methods.payment.authorizenet.property_login_id'] = "Login ID";
$_lang['simplecart.methods.payment.authorizenet.property_login_id.desc'] = "Die API Login ID, welche über das Authorize.net Merchant Dashboard (oder Sandbox) zur Verfügung gestellt wird.";

$_lang['simplecart.methods.payment.authorizenet.property_transaction_key'] = "Transaktions-Key";
$_lang['simplecart.methods.payment.authorizenet.property_transaction_key.desc'] = "Geben Sie den Transaktions-Key ein welcher über das Authorize.net Merchant Dashboard (oder Sandbox) zur Verfügung gestellt wird.";

$_lang['simplecart.methods.payment.authorizenet.property_test_mode'] = "Testmodus";
$_lang['simplecart.methods.payment.authorizenet.property_test_mode.desc'] = "Auf 1 stellen um den Test/Developer Modus zu aktivieren - 0 für Live Transaktionen. ";

$_lang['simplecart.methods.payment.authorizenet.property_hash_secret'] = "MD5 Hash Secret";
$_lang['simplecart.methods.payment.authorizenet.property_hash_secret.desc'] = "Hash-Secret zur Validierung von MD5 Signaturen. <strong>Dieses muss mit dem MD5 Hash übereinstimmen welcher im Merchant Dashboard (oder Sandbox) unter Konto > MD5 Hash konfiguriert ist</strong>.";
