<?php
/*
  * Plugin Name: Price per unit for wc product
  * Plugin URI: 
  * Description: Price as per unit plugin takes the price of the product and divides this price by its weight, which help to sell products to the customer to know how much a weight unit costs.
  * Author: Sunarc
  * Author URI: https://www.suncartstore.com/
  * Version: 1.0
 */

if (!defined("ABSPATH"))
      exit;

 if (!defined("PPUSUNARC_PLUGIN_DIR_PATH"))
  define("PPUSUNARC_PLUGIN_DIR_PATH", plugin_dir_path(__FILE__));

 if (!defined("PPUSUNARC_PLUGIN_URL"))
  define("PPUSUNARC_PLUGIN_URL", plugins_url('price-per-unit-for-wc-product'));  


/*
* Check woocommerce activation
*/
function ppusunarc_activation_hook() {
	if(!class_exists( 'WooCommerce' )){
	    deactivate_plugins(basename(__FILE__));
	    wp_die(__("WooCommerce is not installed/actived. it is required for this plugin to work properly. Please activate WooCommerce.", "price-per-unit-for-wc-product"), "", array('back_link' => 1));
	}
}
register_activation_hook(__FILE__, "ppusunarc_activation_hook");


require_once PPUSUNARC_PLUGIN_DIR_PATH.'/INC/admin-settings.php';
require_once PPUSUNARC_PLUGIN_DIR_PATH.'/INC/front.php';