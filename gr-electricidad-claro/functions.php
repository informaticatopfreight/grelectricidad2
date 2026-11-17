<?php
/**
 * GR Electricidad — funciones del tema.
 *
 * @package GR_Electricidad
 */

defined( 'ABSPATH' ) || exit;

define( 'GR_VERSION', '1.0.1' );
define( 'GR_DIR', get_template_directory() );
define( 'GR_URI', get_template_directory_uri() );

require GR_DIR . '/inc/datos.php';
require GR_DIR . '/inc/seguridad.php';
require GR_DIR . '/inc/seo.php';
require GR_DIR . '/inc/formulario.php';
require GR_DIR . '/inc/personalizar.php';
require GR_DIR . '/inc/importador.php';
require GR_DIR . '/inc/plantilla.php';

/**
 * Configuración básica del tema.
 */
function gr_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support( 'responsive-embeds' );
	add_post_type_support( 'page', 'excerpt' );

	add_image_size( 'gr-tarjeta', 640, 800, true );
	add_image_size( 'gr-ancha', 1800, 1100, false );

	register_nav_menus(
		array(
			'principal' => 'Menú principal',
			'legal'     => 'Menú legal (pie de página)',
		)
	);
}
add_action( 'after_setup_theme', 'gr_setup' );

/**
 * Estilos y scripts. Todo se sirve desde el propio servidor.
 */
function gr_assets() {
	wp_enqueue_style( 'gr-estilo', get_stylesheet_uri(), array(), GR_VERSION );

	wp_enqueue_script( 'gsap', GR_URI . '/assets/js/vendor/gsap.min.js', array(), '3.13.0', array( 'strategy' => 'defer', 'in_footer' => true ) );
	wp_enqueue_script( 'gsap-scrolltrigger', GR_URI . '/assets/js/vendor/ScrollTrigger.min.js', array( 'gsap' ), '3.13.0', array( 'strategy' => 'defer', 'in_footer' => true ) );
	wp_enqueue_script( 'gr-main', GR_URI . '/assets/js/main.js', array( 'gsap', 'gsap-scrolltrigger' ), GR_VERSION, array( 'strategy' => 'defer', 'in_footer' => true ) );

	wp_localize_script(
		'gr-main',
		'GR',
		array(
			'gaId' => gr_dato( 'ga_id' ),
		)
	);

	// El tema no usa bloques en la portada; dejamos el CSS de bloques solo donde hay contenido.
	if ( is_front_page() ) {
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'global-styles' );
		wp_dequeue_style( 'classic-theme-styles' );
	}
}
add_action( 'wp_enqueue_scripts', 'gr_assets' );

/**
 * Precarga de la fuente principal e iconos.
 */
function gr_head_extra() {
	printf( '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n", esc_url( GR_URI . '/assets/fonts/archivo-latin-wdth-normal.woff2' ) );
	if ( ! has_site_icon() ) {
		printf( '<link rel="icon" href="%s" sizes="512x512">' . "\n", esc_url( GR_URI . '/assets/img/icono.png' ) );
		printf( '<link rel="apple-touch-icon" href="%s">' . "\n", esc_url( GR_URI . '/assets/img/apple-touch-icon.png' ) );
	}
	echo '<meta name="theme-color" content="#FAF8F5">' . "\n";
}
add_action( 'wp_head', 'gr_head_extra', 2 );

/**
 * Quita los emojis de WordPress (scripts innecesarios).
 */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

/**
 * Las tablas del contenido se pueden desplazar en el móvil.
 */
function gr_envolver_tablas( $content ) {
	if ( false === strpos( $content, '<table' ) || false !== strpos( $content, 'wp-block-table' ) ) {
		return $content;
	}
	return preg_replace( '#<table(.*?)</table>#s', '<div class="table-wrap"><table$1</table></div>', $content );
}
add_filter( 'the_content', 'gr_envolver_tablas', 20 );
