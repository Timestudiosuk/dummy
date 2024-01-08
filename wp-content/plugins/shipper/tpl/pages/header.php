<?php
/**
 * Shipper templates: shared header template
 *
 * @package shipper
 */

?>
<?php if ( shipper_is_black_friday() ) { ?>
	<div id="shipper-black-friday-notice" data-nonce="<?php echo esc_attr( wp_create_nonce( 'shipper_hide_black_friday' ) ); ?>"></div>
<?php } ?>
<div class="sui-floating-notices"></div>