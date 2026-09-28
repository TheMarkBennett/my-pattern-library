<?php
declare( strict_types = 1 );

namespace MyPatternLibrary;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', static function (): void {
	register_block_pattern_category(
		'my-pattern-library',
		[ 'label' => __( 'My Library', 'my-pattern-library' ) ]
	);

	register_block_pattern_category(
		'hero',
		[ 'label' => __( 'Hero', 'my-pattern-library' ) ]
	);
	register_block_pattern_category(
		'cta',
		[ 'label' => __( 'Cta', 'my-pattern-library' ) ]
	);
	register_block_pattern_category(
		'faq',
		[ 'label' => __( 'FAQ', 'my-pattern-library' ) ]
	);
	register_block_pattern_category(
		'list',
		[ 'label' => __( 'list', 'my-pattern-library' ) ]
	);
	register_block_pattern_category(
		'news',
		[ 'label' => __( 'News', 'my-pattern-library' ) ]
	);
	register_block_pattern_category(
		'service',
		[ 'label' => __( 'service', 'my-pattern-library' ) ]
	);
	register_block_pattern_category(
		'team',
		[ 'label' => __( 'team', 'my-pattern-library' ) ]
	);
	register_block_pattern_category(
		'stats',
		[ 'label' => __( 'Stats', 'my-pattern-library' ) ]
	);
} );

add_action( 'init', static function (): void {
	load_patterns_manually( __DIR__ . '/../patterns' );
} );

add_filter( 'block_editor_settings_all', static function ( array $settings ): array {
	$settings['canLockBlocks'] = false;

	return $settings;
} );

function load_patterns_manually( string $dir ): void {
	foreach ( glob( $dir . '/*.php' ) ?: [] as $file ) {
		$metadata = get_file_data(
			$file,
			[
				'title'       => 'Title',
				'slug'        => 'Slug',
				'categories'  => 'Categories',
				'description' => 'Description',
				'keywords'    => 'Keywords',
			]
		);

		if ( empty( $metadata['title'] ) ) {
			continue;
		}

		$slug = $metadata['slug'] ?: 'my-pattern-library/' . basename( $file, '.php' );

		ob_start();
		include $file;
		$content = ob_get_clean();

		register_block_pattern(
			$slug,
			[
				'title'       => $metadata['title'],
				'description' => $metadata['description'],
				'categories'  => $metadata['categories'] ? array_map( 'trim', explode( ',', $metadata['categories'] ) ) : [ 'my-pattern-library' ],
				'keywords'    => $metadata['keywords'] ? array_map( 'trim', explode( ',', $metadata['keywords'] ) ) : [],
				'content'     => $content,
			]
		);
	}
}