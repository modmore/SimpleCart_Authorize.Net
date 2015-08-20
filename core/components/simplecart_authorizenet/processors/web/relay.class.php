<?php

class SimpleCartAuthorizenetRelayProcessor extends modProcessor
{
    public function process()
    {
        $sc = $this->modx->getService('simplecart', 'SimpleCart', $this->modx->getOption('simplecart.core_path', null,
                $this->modx->getOption('core_path') . 'components/simplecart/') . 'model/simplecart/');
        if ($sc instanceof SimpleCart) {

            $order = $this->modx->getObject('simpleCartOrder', array('id' => (int)$_REQUEST['id']));
            if ($order instanceof simpleCartOrder && $order->get('status') !== 'finished') {
                $fields = array();

                foreach ($_POST as $key => $value) {
                    $fields[] = '<input type="hidden" name="' . $key . '" value="' . $value . '">';
                }

                $fields[] = '<input id="backtoshopbutton" type="submit" value="Confirm Payment">';

                $fields = implode("\n", $fields);
                $url = $this->modx->makeUrl($order->get('confirmation_id'), '', '', 'full');
                $form = 'Redirecting back to the shop... <form id="backtoshop" action="' . $url . '" method="post">' . $fields . '</form>';

                $form .= '<script type="text/javascript">window.onload = function() { document.getElementById("backtoshop").submit(); document.getElementById("backtoshopbutton").style.display = "none"; }</script>';

                echo $form;
            }
            else {
                $url = $this->modx->getOption('site_url');
                echo '<p>Uh oh, could not confirm your payment. <a href="' . $url . '">Return to shop</a></p>';
            }
        }

        @session_write_close();
        exit();
    }
}

return 'SimpleCartAuthorizenetRelayProcessor';