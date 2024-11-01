<?php 
/* 

* Plugin Name: SpecialCart
* Description: SpecialCart
*Version: 1.0.0
*Author: Pixliy
*Author URI: https://www.pixliy.com
*Plugin URI:/specialcart
*Text Domain: SpecialCart
* License: GPLv2 or later
* License URI: https://www.gnu.org/licenses/gpl-2.0.html

**/


if( !defined('ABSPATH')) exit;


class SpecialCart{
    function __construct()
    {

   
        // Actions
        add_action('admin_init',[$this,'adminSettings']);
        add_action('admin_menu',[$this,'adminMenu']);
        add_action('init',[$this,'cartScripts']);
        // add_action('admin_init',[$this,'adminAssets']);

        // add cart icon
        add_filter( 'wp_nav_menu_items', 'add_cart_icon', 10, 2 );
        function add_cart_icon( $items, $args ) {
                $items .= '<li id="cartB" style="cursor:pointer;"><a>Cart(';
                $items .=WC()->cart->get_cart_contents_count();
                $items .= ')</a></li>';
        
            return $items;
        }
        // add cart sidebar
        add_action( 'wp_body_open', [$this,'inserCartBody'] );

     




  

         

    }
    function insertCartBody(){


           
        $wow = '<div id="cartsidebardiv">';
        $wow .= '<div class="cart-sidebar" id="cartsidebar">';
        $wow .= '<div class="cart-sidebar-header"><div class="cart-sidebar-close">×</div><p>Cart</p></div>';
        // $wow .= sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count);
        $wow .= '<div class="cart-sidebar-products" id="specialcart-sidebar-products-container">';
        foreach(WC()->cart->get_cart() as $values ) { 

        
            $_product =  wc_get_product( $values['data']->get_id()); 
            $product_cart_id = WC()->cart->generate_cart_id( $values['data']->id);
            $cart_item_key = $values['key'];
            $price = get_post_meta($values['product_id'] , '_price', true);

            // $attributes = $_product->get_attributes();
            // foreach ( $attributes as $attribute ) {
            //    $wow .= $attribute['name'] . ': ' . $attribute['value'];
            // }

        
            $wow .= '<div class="single-product-box" id="bbox">';
          
            $wow .= '<div class="single-product-half1">';
            $wow .= $_product->get_image(); 

            $wow .= '</div>';

            $wow .= '<div class="single-product-half2">';
            // $wow .= "<a href='". wc_get_cart_remove_url( $cart_item_key )."'>Remove</a>";
            $wow .= '<b>'.esc_attr($_product->get_title()).'</b><br>';
            // $wow .= "<b> Size - " . $values['pro_size'] .'</b><br>';

            $wow .=  '<p class="single-p-price">'.esc_attr($price).' ₪</p>';
            $wow .='<div class="quantity-sp-o">';
            $wow .= '<p class="quy-stock"></p>';
            $wow .= '<div class="minus buttonquy">-</div>';
            $wow.='<input type="text" step="1" min="0"
            name="cart[1e334311e1ef4cf849abff19e4237358][qty]"
            value="'.esc_attr($values['quantity']).'" title="Qty" class="input-cart-quy qty text" data-value="'.esc_attr($cart_item_key) .'" size="4">';
            $wow .= '<div class="plus buttonquy">+</div>';
            $wow .= '</div>';
        
            $wow .= '</div>';

            $wow .= '</div>';
        } 

        $wow .= '</div>';
        $wow .= '<div class="cart-sidebar-footer">';
        $wow .= '<div class="subtotal-cart"> Subtotal ';
        $wow .= '<span id="subtotal-special-cart">';
        $wow .= WC()->cart->get_cart_subtotal();
        $wow .= '</span>';
        $wow .= '</div>';
        $wow .='<div class="checkout-btc"><a href="/checkout">Checkout</a></div>';
        $wow .='</div>';
        $wow .= '</div>';
        $wow .= '</div>';

        $wowarr = array(
            'a' => array(
                'href' => array(),
                'title' => array(),
                'class' => array(),
                'id' => array(),
            ),
            'br' => array(),
            'em' => array(),
            'input' => array( 
            'class' => array(),
            'id' => array(),
            'type' => array(),
            'value' => array(),
            'data-value' => array(),
            'title' => array(),

           ),
            'strong' => array(),
            'div' => array(
                'class' => array(),
                'id' => array(),
            ),
            'p' => array(),
            'span' => array(
                'class' => array(),
                'id' => array(),
            ),
            'img' => array(
                'title' => array(),
                'src'	=> array(),
                'alt'	=> array(),
                'class' => array(),
                'id' => array(),
            ),

        );
        echo wp_kses( $wow,$wowarr);
        
      
    }
    function specialCartDashboard()
    {
        ?>
                
                

               <form action="options.php" method="post">
                <?php
                  settings_fields('specialcartpluginsettings');
                  do_settings_sections('specialcart');
                  submit_button('save')
                ?>
               </form>
          
        <?php
    }

