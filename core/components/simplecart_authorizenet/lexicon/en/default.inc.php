<?php

$_lang['simplecart.methods.payment.authorizenet'] = "Authorize.net";
$_lang['simplecart.methods.payment.authorizenet.desc'] = "Pay your order online with a credit card.";
$_lang['simplecart.methods.payment.authorizenet.orderdesc'] = "You've completed your order with a credit card. We successfully have received the payment and your order will be shipped.";

// properties
$_lang['simplecart.methods.payment.authorizenet.property_login_id'] = "Login ID";
$_lang['simplecart.methods.payment.authorizenet.property_login_id.desc'] = "The API Login ID provided through the Authorize.net Merchant Dashboard (or Sandbox).";

$_lang['simplecart.methods.payment.authorizenet.property_transaction_key'] = "Transaction Key";
$_lang['simplecart.methods.payment.authorizenet.property_transaction_key.desc'] = "Enter the Transaction Key provided through the Authorize.net Merchant Dashboard (or Sandbox).";

$_lang['simplecart.methods.payment.authorizenet.property_test_mode'] = "Test Mode";
$_lang['simplecart.methods.payment.authorizenet.property_test_mode.desc'] = "Set to 1 to to enable test/developer mode, and 0 to enable live transactions. ";

$_lang['simplecart.methods.payment.authorizenet.property_hash_secret'] = "MD5 Hash Secret";
$_lang['simplecart.methods.payment.authorizenet.property_hash_secret.desc'] = "The hash secret to use in validating MD5 signatures. <strong>This needs to match the MD5 Hash as configured in the Sandbox/Merchant dashboard under Account > MD5 Hash</strong>.";
