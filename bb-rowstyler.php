<?php
/**
 * bb-rowstyler
 *
 * @package     RowStyler
 * @author      Badabingbreda
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: BeaverBuilder RowStyler
 * Plugin URI:  https://www.badabing.nl
 * Description: Template for adding Rows-styles to the Beaver Builder
 * Version:     1.0
 * Author:      Badabingbreda
 * Author URI:  https://www.badabing.nl
 * Text Domain: bb-rowstyler
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

define( 'BBROWSTYLER_VERSION' , '1.0' );
define( 'BBROWSTYLER_DIR', plugin_dir_path( __FILE__ ) );
define( 'BBROWSTYLER_URL', plugins_url( '/', __FILE__ ) );

//textdomain
load_plugin_textdomain( 'bb-rowstyler', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );

add_action( 'init', 'BBROWSTYLER_plugin_start' );

function BBROWSTYLER_plugin_start() {

  if ( class_exists( 'FLBuilder' ) ) {

       require_once ( 'includes/rowstyler.php' );

  }

}
