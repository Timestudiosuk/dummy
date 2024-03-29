<?php
/**
 * Optin form settings
 *
 * @package Hustle
 */

?>
<div class="sui-box">

	<div class="sui-box-body">

		<?php
		// ROW: Buttons Layout.
		$this->render(
			'admin/commons/sui-wizard/tab-appearance/row-customize-elements/optin_form/form-layout',
			array(
				'key'                => $key,
				'settings'           => $settings,
				'is_optin'           => $is_optin,
				'device'             => $device,
				'smallcaps_singular' => $smallcaps_singular,
			)
		);

		// ROW: Alignment.
		$this->render(
			'admin/commons/sui-wizard/tab-appearance/row-customize-elements/optin_form/form-fields',
			array(
				'key'                => $key,
				'settings'           => $settings,
				'is_optin'           => $is_optin,
				'device'             => $device,
				'smallcaps_singular' => $smallcaps_singular,
			)
		);
		?>

	</div>

</div>