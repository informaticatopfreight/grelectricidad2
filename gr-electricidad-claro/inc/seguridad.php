<?php
/**
 * Medidas de seguridad.
 *
 * - Desactiva XML-RPC y los pingbacks.
 * - Oculta la versión de WordPress.
 * - Impide averiguar los nombres de usuario (archivos de autor, ?author=, API REST, mapa del sitio).
 * - Desactiva los comentarios en toda la web.
 * - Añade cabeceras de seguridad.
 * - Mensajes de error de acceso genéricos.
 * - Avisa en el escritorio si existe un usuario llamado «admin» o «admin1».
 *
 * @package GR_Electricidad
 */

defined( 'ABSPATH' ) || exit;

/* ---------- XML-RPC y pingbacks ---------- */
add_filter( 'xmlrpc_enabled', '__return_false' );
add_filter(
	'wp_headers',
	function ( $headers ) {
		unset( $headers['X-Pingback'] );
		return $headers;
	}
);
add_filter(
	'xmlrpc_methods',
	function () {
		return array();
	}
);

/* ---------- Versión de WordPress oculta ---------- */
remove_action( 'wp_head', 'wp_generator' );
add_filter( 'the_generator', '__return_empty_string' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );

/* ---------- Usuarios ocultos ---------- */

// Archivos de autor (/author/...) y ?author=N → 404.
add_action(
	'template_redirect',
	function () {
		if ( is_author() || isset( $_GET['author'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			global $wp_query;
			$wp_query->set_404();
			status_header( 404 );
			nocache_headers();
		}
	},
	1
);

// API REST: la lista de usuarios solo para usuarios conectados que puedan verla.
add_filter(
	'rest_endpoints',
	function ( $endpoints ) {
		if ( current_user_can( 'list_users' ) ) {
			return $endpoints;
		}
		foreach ( array_keys( $endpoints ) as $ruta ) {
			if ( 0 === strpos( $ruta, '/wp/v2/users' ) ) {
				unset( $endpoints[ $ruta ] );
			}
		}
		return $endpoints;
	}
);

// Mapa del sitio sin usuarios.
add_filter(
	'wp_sitemaps_add_provider',
	function ( $provider, $name ) {
		return 'users' === $name ? false : $provider;
	},
	10,
	2
);

// Enlaces de autor fuera del HTML y de los oEmbed.
add_filter( 'author_link', fn() => home_url( '/' ) );
add_filter(
	'oembed_response_data',
	function ( $data ) {
		unset( $data['author_name'], $data['author_url'] );
		return $data;
	}
);

/* ---------- Comentarios desactivados ---------- */
add_filter( 'comments_open', '__return_false', 20 );
add_filter( 'pings_open', '__return_false', 20 );
add_filter( 'comments_array', '__return_empty_array', 10 );
add_action(
	'init',
	function () {
		foreach ( get_post_types() as $tipo ) {
			if ( post_type_supports( $tipo, 'comments' ) ) {
				remove_post_type_support( $tipo, 'comments' );
				remove_post_type_support( $tipo, 'trackbacks' );
			}
		}
	},
	100
);
add_action(
	'admin_menu',
	function () {
		remove_menu_page( 'edit-comments.php' );
		remove_submenu_page( 'options-general.php', 'options-discussion.php' );
	}
);
add_action(
	'admin_bar_menu',
	function ( $bar ) {
		$bar->remove_node( 'comments' );
	},
	100
);
add_filter( 'feed_links_show_comments_feed', '__return_false' );

/* ---------- Cabeceras de seguridad ---------- */
add_action(
	'send_headers',
	function () {
		if ( headers_sent() ) {
			return;
		}
		header( 'X-Content-Type-Options: nosniff' );
		header( 'X-Frame-Options: SAMEORIGIN' );
		header( 'Referrer-Policy: strict-origin-when-cross-origin' );
		header( 'Permissions-Policy: camera=(), microphone=(), geolocation=(), interest-cohort=()' );
		if ( is_ssl() ) {
			header( 'Strict-Transport-Security: max-age=31536000' );
		}
	}
);

/* ---------- Errores de acceso genéricos ---------- */
add_filter(
	'login_errors',
	function () {
		return 'Usuario o contraseña incorrectos.';
	}
);

/* ---------- Actualizaciones automáticas ---------- */
// Mantiene WordPress, los plugins y los temas al día sin intervención.
add_filter( 'allow_major_auto_core_updates', '__return_true' );
add_filter( 'auto_update_plugin', '__return_true' );
add_filter( 'auto_update_translation', '__return_true' );

/* ---------- Aviso si hay usuarios con nombres fáciles ---------- */
add_action(
	'admin_notices',
	function () {
		if ( ! current_user_can( 'list_users' ) ) {
			return;
		}
		$malos = array();
		foreach ( array( 'admin', 'admin1', 'administrador', 'administrator', 'grelectricidad' ) as $login ) {
			if ( get_user_by( 'login', $login ) ) {
				$malos[] = $login;
			}
		}
		if ( ! $malos ) {
			return;
		}
		printf(
			'<div class="notice notice-error"><p><strong>Seguridad:</strong> existe un usuario con un nombre fácil de adivinar (%s). Crea otro administrador con un nombre distinto, entra con él y borra este usuario desde <a href="%s">Usuarios</a>, atribuyendo su contenido al nuevo.</p></div>',
			esc_html( implode( ', ', $malos ) ),
			esc_url( admin_url( 'users.php' ) )
		);
	}
);
