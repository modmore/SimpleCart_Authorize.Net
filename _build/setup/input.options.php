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
		'name' => 'username',
		'label' => 'API Username',
		'description' => 'Enter the username from your API credentials, to gain access to your account.',
		'default' => '',
	),
	'sc-so-field3' => array(
		'type' => 'text',
		'name' => 'password',
		'label' => 'API Password',
		'description' => 'Enter the password from your API credentials, to gain access to your account.',
		'default' => '',
	),
	'sc-so-field4' => array(
		'type' => 'text',
		'name' => 'signature',
		'label' => 'API Signature',
		'description' => 'Enter the signature from your API credentials, to gain access to your account.',
		'default' => '',
	),
	'sc-so-field5' => array(
		'type' => 'select',
		'name' => 'noshipping',
		'label' => 'Disable PayPal Shipping',
		'choices' => array(
			'0' => 'No',
			'1' => 'Yes',
		),
		'description' => 'Enter YES to disable (NO to enable) shipping in PayPal. Normally this is captured by you. Default YES.',
		'default' => '1',
	),
	'sc-so-field6' => array(
		'type' => 'select',
		'name' => 'usesandbox',
		'label' => 'Sandbox mode',
		'choices' => array(
			'0' => 'No',
			'1' => 'Yes',
		),
		'description' => 'Whether or not to run PayPal in Sandbox (test) mode. Note: you need Sandbox API credentials too.',
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
					$output .= '<select name="" class="x-form-text x-form-field modx-combo x-form-focus" style="width:100%;height:35px;margin-top:-2px;">';
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

		<!-- currently in MODX 2.3.2-pl its not yet possible to fire javascript -->
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