    function staticData()
    {
        ?>
           <input type="color" id="sc_themecolor" name="sc_cart_theme" value="<?php echo esc_attr(get_option( 'sc_cart_theme')); ?>">
           <input type="color" id="sc_primarycolor" name="sc_cart_primary" value="<?php echo esc_attr(get_option( 'sc_cart_primary')); ?>">

        <?php
    }
    function adminSettings()
    {

        add_settings_section( 'specialcart_section',null,null,'specialcart' );

        add_settings_field('sc_cart_theme',null,[$this,'staticData'],'specialcart','specialcart_section');

        register_setting('specialcartpluginsettings','sc_cart_theme',array('sanitize_callback'=> 'sanitize_hex_color','default'=>'#ff0000'));
        register_setting('specialcartpluginsettings','sc_cart_primary',array('sanitize_callback'=> 'sanitize_hex_color','default'=>'#fff000'));
        register_setting('specialcartpluginsettings','sc_cart_icon',array('sanitize_callback'=> 'sanitize_text_field','default'=>''));

    }

    function adminMenu()
    {

       add_menu_page('SpecialCart','SpecialCart','manage_options','specialcart',[$this,'specialCartDashboard'],'dashicons-store',51);
        
    }
    function cartScripts()
    {
        wp_enqueue_script('cartScript',plugin_dir_url(__FILE__). 'js/cart.js',['jquery']);

        wp_enqueue_style('cartStyles',plugin_dir_url(__FILE__). 'css/cart.css', array(), '1.0.0', 'all' );


        // cart items
        add_action( 'wp_ajax_get_cart_items', 'wp_ajax_get_cart_items_action' );
        add_action( 'wp_ajax_nopriv_get_cart_items', 'wp_ajax_get_cart_items_action' );
        function wp_ajax_get_cart_items_action() {
            $cart = WC()->cart;
            if ( $cart ) {
                $arr = array();

                foreach(WC()->cart->get_cart() as $values ) { 
                    $ar = array();
            
                    $_product =  wc_get_product( $values['data']->get_id()); 
                    $product_cart_id = WC()->cart->generate_cart_id( $values['data']->id);
                    $cart_item_key = $values['key'];
                    $price = get_post_meta($values['product_id'] , '_price', true);
                    $product_title = $_product->get_title();
                    $product_img = $_product->get_image();

                    array_push($ar,$cart_item_key);
                    array_push($ar,$product_title);
                    array_push($ar,$product_img);
                    array_push($ar,$price);
                    array_push($ar,$values['quantity']);

                    array_push($arr,$ar);

                }
         
                wp_send_json_success( $arr );
                wp_die();
            }
        }
        // cart total
        add_action( 'wp_ajax_get_cart_total', 'wp_ajax_get_cart_total_action' );
        add_action( 'wp_ajax_nopriv_get_cart_total', 'wp_ajax_get_cart_total_action' );
        function wp_ajax_get_cart_total_action() {
            $cart = WC()->cart;
            if ( $cart ) {
                wp_send_json_success( $cart->get_cart_subtotal() );
                wp_die();
            }
        }

        // cart quantity update
        add_action('wp_ajax_update_item_from_cart', 'update_item_from_cart');
        add_action('wp_ajax_nopriv_update_item_from_cart', 'update_item_from_cart');
        function update_item_from_cart() {
            $cart_item_key = sanitize_text_field( $_POST['cart_item_key'] );   
            $quantity = sanitize_text_field( $_POST['qty'] );     

            // Get mini cart
            ob_start();

            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item)
            {
                if( $cart_item_key == sanitize_text_field( $_POST['cart_item_key'] ) )
                {
                    WC()->cart->set_quantity( $cart_item_key, $quantity, $refresh_totals = true );
                }
            }
            WC()->cart->calculate_totals();
            WC()->cart->maybe_set_cart_cookies();
            return true;
        }


        wp_localize_script( 'cartScript', 'cartScript', [
            'ajaxurl' => admin_url( 'admin-ajax.php' )
        ]);


    }
    function adminAssets()
    {
        // JS
        // wp_enqueue_script('adminDashboardScript',plugin_dir_url(__FILE__). 'js/dashboard.js',array());
        // wp_enqueue_script('adminQuestionsScript',plugin_dir_url(__FILE__). 'js/questions.js',array());
        // wp_enqueue_script('adminQuizEditorScript',plugin_dir_url(__FILE__). 'js/quiz/quizEditor.js',array());

        // wp_localize_script( 'adminQuestionsScript', 'pluginuri',plugin_dir_url(__FILE__));


        // // Css
        // wp_enqueue_style('dashboardStyles',plugin_dir_url(__FILE__). 'css/dashboard.css', array(), '1.0.0', 'all' );
        // wp_enqueue_style('editorStyles',plugin_dir_url(__FILE__). 'css/editor.css', array(), '1.0.0', 'all' );

    }
    

}


if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

    // check if woocommerce activated and init product viewer class
    $specialcart = new SpecialCart();


 }
 else {
    // display error message

        function general_admin_notice(){
            global $pagenow;
            if ( $pagenow == 'plugins.php' ) {
                echo '<div class="notice notice-error is-dismissible">
                    <p style="font-size:1.2em;">Install <a target="_blank" href="https://wordpress.org/plugins/woocommerce/">Woocommerce</a> before using AllCommerce</p>
                </div>';
            }
        }
        add_action('admin_notices', 'general_admin_notice');
 }

