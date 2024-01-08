import React from 'react';
import ConfigValues from '../../../es6/config-values';
import DisabledComponent from '../../../components/disabled-component';
import Button from '../../../components/button';
import { __ } from '@wordpress/i18n';
import VerticalTab from '../../../components/vertical-tab';
import UrlUtil from '../../../utils/url-util';

const isActive =
	UrlUtil.getQueryParam('tab') &&
	UrlUtil.getQueryParam('tab') === 'tab_url_redirection';

export default class RedirectDeactivated extends React.Component {
	render() {
		return (
			<VerticalTab
				title={__('URL Redirection', 'wds')}
				isActive={isActive}
			>
				<DisabledComponent
					imagePath={ConfigValues.get('image', 'redirects')}
					message={__(
						'Configure SmartCrawl to automatically redirect traffic from one URL to another. Use this tool if you have changed a page’s URL and wish to keep traffic flowing to the new page.',
						'wds'
					)}
					component="redirects"
					nonce={ConfigValues.get('settings_nonce', 'redirects')}
					referer={ConfigValues.get('referer', 'redirects')}
					button={
						<Button
							name="submit"
							type="submit"
							color="blue"
							text={__('Activate', 'wds')}
						/>
					}
					inner
				/>
			</VerticalTab>
		);
	}
}
