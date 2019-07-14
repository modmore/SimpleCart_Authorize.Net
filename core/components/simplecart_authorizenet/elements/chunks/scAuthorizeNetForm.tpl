<style type="text/css">
    .sc-authnet-field > label {
        display: block;
        width: 100%;
    }
</style>
<div class="sc-authnet-gateway">

    <input type="hidden" name="dataValue" id="dataValue">
    <input type="hidden" name="dataDescriptor" id="dataDescriptor">


    <div class="sc-authnet-field sc-authnet-field-number">
        <label for="authnet_cardNumber">Card Number:</label>
        <input type="text" id="authnet_cardNumber" autocomplete="cc-number" placeholder="1234" maxlength="24" size="40">
    </div>

    <div class="sc-authnet-field sc-authnet-field-expiration">
        <label for="authnet_expMonth">Expiration Date:</label>
        <input type="number" id="authnet_expMonth" autocomplete="cc-exp-month" min="1" max="12" size="10" maxlength="2" placeholder="MM">
        <span class="sc-authnet-field-expiration-separator">/</span>
        <input type="number" id="authnet_expYear" autocomplete="cc-exp-year" min="2019" max="2050" size="20" maxlength="4" placeholder="YYYY">
    </div>
    <div class="sc-authnet-field sc-authnet-field-cardCode">
        <label for="authnet_cardCode">Security Code</label>
        <input type="number" id="authnet_cardCode" autocomplete="cc-exp-csc">
    </div>

    <script type="text/javascript" src="[[+js_url]]" charset="utf-8"></script>
    <script type="text/javascript">

        function initAuthNetListener() {
            var form = document.getElementById('simplecartCheckout');
            form.addEventListener('submit', function(e) {
                if (form.elements.paymentMethod &&
                    (Number(form.elements.paymentMethod.value) == [[+method_id]])
                ) {
                    e.preventDefault();
                    sendPaymentDataToAnet();
                    return false;
                }
            })
        }

        if (document.readyState != 'loading') {
            initAuthNetListener();
        }
        else {
            document.addEventListener('DOMContentLoaded', initAuthNetListener);
        }


        function sendPaymentDataToAnet() {
            var authData = { };
            authData.clientKey = "[[+client_key]]";
            authData.apiLoginID = "[[+login_id]]";

            var cardData = { };
            cardData.cardNumber = document.getElementById("authnet_cardNumber").value;
            cardData.month = document.getElementById("authnet_expMonth").value;
            cardData.year = document.getElementById("authnet_expYear").value;
            cardData.cardCode = document.getElementById("authnet_cardCode").value;


            var secureData = { };
            secureData.authData = authData;
            secureData.cardData = cardData;

            // console.log('sendPaymentDataToAnet()', secureData);

            Accept.dispatchData(secureData, authNetResponseHandler);
        }

        function authNetResponseHandler (response) {
            console.log('authNetResponseHandler()', response);

            if (response.messages.resultCode === "Error") {
                var i = 0,
                    message = '';
                console.error(response.messages);

                // Ignore "Accept.js encryption failed" error, 99% likely caused by js errors elsewhere on the page
                if (response.messages.message[0].code !== 'E_WC_14') {
                    while (i < response.messages.message.length) {
                        message += response.messages.message[i].text;
                        i++;
                    }

                    window.alert(message);
                    return;
                }
            }


            document.getElementById("dataDescriptor").value = response.opaqueData.dataDescriptor;
            document.getElementById("dataValue").value = response.opaqueData.dataValue;

            document.getElementById("authnet_cardNumber").value = "";
            document.getElementById("authnet_expMonth").value = "";
            document.getElementById("authnet_expYear").value = "";
            document.getElementById("authnet_cardCode").value = "";

            document.getElementById('simplecartCheckout').submit();
        }

    </script>
</div>