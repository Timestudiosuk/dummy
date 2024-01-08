import React from 'react';
import ReactDom from 'react-dom/client';
import ErrorBoundary from './components/error-boundry';
import DataResetButton from './components/settings/data-reset-button';
import MultisiteResetButton from './components/settings/multisite-reset-button';
import ModulesSettings from './components/settings/plugin-modules/modules-settings';

(function ($) {
	const resetButton = document.getElementById(
		'wds-data-reset-button-placeholder'
	);
	if (resetButton) {
		const root = ReactDom.createRoot(resetButton);
		root.render(
			<ErrorBoundary>
				<DataResetButton />
			</ErrorBoundary>
		);
	}

	const multisiteResetButton = document.getElementById(
		'wds-multisite-reset-button-placeholder'
	);
	if (multisiteResetButton) {
		const root = ReactDom.createRoot(multisiteResetButton);
		root.render(
			<ErrorBoundary>
				<MultisiteResetButton />
			</ErrorBoundary>
		);
	}

	const pluginModules = document.getElementById('wds-plugin-modules');
	if (pluginModules) {
		const root = ReactDom.createRoot(pluginModules);
		root.render(
			<ErrorBoundary>
				<ModulesSettings />
			</ErrorBoundary>
		);
	}

	window.Wds = window.Wds || {};

	function addCustomMetaTagField() {
		const $this = $(this),
			$container = $this.closest('.wds-custom-meta-tags'),
			$newInput = $container
				.find('.wds-custom-meta-tag:first-of-type')
				.clone();

		$newInput.insertBefore($this);
		$newInput.find('input').val('').trigger('focus');
	}

	function init() {
		window.Wds.styleable_file_input();
		$(document).on(
			'click',
			'.wds-custom-meta-tags button',
			addCustomMetaTagField
		);

		Wds.vertical_tabs();
	}

	$(init);
})(jQuery);
