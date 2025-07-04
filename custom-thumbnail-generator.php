<?php
/**
 * Plugin Name: Custom Thumbnail Generator
 * Description: Generate theme-independent custom thumbnail sizes with Regenerate Thumbnail feature
 * Version: 1.0.0
 * Author: Subrata Sarkar
 * Author URI: https://github.com/emfluenceindia
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: custom-thumbnail-generator
 * Method Prefix: ctg_
 * 
 * TEST URL: https://techblog.lndo.site/wp-admin/admin-ajax.php?action=ctg_add_custom_size&ctg-width=350&ctg-height=50&ctg-crop=1&ctg-nonce=ljddgf45
 */

 if( ! defined( 'ABSPATH' ) ) exit;

 require_once plugin_dir_path( __FILE__ ) . 'includes/init.php';