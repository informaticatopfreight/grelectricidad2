<?php
/**
 * Páginas: textos legales, Empresa, Servicios (y cada servicio).
 *
 * @package GR_Electricidad
 */

defined( 'ABSPATH' ) || exit;
get_header();

while ( have_posts() ) :
	the_post();
	$id         = get_the_ID();
	$padre      = wp_get_post_parent_id( $id );
	$servicios  = get_page_by_path( 'servicios' );
	$es_serv    = $servicios && $padre === $servicios->ID;
	$es_listado = $servicios && $id === $servicios->ID;
	$legal      = in_array( get_post_field( 'post_name', $id ), array( 'aviso-legal', 'politica-de-privacidad', 'politica-de-cookies' ), true );

	gr_cabecera_pagina( ( $es_serv || $es_listado || 'empresa' === get_post_field( 'post_name', $id ) ) && has_excerpt() ? get_the_excerpt() : '' );
	?>

	<article class="prose js-prose">
		<?php if ( $legal ) : ?>
			<p class="muted">Última actualización: <?php echo esc_html( wp_date( 'F \d\e Y', get_post_modified_time( 'U', true ) ) ); ?></p>
		<?php endif; ?>
		<?php the_content(); ?>
		<?php if ( 'politica-de-cookies' === get_post_field( 'post_name', $id ) && gr_dato( 'ga_id' ) ) : ?>
			<p><button class="btn btn-red js-cookie-open" type="button">Configurar cookies</button></p>
		<?php endif; ?>
		<?php if ( $es_serv ) : ?>
			<p style="margin-top:48px"><a class="btn btn-red" href="#presupuesto" style="color:#fff">Pide presupuesto <?php echo gr_flecha(); // phpcs:ignore WordPress.Security.EscapeOutput ?></a></p>
		<?php endif; ?>
	</article>

	<?php
	if ( $es_listado ) {
		gr_otros_servicios( 0 );
	} elseif ( $es_serv ) {
		gr_otros_servicios( $id );
		gr_bloque_contacto( false );
	} elseif ( 'empresa' === get_post_field( 'post_name', $id ) ) {
		gr_otros_servicios( 0 );
		gr_bloque_clientes();
	}
endwhile;

get_footer();
