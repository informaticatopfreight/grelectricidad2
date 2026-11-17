<?php
/**
 * Cabecera.
 *
 * @package GR_Electricidad
 */

defined( 'ABSPATH' ) || exit;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#contenido">Saltar al contenido</a>

<header class="site-header js-header">
	<div class="wrap">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( gr_dato( 'razon_social' ) ); ?>, inicio">
			<img src="<?php echo esc_url( GR_URI . '/assets/img/logo-gr-electricidad.png' ); ?>" alt="<?php echo esc_attr( gr_dato( 'razon_social' ) ); ?>" width="593" height="422">
		</a>

		<nav class="nav-main js-nav" id="navegacion" aria-label="Principal">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'principal',
					'container'      => false,
					'fallback_cb'    => 'gr_menu_reserva',
					'depth'          => 2,
				)
			);
			?>
		</nav>

		<div class="header-cta">
			<a class="btn btn-red btn-sm" href="<?php echo esc_attr( gr_tel_href( gr_dato( 'tel_comercial' ) ) ); ?>" aria-label="Llamar al <?php echo esc_attr( gr_dato( 'tel_comercial' ) ); ?>">
				<?php echo gr_icono_tel(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<span class="btn-phone-text"><?php echo esc_html( gr_dato( 'tel_comercial' ) ); ?></span>
			</a>
			<button class="nav-toggle js-nav-toggle" type="button" aria-expanded="false" aria-controls="navegacion">Menú</button>
		</div>
	</div>
</header>

<main id="contenido">
