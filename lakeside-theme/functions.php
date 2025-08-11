<?php
/**
 * lakeside-wp2 functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package lakeside-wp2
 */

if ( ! function_exists( 'lakeside_wp2_setup' ) ) :
  	/**
      	 * Sets up theme defaults and registers support for various WordPress features.
      	 *
      	 * Note that this function is hooked into the after_setup_theme hook, which
      	 * runs before the init hook. The init hook is too late for some features, such
      	 * as indicating support for post thumbnails.
      	 */
  	function lakeside_wp2_setup() {
      		/*
            		 * Make theme available for translation.
            		 * Translations can be filed in the /languages/ directory.
            		 * If you're building a theme based on lakeside-wp2, use a find and replace
            		 * to change 'lakeside-wp2' to the name of your theme in all the template files.
            		 */
  		load_theme_textdomain( 'lakeside-wp2', get_template_directory() . '/languages' );

  		// Add default posts and comments RSS feed links to head.
  		add_theme_support( 'automatic-feed-links' );

  		/*
        		 * Let WordPress manage the document title.
        		 * By adding theme support, we declare that this theme does not use a
        		 * hard-coded <title> tag in the document head, and expect WordPress to
        		 * provide it for us.
        		 */
  		add_theme_support( 'title-tag' );

  		/*
        		 * Enable support for Post Thumbnails on posts and pages.
        		 *
        		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
        		 */
  		add_theme_support( 'post-thumbnails' );

  		// This theme uses wp_nav_menu() in one location.
  		register_nav_menus( array(
        			'menu-1' => esc_html__( 'Primary', 'lakeside-wp2' ),
        		) );

  		/*
        		 * Switch default core markup for search form, comment form, and comments
               		 * to output valid HTML5.
               		 */
  		add_theme_support( 'html5', array(
        			'search-form',
        			'comment-form',
        			'comment-list',
        			'gallery',
        			'caption',
        		) );

  		// Set up the WordPress core custom background feature.
  		add_theme_support( 'custom-background', apply_filters( 'lakeside_wp2_custom_background_args', array(
        			'default-color' => 'ffffff',
        			'default-image' => '',
        		) ) );

  		// Add theme support for selective refresh for widgets.
  		add_theme_support( 'customize-selective-refresh-widgets' );

  		/**
        		 * Add support for core custom logo.
        		 *
        		 * @link https://codex.wordpress.org/Theme_Logo
        		 */
  		add_theme_support( 'custom-logo', array(
        			'height'      => 250,
        			'width'       => 250,
        			'flex-width'  => true,
        			'flex-height' => true,
        		) );
    }
endif;
add_action( 'after_setup_theme', 'lakeside_wp2_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
   *
   * Priority 0 to make it available to lower priority callbacks.
   *
   * @global int $content_width
   */
function lakeside_wp2_content_width() {
  	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'lakeside_wp2_content_width', 640 );
}
add_action( 'after_setup_theme', 'lakeside_wp2_content_width', 0 );

/**
 * Register widget area.
   *
   * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
   */
function lakeside_wp2_widgets_init() {
  	register_sidebar( array(
      		'name'          => esc_html__( 'Sidebar', 'lakeside-wp2' ),
      		'id'            => 'sidebar-1',
      		'description'   => esc_html__( 'Add widgets here.', 'lakeside-wp2' ),
      		'before_widget' => '<section id="%1$s" class="widget %2$s">',
      		'after_widget'  => '</section>',
      		'before_title'  => '<h2 class="widget-title">',
      		'after_title'   => '</h2>',
      	) );
}
add_action( 'widgets_init', 'lakeside_wp2_widgets_init' );

/**
 * Enqueue scripts and styles.
   */
function lakeside_wp2_scripts() {
  	wp_enqueue_style( 'lakeside-wp2-style', get_stylesheet_uri() );

	wp_enqueue_script( 'lakeside-wp2-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'lakeside-wp2-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
    		wp_enqueue_script( 'comment-reply' );
  }
}
add_action( 'wp_enqueue_scripts', 'lakeside_wp2_scripts' );

/**
 * Implement the Custom Header feature.
   */
  require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
   */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
   */
  require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
   */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
   */
if ( defined( 'JETPACK__VERSION' ) ) {
  	require get_template_directory() . '/inc/jetpack.php';
}

// Remove jQuery Migrate for performance optimization
// Added by Yeasin - Date: 2025-08-07
function remove_jquery_migrate() {
      if (!is_admin()) {
                // Deregister jQuery Migrate and re-register jQuery only
          wp_deregister_script('jquery');
                wp_register_script('jquery', includes_url('/js/jquery/jquery.min.js'), false, null, true);
                wp_enqueue_script('jquery');
      }
}
add_action('wp_enqueue_scripts', 'remove_jquery_migrate');

// Enqueue JS
add_action('wp_enqueue_scripts', function () {
      if (is_shop() || is_product_category() || is_product_tag()) {
                wp_enqueue_script('ajax-load-products', get_template_directory_uri() . '/js/ajax-load-products.js', ['jquery'], null, true);

          global $wp_query;
                wp_localize_script('ajax-load-products', 'ajax_params', [
                                               'ajaxurl'        => admin_url('admin-ajax.php'),
                                               'query_vars'     => json_encode($wp_query->query),
                                               'current_page'   => max(1, get_query_var('paged')),
                                               'max_page'       => $wp_query->max_num_pages
                                           ]);
      }
}, 20);

// Remove default pagination
remove_action('woocommerce_after_shop_loop', 'woocommerce_pagination', 10);

// Ajax handler
add_action('wp_ajax_load_more_products', 'load_more_products_ajax_handler');
add_action('wp_ajax_nopriv_load_more_products', 'load_more_products_ajax_handler');

function load_more_products_ajax_handler() {
      $paged      = isset($_POST['page']) ? intval($_POST['page']) : 1;
      $query_vars = isset($_POST['query']) ? json_decode(stripslashes($_POST['query']), true) : [];

    $query_vars['paged']     = $paged;
      $query_vars['post_type'] = 'product';

    $loop = new WP_Query($query_vars);

    if ($loop->
