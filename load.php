<?php
/**
 * Main loader file for the Advanced Media Filters enhancement.
 *
 * This file defines constants, includes the main class,
 * and initializes the filtering functionality.
 *
 * @package    HussainasMediaFilters
 * @version     1.0.0
 * @author      Hussain Ahmed Shrabon
 * @license     GPL-2.0-or-later
 * @link        https://github.com/iamhussaina
 * @textdomain  hussainas
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Ensure the class is not already defined.
if ( ! class_exists( 'Hussainas_Media_Filters' ) ) {
	/**
	 * Define the path constant for inclusion.
	 *
	 * Using `__DIR__` ensures the path is always correct,
	 * regardless of how the file is included.
	 */
	define( 'HUSSAINAS_MF_PATH', __DIR__ . '/' );

	// Include the main class file.
	require_once HUSSAINAS_MF_PATH . 'includes/class-hussainas-media-filters.php';

	/**
	 * Initializes the main class using a static getter.
	 *
	 * This approach ensures that the class is instantiated only once
	 * (Singleton pattern) and registers all necessary WordPress hooks.
	 *
	 * @return void
	 */
	function hussainas_mf_load_enhancement() {
		Hussainas_Media_Filters::get_instance();
	}

	// Hook into WordPress's 'after_setup_theme' to ensure all functions are available.
	add_action( 'after_setup_theme', 'hussainas_mf_load_enhancement' );
}
