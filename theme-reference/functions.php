<?php
/**
 * Theme functions and definitions.
 *
 * Sets up the theme and provides some helper functions
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development
 * and http://codex.wordpress.org/Child_Themes), you can override certain
 * functions (those wrapped in a function_exists() call) by defining them first
 * in your child theme's functions.php file. The child theme's functions.php
 * file is included before the parent theme's file, so the child theme
 * functions would be used.
 *
 *
 * For more information on hooks, actions, and filters,
 * see http://codex.wordpress.org/Plugin_API
 *
 * @package     Adrian WordPress theme
 * @subpackage  Templates
 * @author      Adrian Morelos
 * @link        http://adrian-morelos.github.io/
 * @since       1.0.0
 */

define('SEARCH_DIRECTORY_THEME_DIR', dirname(__FILE__));

// Actions
add_action('wp_enqueue_scripts', 'load_scripts');
add_action('wp_ajax_bordoni_query_posts', 'custom_query_search_callback');
add_action('wp_ajax_nopriv_bordoni_query_posts', 'custom_query_search_callback');

/**
 * Load needed js and Css scripts.
 *
 * @since 1.0.0
 *
 */
function load_scripts() {
  // Define js directory
  $js_dir = get_template_directory_uri() . '/js/';

  // Define CSS directory
  $css_dir = get_template_directory_uri() . '/css/';

  // JS
  wp_register_script('jquery-validate', $js_dir . 'jquery.validate.min.js', array('jquery'), '1.0', true);
  wp_register_script('search-directory', $js_dir . 'ringvoz-faq.js', array('jquery', 'jquery-validate'), '1.0', true);

  // CSS
  wp_enqueue_style('search-directory', $css_dir . 'search-directory.css');

}

/**
 * Custom Ajax CallBack.
 *
 * @since 1.0.0
 *
 * @return Json Response
 */
function custom_query_search_callback() {
  $response = array();
  // Never Use $_POST or $_GET variables without proper care Sanatization
  $query = new WP_Query(array(
    'posts_per_page' => absint($_POST['param1']),
    'post_type' => wp_kses($_POST['param2'], array()),
    'OTHER QUERY VAR HERE' => wp_kses($_POST['param3'], array()),
  ));

  // If we don't have posts matching this query return status as false
  if (!$query->have_posts()) {
    $response->status = false;
    // remember to send an information about why it failed, always.
    $response->message = esc_attr__('No posts were found');

  } else {
    $response->status = true;
    // We will return the whole query to allow any customization on the front end
    $response->query = $query;
    $response->mockup = build_html_response($query);
    $response->coordinates_array = build_coordinates_response($query);
  }

  // Never forget to exit or die on the end of a WordPress AJAX action!
  exit(json_encode($response));
}

/**
 * Build html response from the result query.
 *
 * @since 1.0.0
 *
 * @param $query_result WP_Query result to build the html response.
 * @return Html Output
 */
function build_html_response($query_result) {
  $mockup = "";
  // Your code here to build html response..
  return $mockup;
}

/**
 * Build coordinates array response from the result query.
 *
 * @since 1.0.0
 *
 * @param $query_result WP_Query result to build the coordinates array.
 * @return array
 */
function build_coordinates_response($query_result) {
  $coordinates = array();
  // Your code here to build coordinates array response..
  return $coordinates;
}

/*
* Shortcode
*/
function search_directory_shortcode($atts) {

  wp_enqueue_style('search-directory');
  wp_enqueue_script('jquery-validate');
  wp_enqueue_script('search-directory');
  $data = array('custom_query_search_callback' => get_site_url() . '/custom_query_search_callback');
  wp_localize_script('search-directory', 'search_directory', $data);

  $data_needed = array(); // Do Something for get infomation needed for the template.

  // Return output
  include(SEARCH_DIRECTORY_THEME_DIR . '/search-directory.tpl.php');
}

add_shortcode('search_directory', 'search_directory_shortcode');
