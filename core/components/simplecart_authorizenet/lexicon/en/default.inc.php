<?php

$_lang['simplecart.methods.payment.authorizenet'] = 'Authorize.net';
$_lang['simplecart.methods.payment.authorizenet.desc'] = 'Pay your order online with a credit card.';
$_lang['simplecart.methods.payment.authorizenet.orderdesc'] = 'You\'ve completed your order with a credit card. We have successfully received the payment and your order will be shipped.';

// properties
$_lang['simplecart.methods.payment.authorizenet.property_login_id'] = 'Login ID';
$_lang['simplecart.methods.payment.authorizenet.property_login_id.desc'] = 'The API Login ID provided through the Authorize.net Merchant Dashboard (or Sandbox). Find this in Account > Settings > Security Settings > General Security Settings > API Credentials & Keys.';

$_lang['simplecart.methods.payment.authorizenet.property_transaction_key'] = 'Transaction Key';
$_lang['simplecart.methods.payment.authorizenet.property_transaction_key.desc'] = 'Enter the Transaction Key provided through the Authorize.net Merchant Dashboard (or Sandbox). Find this in Account > Settings > Security Settings > General Security Settings > API Credentials & Keys, followed by choosing "New Transaction Key" under "Create New Key(s)". (Note that creating a new key invalidates any old keys that may be in use.)';

$_lang['simplecart.methods.payment.authorizenet.property_client_key'] = 'Client Key';
$_lang['simplecart.methods.payment.authorizenet.property_client_key.desc'] = 'Enter the Client Key provided through the Authorize.net Merchant Dashboard (or Sandbox) at Account > Settings > Security Settings > General Security Settings > Manage Public Client Key';

$_lang['simplecart.methods.payment.authorizenet.property_test_mode'] = 'Test Mode';
$_lang['simplecart.methods.payment.authorizenet.property_test_mode.desc'] = 'Set to 1 to to enable test/developer mode, and 0 to enable live transactions. Note that test transactions do not currently show up in the sandbox because they are marked as test requests.';

$_lang['simplecart.methods.payment.authorizenet.property_currency'] = 'Currency';
$_lang['simplecart.methods.payment.authorizenet.property_currency.desc'] = 'The 3-character alpha code for the currency to use for Authorize.net.';

$_lang['simplecart.methods.payment.authorizenet.property_form_chunk'] = 'Form Chunk';
$_lang['simplecart.methods.payment.authorizenet.property_form_chunk.desc'] = 'The name of a chunk to use for the payment form in the checkout. The default chunk, scAuthorizeNetForm, will be overwritten when you upgrade, so if you want to change the form you should make a copy and configure the name of the copy here. When you\'ve changed the chunk, it is important to be extra careful when upgrading the gateway as your chunk may need tweaks as well. If you only want to restyle the form, you can do so with just CSS.';
