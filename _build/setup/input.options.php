<?php

$fields = array(
	'sc-so-field1' => array(
		'type' => 'text',
		'name' => 'currency',
		'label' => 'Currency',
		'description' => 'The currency used to pay inside PayPal. Note: always should be USD for Sandbox Mode.',
		'default' => 'USD',
	),
	'sc-so-field2' => array(
		'type' => 'text',
		'name' => 'hash_secret',
		'label' => 'Hash Secret',
		'description' => 'The MD5 Hash Secret used for validating if the response from Authorize.net is genuine. Needs to match what is set in the Authorize.net merchant dashboard.',
		'default' => '',
	),
	'sc-so-field3' => array(
		'type' => 'text',
		'name' => 'login_id',
		'label' => 'Login ID',
		'description' => 'The API Login ID, available from the Authorize.net merchant dashboard.',
		'default' => '',
	),
	'sc-so-field4' => array(
		'type' => 'text',
		'name' => 'transaction_key',
		'label' => 'Transaction Key',
		'description' => 'The API Transaction Key, available from the Authorize.net merchant dashboard.',
		'default' => '',
	),
	'sc-so-field6' => array(
		'type' => 'select',
		'name' => 'test_mode',
		'label' => 'Test mode',
		'choices' => array(
			'0' => 'No',
			'1' => 'Yes',
		),
		'description' => 'Determines what endpoint to use in talking to Authorize.net. Set to Yes for using the sandbox, or No for the live service. ',
		'default' => '1',
	),
);

switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
		
		$output = '<div style="width:100%;min-width:100%;margin:-15px;">
		<table cellspacing="0" cellpadding="0" style="width:100%;">';

		$i = 1;
		foreach ($fields as $id => $attr) {
			
			if ($i % 2 == 1) {
				$output .= '<tr>';
			}
			
			$output .= '<td style="width:5%;">&nbsp;</td>';
			$output .= '<td valign="top" style="width:45%;vertical-align:top;">';
			$output .= '<div class="x-form-item " tabindex="' . $i . '" id="ext-gen-' . $id . '">
				<label for="ext-comp-' . $id . '" style="width:97%;" class="x-form-item-label" id="ext-gen-' . $id . '">' . $attr['label'] . ':</label>
				<div class="x-form-element" id="x-form-el-ext-comp-' . $id . '" style="width:97%;padding-left:0;">';
			
			switch ($attr['type']) {
				case 'select':
					$output .= '<select name="' . $attr['name'] . '" class="x-form-text x-form-field modx-combo x-form-focus" style="width:100%;height:35px;margin-top:-2px;">';
					foreach ($attr['choices'] as $val => $label) {
						$selected = ($val == $attr['default']) ? ' selected="selected"' : '';
						$output .= '<option value="' . $val . '"' . $selected . '>' . $label . '</option>';
					}
					$output .= '</select>';
					break;
					
				case 'text':
				default:
					$output .= '<input type="text" name="' . $attr['name'] . '"' . ((isset($attr['default']) && !empty($attr['default'])) ? ' value="' . $attr['default'] . '"' : '') . ' autocomplete="on" msgtarget="under" id="ext-comp-' . $id . '" class="x-form-text x-form-field x-form-text-field" style="width:100%;">';
					break;
			}
			$output .= '</div>
				<div class="x-form-clear-left"></div>
			</div>';
			
			if (isset($attr['description']) && !empty($attr['description'])) {
				$output .= '<label for="ext-comp-' . $id . '" class=" desc-under" style="padding-top:8px; width:98%;">' . $attr['description'] . '</label>';
			}
			
			$output .= '</td>';
			
			if ($i % 2 == 0) {
				$output .= '</tr>';
			}
			
			$i++;
		}

		$output .= '</table>
		</div>

		<script type="text/javascript">
			var win = Ext.getCmp("modx-window-setupoptions");
				win.config.autoHeight = true;
				win.setWidth(750);
				win.render();
				win.center();
		</script>';

		break;
		
    case xPDOTransport::ACTION_UPGRADE:
    case xPDOTransport::ACTION_UNINSTALL:
		// nothing yet
		break;
}

return $output;