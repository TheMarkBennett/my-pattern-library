<?php
declare( strict_types = 1 );

namespace MyPatternLibrary;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const DISABLE_CORE_PATTERNS_OPTION = 'my_pattern_library_disable_core_patterns';

add_action( 'admin_menu', __NAMESPACE__ . '\\add_settings_page' );
add_action( 'admin_init', __NAMESPACE__ . '\\register_settings' );
add_action( 'after_setup_theme', __NAMESPACE__ . '\\disable_core_patterns', 11 );
add_action( 'init', __NAMESPACE__ . '\\unregister_core_patterns', 100 );
add_filter( 'should_load_remote_block_patterns', __NAMESPACE__ . '\\filter_remote_patterns' );

function add_settings_page(): void {
	add_options_page(
		__( 'My Pattern Library', 'my-pattern-library' ),
		__( 'My Pattern Library', 'my-pattern-library' ),
		'manage_options',
		'my-pattern-library',
		__NAMESPACE__ . '\\render_settings_page'
	);
}

function register_settings(): void {
	register_setting(
		'my_pattern_library_settings',
		DISABLE_CORE_PATTERNS_OPTION,
		[
			'type'              => 'boolean',
			'sanitize_callback' => __NAMESPACE__ . '\\sanitize_boolean',
			'default'           => false,
		]
	);

	add_settings_section(
		'my_pattern_library_patterns_section',
		__( 'Pattern Library', 'my-pattern-library' ),
		'__return_false',
		'my-pattern-library'
	);

	add_settings_field(
		DISABLE_CORE_PATTERNS_OPTION,
		__( 'Disable WordPress and theme patterns', 'my-pattern-library' ),
		__NAMESPACE__ . '\\render_disable_core_patterns_field',
		'my-pattern-library',
		'my_pattern_library_patterns_section'
	);
}

function sanitize_boolean( mixed $value ): bool {
	return true === $value || in_array( $value, [ 1, '1', 'true', 'on' ], true );
}

function disable_core_patterns(): void {
	if ( get_option( DISABLE_CORE_PATTERNS_OPTION, false ) ) {
		remove_theme_support( 'core-block-patterns' );
	}
}

function filter_remote_patterns( bool $load ): bool {
	return get_option( DISABLE_CORE_PATTERNS_OPTION, false ) ? false : $load;
}

function unregister_core_patterns(): void {
	if ( ! get_option( DISABLE_CORE_PATTERNS_OPTION, false ) ) {
		return;
	}

	$registry = \WP_Block_Patterns_Registry::get_instance();
	$theme_namespaces = [ get_template(), get_stylesheet() ];

	foreach ( $registry->get_all_registered() as $pattern ) {
		$name = $pattern['name'] ?? '';

		$is_core_pattern = str_starts_with( $name, 'core/' );
		$is_theme_pattern = false;

		foreach ( $theme_namespaces as $namespace ) {
			if ( str_starts_with( $name, $namespace . '/' ) ) {
				$is_theme_pattern = true;
				break;
			}
		}

		if ( $is_core_pattern || $is_theme_pattern ) {
			unregister_block_pattern( $name );
		}
	}
}

function render_settings_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?php echo esc_html__( 'My Pattern Library', 'my-pattern-library' ); ?></h1>
		<p><?php echo esc_html__( 'These settings affect WordPress-provided patterns. Patterns from this plugin remain available.', 'my-pattern-library' ); ?></p>
		<?php settings_errors(); ?>
		<form action="options.php" method="post">
			<?php
			settings_fields( 'my_pattern_library_settings' );
			do_settings_sections( 'my-pattern-library' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}

function render_disable_core_patterns_field(): void {
	$enabled = (bool) get_option( DISABLE_CORE_PATTERNS_OPTION, false );
	?>
	<input type="hidden" name="<?php echo esc_attr( DISABLE_CORE_PATTERNS_OPTION ); ?>" value="0">
	<label>
		<input type="checkbox" name="<?php echo esc_attr( DISABLE_CORE_PATTERNS_OPTION ); ?>" value="1" <?php checked( $enabled ); ?>>
		<?php echo esc_html__( 'Remove WordPress core, remote, and active theme patterns from the editor.', 'my-pattern-library' ); ?>
	</label>
	<?php
}
