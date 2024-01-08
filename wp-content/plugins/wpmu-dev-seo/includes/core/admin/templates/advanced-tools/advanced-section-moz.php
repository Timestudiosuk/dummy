<?php

namespace SmartCrawl;

$active_tab = empty( $active_tab ) ? false : $active_tab;

if ( ! Settings::get_setting( 'moz' ) ) {

	$this->render_view(
		'vertical-tab',
		array(
			'tab_id'       => 'tab_moz',
			'tab_name'     => __( 'Moz', 'wds' ),
			'is_active'    => 'tab_moz' === $active_tab,
			'button_text'  => false,
			'tab_sections' => array(
				array(
					'section_template' => 'advanced-tools/advanced-section-moz-disabled',
					'section_args'     => array(),
				),
			),
		)
	);

	return;
}

$this->render_view(
	'vertical-tab',
	array(
		'tab_id'       => 'tab_moz_main',
		'tab_name'     => __( 'Moz', 'wds' ),
		'is_active'    => 'tab_moz' === $active_tab,
		'button_text'  => false,
		'tab_sections' => array(
			array(
				'section_template' => 'advanced-tools/advanced-section-moz-details',
				'section_args'     => array(),
			),
		),
	)
);

$this->render_view(
	'vertical-tab',
	array(
		'tab_id'       => 'tab_moz_settings',
		'tab_name'     => esc_html__( 'Settings', 'wds' ),
		'is_active'    => 'tab_moz' === $active_tab,
		'button_text'  => false,
		'tab_sections' => array(
			array(
				'section_template' => 'advanced-tools/advanced-section-moz-settings',
			),
		),
	)
);