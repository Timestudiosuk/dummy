<?php
/**
 * Main wrapper for the Email lists (entries/form submissions) page.
 *
 * @package Hustle
 * @since 4.0.0
 */

// Email Lists: Images.
if ( ! $this->is_branding_hidden ) :
	$image_attrs = array(
		'path'        => self::$plugin_url . 'assets/images/no-result.png',
		'retina_path' => self::$plugin_url . 'assets/images/no-result@2x.png',
	);
else :
	$image_attrs = array(
		'path'   => $this->branding_image,
		'width'  => 172,
		'height' => 192,
		'class'  => 'sui-image',
	);
endif;
?>

<div class="sui-header">
	<h1 class="sui-header-title"><?php esc_html_e( 'Email Lists', 'hustle' ); ?></h1>
	<?php $this->render( 'admin/commons/view-documentation', array( 'docs_section' => 'email-lists' ) ); ?>
</div>

<div id="hustle-floating-notifications-wrapper" class="sui-floating-notices"></div>

<?php
// Search Bar.
$this->render(
	'admin/email-lists/search-bar',
	array(
		'has_entries' => ( $is_module_selected && ! empty( $entries ) ),
		'module'      => $module,
	)
);
?>

<?php
// If a module is selected, get its entries. Show a placeholder message otherwise.
if ( $is_module_selected ) :

	$integrations_url = add_query_arg(
		array(
			'page'    => $module->get_wizard_page(),
			'id'      => $module->id,
			'section' => 'integrations',
		),
		get_admin_url( get_current_blog_id(), 'admin.php' )
	);

	$add_local_list = sprintf(
		/* translators: 1: module name, 2: opening 'a' tag with the module's edit integrations url, 3: closing 'a' tag 4. Plugin name */
		esc_html__( '%4$s\'s Local List is inactive for this %1$s. %2$sActivate Local List%3$s integration for this module to store the submissions in your database and see those submissions here.', 'hustle' ),
		esc_html( $module_name ),
		'<a href="' . esc_url( $integrations_url ) . '" target="_blank">',
		'</a>',
		Opt_In_Utils::get_plugin_name()
	);

	// If there are entries, show them. Show a placeholder message otherwise.
	if ( ! empty( $entries ) || $is_filtered ) :

		// List Emails.
		$this->render(
			'admin/email-lists/emails-list',
			array(
				'module'         => $module,
				'no_local_list'  => $no_local_list,
				'add_local_list' => $add_local_list,
				'wizard_page'    => add_query_arg(
					array(
						'page'    => $module->get_wizard_page(),
						'id'      => $module->module_id,
						'section' => 'integrations',
					),
					'admin.php'
				),
				'form_fields'    => $module->get_form_fields(),
			)
		);
		?>

	<?php elseif ( $no_local_list ) : ?>

		<div class="sui-box sui-message">
			<?php $this->render( 'admin/image-markup', $image_attrs ); ?>
			<div class="sui-message-content">

				<h2><?php esc_html_e( 'Local List is Inactive!', 'hustle' ); ?></h2>

				<p><?php echo wp_kses_post( $add_local_list ); ?></p>

			</div>

		</div>

	<?php else : ?>

		<div class="sui-box sui-message">
			<?php $this->render( 'admin/image-markup', $image_attrs ); ?>
			<div class="sui-message-content">

				<h2><?php esc_html_e( 'No Emails Collected!', 'hustle' ); ?></h2>

				<p><?php /* translators: module name */ printf( esc_html__( "Your %s hasn't collected any emails yet. When it starts converting, you'll be able to view the collected emails here.", 'hustle' ), esc_html( $module_name ) ); ?></p>

			</div>

		</div>

	<?php endif; ?>

<?php else : ?>

	<?php if ( 0 === $global_entries ) { ?>

		<div class="sui-box sui-message">
			<?php $this->render( 'admin/image-markup', $image_attrs ); ?>
			<div class="sui-message-content">

				<h2><?php esc_html_e( 'Email Lists', 'hustle' ); ?></h2>

				<p><?php esc_html_e( "You haven't yet collected emails through email opt-ins inside any of your popup, slide-in or embed. When you do, you'll be able to view the email list here.", 'hustle' ); ?></p>

			</div>

		</div>

	<?php } else { ?>

		<div class="sui-box sui-message">
			<?php $this->render( 'admin/image-markup', $image_attrs ); ?>
			<div class="sui-message-content">

				<h2><?php esc_html_e( 'Almost there!', 'hustle' ); ?></h2>

				<p><?php esc_html_e( 'Select the popup, slide-in or embed to view the corresponding email list.', 'hustle' ); ?></p>

			</div>

		</div>

	<?php } ?>

<?php endif; ?>

<?php
// Global Footer.
$this->render( 'admin/global/sui-components/sui-footer' );
?>

<?php
// DIALOG: Dialog Filter for MOBILE.
$this->render(
	'admin/email-lists/dialog-filter',
	array( 'module' => $module )
);
?>

<?php
// DIALOG: Delete Email.
$this->render( 'admin/commons/sui-listing/dialogs/delete-module', array() );

// DIALOG: Dissmiss migrate tracking notice modal confirmation.
if ( Hustle_Notifications::is_show_migrate_tracking_notice() ) {
	$this->render( 'admin/dialogs/migrate-dismiss-confirmation' );
}
?>