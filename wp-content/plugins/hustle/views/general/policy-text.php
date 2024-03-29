<?php
/**
 * Policy text template.
 *
 * @package Hustle
 * @since 4.0.0
 */

?>
<div class="wp-suggested-text">
	<h2><?php esc_html_e( 'Which modules collect personal data?', 'hustle' ); ?></h2>
	<p class="privacy-policy-tutorial">
		<?php
		esc_html_e(
			'If you use Hustle to create and embed any Pop-up, Embed, Slide-in, or Social share module, you may need to mention it here to properly distinguish it from other plugins.',
			'hustle'
		);
		?>
	</p>

	<h2><?php esc_html_e( 'What personal data do we collect and why?', 'hustle' ); ?></h2>
	<p class="privacy-policy-tutorial">
		<?php
		printf(
			/* translators: 1. opening 'strong' tag, 2. closing 'strong' tag */
			esc_html__(
				'By default, Hustle captures the %1$sIP Address%2$s for each conversion and for each view only if the "tracking" functionality is enabled. Other personal data such as your %1$sname%2$s and %1$semail address%2$s may also be captured,
	depending on the form fields.',
				'hustle'
			),
			'<strong>',
			'</strong>'
		);
		?>
	</p>
	<p class="privacy-policy-tutorial">
		<i>
			<?php
			esc_html_e(
				'Note: In this section you should include any personal data you collected and which form captures personal data to give users more relevant information. You should also include an explanation of why this data is needed. The explanation must note either the legal basis for your data collection and retention of the active consent the user has given.',
				'hustle'
			);
			?>
		</i>
	</p>
	<p>
		<strong class="privacy-policy-tutorial"><?php esc_html_e( 'Suggested text: ', 'hustle' ); ?></strong>
		<?php
		printf(
			/* translators: 1. opening 'strong' tag, 2. closing 'strong' tag */
			esc_html__(
				'When visitors or users submit a form or view a module, we capture the %1$sIP Address%2$s for analyisis purposes. We also capture the %1$semail address%2$s and might capture other personal data included in the form fields.',
				'hustle'
			),
			'<strong>',
			'</strong>'
		);
		?>
	</p>

	<h2><?php esc_html_e( 'How long we retain your data', 'hustle' ); ?></h2>
	<p class="privacy-policy-tutorial">
		<?php
		printf(
			/* translators: 1. opening 'strong' tag, 2. closing 'strong' tag */
			esc_html__(
				'By default Hustle retains all form submissions and tracking data %1$sforever%2$s. You can delete the stored data in %1$sHustle%2$s &raquo; %1$sSettings%2$s &raquo;
				%1$sPrivacy Settings%2$s, and under each module\'s settings.',
				'hustle'
			),
			'<strong>',
			'</strong>'
		);
		?>
	</p>

	<p>
		<strong class="privacy-policy-tutorial"><?php esc_html_e( 'Suggested text: ', 'hustle' ); ?></strong>
		<?php esc_html_e( 'When visitors or users submit a form or view a module we retain the data for 30 days.', 'hustle' ); ?>
	</p>
	<h2><?php esc_html_e( 'Where we send your data', 'hustle' ); ?></h2>
	<p>
		<strong class="privacy-policy-tutorial"><?php esc_html_e( 'Suggested text: ', 'hustle' ); ?></strong>
		<?php esc_html_e( 'All collected data might be shown publicly and we send it to our workers or contractors to perform necessary actions based on the form submission.', 'hustle' ); ?>
	</p>
	<h2><?php esc_html_e( 'Third Parties', 'hustle' ); ?></h2>
	<p class="privacy-policy-tutorial">
		<?php
		esc_html_e(
			'If your forms use either built-in or external third-party services, in this section you should mention any third parties and its privacy policy.',
			'hustle'
		);
		?>
	</p>
	<p class="privacy-policy-tutorial">
		<?php /* translators: Plugin name */ echo esc_html( sprintf( __( 'By default %s optionally use these third-party integrations:', 'hustle' ), Opt_In_Utils::get_plugin_name() ) ); ?>
	</p>
	<ul class="privacy-policy-tutorial">
		<li><?php esc_html_e( 'ActiveCampaign. Enabled when you activate and setup ActiveCampaign on Email Collection settings.', 'hustle' ); ?></li>
		<li><?php esc_html_e( 'Aweber. Enabled when you activate and setup Aweber on Email Collection settings.', 'hustle' ); ?></li>
		<li><?php esc_html_e( 'Campaign Monitor. Enabled when you activate and setup Campaign Monitor on Email Collection settings.', 'hustle' ); ?></li>
		<li><?php esc_html_e( 'Constant Contact. Enabled when you activate and setup Constant Contact on Email Collection settings.', 'hustle' ); ?></li>
		<li><?php esc_html_e( 'ConvertKit. Enabled when you activate and setup ConvertKit on Email Collection settings.', 'hustle' ); ?></li>
		<li><?php esc_html_e( 'e-Newsletter. Enabled when you activate and setup e-Newsletter on Email Collection settings.', 'hustle' ); ?></li>
		<li><?php esc_html_e( 'GetResponse. Enabled when you activate and setup GetResponse on Email Collection settings.', 'hustle' ); ?></li>
		<li><?php esc_html_e( 'HubSpot. Enabled when you activate and setup HubSpot on Email Collection settings.', 'hustle' ); ?></li>
		<li><?php esc_html_e( 'iContact. Enabled when you activate and setup iContact on Email Collection settings.', 'hustle' ); ?></li>
		<li><?php esc_html_e( 'Keap. Enabled when you activate and setup Keap on Email Collection settings.', 'hustle' ); ?></li>
		<li><?php esc_html_e( 'Mad Mimi. Enabled when you activate and setup Mad Mimi on Email Collection settings.', 'hustle' ); ?></li>
		<li><?php esc_html_e( 'Mailchimp. Enabled when you activate and setup Mailchimp on Email Collection settings.', 'hustle' ); ?></li>
		<li><?php esc_html_e( 'MailerLite. Enabled when you activate and setup MailerLite on Email Collection settings.', 'hustle' ); ?></li>
		<li><?php esc_html_e( 'Mautic. Enabled when you activate and setup Mautic on Email Collection settings.', 'hustle' ); ?></li>
		<li><?php esc_html_e( 'reCAPTCHA. Enabled when you activate and setup reCAPTCHA on non-Social sharing modules.', 'hustle' ); ?></li>
		<li><?php esc_html_e( 'Pinterest. Enabled when you activate and setup Pinterest in Social Share Module.', 'hustle' ); ?></li>
		<li><?php esc_html_e( 'SendGrid. Enabled when you activated and setup SendGrid on Email Collection settings.', 'hustle' ); ?></li>
		<li><?php esc_html_e( 'Brevo. Enabled when you activated and setup Brevo on Email Collection settings.', 'hustle' ); ?></li>
		<li><?php esc_html_e( 'Sendy. Enabled when you activated and setup Sendy on Email Collection settings.', 'hustle' ); ?></li>
		<li><?php esc_html_e( 'Zapier. Enabled when you activated and setup Zapier on Email Collection settings.', 'hustle' ); ?></li>
		<?php echo esc_html( $external_integrations_list ); ?>
	</ul>
	<p>
		<strong class="privacy-policy-tutorial"><?php esc_html_e( 'Suggested text: ', 'hustle' ); ?></strong>
	<?php
	$integrations = array(
		'ActiveCampaign'   => 'https://www.activecampaign.com/privacy-policy/',
		'Aweber'           => 'https://www.aweber.com/privacy.htm',
		'Campaign Monitor' => 'https://www.campaignmonitor.com/policies/#privacy-policy',
		'Constant Contact' => 'https://www.endurance.com/privacy',
		'ConvertKit'       => 'https://convertkit.com/privacy/',
		'GetResponse'      => 'https://www.getresponse.com/legal/privacy.html?lang=en',
		'HubSpot'          => 'https://legal.hubspot.com/legal-stuff',
		'iContact'         => 'https://www.icontact.com/legal/privacy',
		'Keap'             => 'https://keap.com/legal/privacy-policy',
		'Mad Mimi'         => 'https://madmimi.com/legal/terms',
		'Mailchimp'        => 'https://mailchimp.com/legal/privacy/',
		'MailerLite'       => 'https://www.mailerlite.com/privacy-policy',
		'Mautic'           => 'https://www.mautic.org/privacy-policy/',
		'SendGrid'         => 'https://sendgrid.com/policies/privacy/',
		'Brevo'            => 'https://www.brevo.com/legal/privacypolicy/',
		'Sendy'            => 'https://sendy.co/privacy-policy',
	);

	foreach ( $integrations as $integration_name => $privacy_url ) {
		?>
		<p><?php /* translators: 1. Company name 2. Privacy link. */ printf( esc_html__( 'We use %1$s to manage our subscriber lists. Their privacy policy can be found here : %2$s.', 'hustle' ), esc_attr( $integration_name ), esc_url( $privacy_url ) ); ?></p>
		<?php
	}
	?>
	<p><?php /* translators: 1. Company name 2. Privacy link. */ printf( esc_html__( 'We use %1$s to manage our subscriber. You can learn more about it here %2$s.', 'hustle' ), 'e-Newsletter', 'https://wpmudev.com/project/e-newsletter/' ); ?></p>
	<p><?php /* translators: 1. Company name 2. Privacy link. */ printf( esc_html__( 'We use %1$s to share media. Their privacy policy can be found here : %2$s.', 'hustle' ), 'Pinterest', 'https://policy.pinterest.com/privacy-policy/' ); ?></p>
	<p><?php /* translators: 1. Company name 2. Privacy link. */ printf( esc_html__( 'We use %1$s to protect your website from fraud and abuse. Their privacy policy can be found here : %2$s.', 'hustle' ), 'reCAPTCHA', 'https://policies.google.com/privacy' ); ?></p>
	<p><?php /* translators: 1. Company name 2. Privacy link. */ printf( esc_html__( 'We use %1$s to manage our integration data. Their privacy policy can be found here : %2$s.', 'hustle' ), 'Zapier', 'https://zapier.com/privacy/' ); ?></p>
	<?php echo esc_html( $external_integrations_privacy_url_list ); ?>

	<h2><?php esc_html_e( 'Cookies', 'hustle' ); ?></h2>
	<p class="privacy-policy-tutorial">
		<?php
		esc_html_e(
			'By default Hustle uses cookies to count how many times each module is visualized. Cookies might be used to handle other features such as display settings, used when a module should not be displayed for a certain time,
		whether the user commented before, whether the user has subscribed, among others, if their related settings are enabled.',
			'hustle'
		);
		?>
	</p>


</div>
