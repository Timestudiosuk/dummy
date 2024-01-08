<?php
/**
 * Moz deactivation template.
 *
 * @package SmartCrawl
 */

?>

<form method='post'>

	<?php
	$this->render_view(
		'disabled-component-inner',
		array(
			'content'      => esc_html__(
				'Moz provides reports that tell you how your site stacks up against the competition with all of
the important SEO measurement tools - ranking, links, and much more.',
				'wds'
			),
			'component'    => 'moz',
			'button_text'  => esc_html__( 'Activate', 'wds' ),
			'nonce_action' => 'wds-autolinks-nonce',
		)
	);
	?>

</form>