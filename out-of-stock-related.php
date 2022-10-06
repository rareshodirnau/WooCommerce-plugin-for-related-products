<?php
/*
Plugin Name:  WooCommerce plugin for related products
Description:  Related products for woocommerce arrange by parent and subcategories only with stock
Version:      1.0
Author:       Rares Hodirnau
Author URI:   https://github.com/rareshodirnau/Hide-out-of-stock-related-products-in-WooCommerce.git
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Plugin URL.
define( 'OUT_OF_STOCK_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
// Plugin path.
define( 'OUT_OF_STOCK', untrailingslashit( plugin_dir_path( __FILE__ ) ) );

class OutofStock{
    
    public function __construct(){
        add_filter( 'woocommerce_related_products', array($this, 'wcd_related_products'), 10, 3 );
    }
    
    public function wcd_related_products( $related_posts, $product_id, $args ){
        $terms = wp_get_object_terms($product_id, 'product_cat', array(
            'orderby' => 'parent',
        ));
        for ($i=count($terms); $i>0; $i--){
            $in_stock_product_ids = (array) wc_get_products( array(
                'status'       => 'publish',
                'stock_status' => 'instock',
                'category'     => array(get_term($terms[$i-1]->term_id, 'product_cat')->name), 
                'exclude'      => array($product_id),
                'return'       => 'ids',
            ));
            if (!empty($in_stock_product_ids)){
                break;
            }
            
        }
        return $in_stock_product_ids;
    }
}
new OutofStock;
