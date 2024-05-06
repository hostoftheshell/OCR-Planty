<?php
/**
 * Theme updater
 *
 * @package Spectra One
 * @author Brainstorm Force
 * @since 1.0.5
 */

declare(strict_types=1);

namespace Swt;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


add_action( 'after_setup_theme', SWT_NS . 'run_function_after_theme_update' );

/**
 * Regenerate spectra one.
 *
 * @return void
 * @since 1.0.5
 */
function run_function_after_theme_update():void {
	$version = wp_get_theme()->get( 'Version' );

	if ( $version ) {
		/** @psalm-suppress PossiblyInvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort -- $version comes from wordpress function itself.
		list( $current_version ) = explode( '-', $version );
		$old_version             = get_option( 'swt_theme_version' );

		if ( $old_version !== $current_version && $old_version < $current_version ) {
			// Run your function here.

			if ( is_spectra_plugin() ) {
				/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort -- Needed to Regenerate fonts.
				$spectra_global_fse_fonts = \UAGB_Admin_Helper::get_admin_settings_option( 'spectra_global_fse_fonts', array() );

				if ( empty( $spectra_global_fse_fonts ) || ! is_array( $spectra_global_fse_fonts ) ) {
					return;
				}
				/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort -- Needed to Regenerate fonts.
				$uagb_fonts = new \UAGB_FSE_Fonts_Compatibility();

				/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort -- Needed to Regenerate fonts.
				$uagb_fonts->save_google_fonts_to_theme();
			}
			/** @psalm-suppress UndefinedFunction */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort -- Needed to Regenerate fonts.
			$is_custom_font_plugin = is_custom_fonts_plugin();
			if ( $is_custom_font_plugin ) {
				/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort -- Needed to Regenerate fonts.
				$all_fonts = \Bsf_Custom_Fonts_Render::get_instance()->get_existing_font_posts();

				if ( empty( $all_fonts ) || ! is_array( $all_fonts ) ) {
					return;
				}
				// @codingStandardsIgnoreStart
				/**
				 * @psalm-suppress UndefinedClass
				 * @psalm-suppress UndefinedFunction
				 */
				bcf_google_fonts_compatibility()->update_fse_theme_json();
				// @codingStandardsIgnoreEnd
			}

			// Update not to run twice.
			update_option( 'swt_theme_version', $current_version );
		}
	}
}

add_action( 'switch_theme', SWT_NS . 'remove_option_after_theme_update' );

/**
 * Implement theme update logic.
 *
 * @return void
 * @since 1.0.5
 */
function remove_option_after_theme_update() {
	delete_option( 'swt_theme_version' );
}
