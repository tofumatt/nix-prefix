<?php
/**
 * The code that controls our custom settings page for the plugin.
 *
 * We have two options, both quite simple. One that adds a span rather than
 * removing the prefix, and another that allows the user to choose which
 * archive pages are affected.
 *
 * @package Nix Prefix
 */

/*
 * Add a menu item for our settings page under the main settings tab.
 */
function nix_prefix_add_menu_item() {
	add_options_page(
		esc_attr__( 'Nix Prefix Settings', 'nix_prefix' ),
		esc_html__( 'Nix Prefix', 'nix_prefix' ),
		'manage_options',
		'nix_prefix',
		'nix_prefix_options_page'
	);
}
add_action( 'admin_menu', 'nix_prefix_add_menu_item' );

/*
 * Set up the actual settings page itself.
 */
function nix_prefix_options_page() {
	?>
	<div class="wrap">
		<?php if ( isset( $_REQUEST['settings-updated'] ) ) : ?>
			<div class="updated fade"><p><strong><?php esc_html_e( 'Your settings have been saved!', 'nix_prefix' ); ?></strong></p></div>
		<?php endif; ?>

		<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

		<form action='options.php' method='post'>
			<?php
			settings_fields( 'nix_prefix_settings' );
			do_settings_sections( 'nix_prefix_settings' );
			submit_button();
			?>
	</div>
	<?php
}

/*
 * Register our settings sections and fields.
 */
function nix_prefix_settings_init() {

	register_setting( 'nix_prefix_settings', 'nix_prefix_settings' );

		// Register a section, mostly because it doesn't seem possible not to
		add_settings_section(
		'nix_prefix_general_settings_section',
		esc_html__( 'General Settings', 'nix_prefix' ),
		'',
		'nix_prefix_settings'
	);

		// Register various options
	add_settings_field(
		'nix_prefix_wrap_in_span',
		esc_html__( 'Prefix behaviour', 'nix_prefix' ),
		'nix_prefix_wrap_in_span',
		'nix_prefix_settings',
		'nix_prefix_general_settings_section'
	);

	add_settings_field(
		'nix_prefix_archive_category',
		esc_html__( 'Category archives', 'nix_prefix' ),
		'nix_prefix_archive_pages_render',
		'nix_prefix_settings',
		'nix_prefix_general_settings_section'
	);

	add_settings_field(
		'nix_prefix_archive_tag',
		esc_html__( 'Tag archives', 'nix_prefix' ),
		'nix_prefix_archive_pages_render',
		'nix_prefix_settings',
		'nix_prefix_general_settings_section'
	);
}
add_action( 'admin_init', 'nix_prefix_settings_init' );

/*
 * These functions are used to render each particular option's input field.
 */
function nix_prefix_wrap_in_span() {
	$options = get_option( 'nix_prefix_settings' );
		if ( ! isset( $options['nix_prefix_wrap_in_span'] ) ) :
			$options['nix_prefix_wrap_in_span'] = 0;
		endif;
	?>
	<input type='checkbox' name='nix_prefix_settings[nix_prefix_wrap_in_span]' <?php checked( $options['nix_prefix_wrap_in_span'], 1 ); ?> value='1'>
	<label for="nix_prefix_settings[nix_prefix_wrap_in_span]">Wrap prefix in a span tag instead of hiding it</label>
<?php
}

function nix_prefix_archive_pages_render() {
	$options = get_option( 'nix_prefix_settings' );
	$archive_page_types = array( 'category', 'tag', 'author', 'date', 'CPT', 'taxonomy' );
	?>
	<?php foreach ( $archive_page_types as $type ) : ?>
		<input type='checkbox' name='nix_prefix_settings[nix_prefix_archive_<?php echo $type; ?>]' <?php checked( $options['nix_prefix_archive_' .  $type ], 1 ); ?> value='1'>
	<?php endforeach; ?>
<?php
}
