<?php
/**
 * Página «Contacto».
 * El mapa es un enlace a Google Maps (no se incrusta: así no hace falta clave de API
 * ni se instalan cookies de Google sin permiso).
 *
 * @package GR_Electricidad
 */

defined( 'ABSPATH' ) || exit;
get_header();

$direccion = gr_dato( 'direccion' ) . ', ' . gr_dato( 'cp' ) . ' ' . gr_dato( 'localidad' ) . ', ' . gr_dato( 'provincia' );

while ( have_posts() ) :
	the_post();
	gr_cabecera_pagina( has_excerpt() ? get_the_excerpt() : '' );
	?>
	<?php if ( trim( get_the_content() ) ) : ?>
		<div class="prose" style="padding-bottom:0"><?php the_content(); ?></div>
	<?php endif; ?>
	<section class="wrap" style="padding-top:64px" aria-label="Dónde estamos">
		<div class="footer-grid">
			<div class="js-up"><p class="mono" style="color:var(--red-text)">Sede / correo postal</p><p><?php echo esc_html( gr_dato( 'direccion' ) ); ?><br><?php echo esc_html( gr_dato( 'cp' ) . ' ' . gr_dato( 'localidad' ) . ' (' . gr_dato( 'provincia' ) . ')' ); ?></p></div>
			<div class="js-up"><p class="mono" style="color:var(--red-text)">Talleres</p><p><?php echo nl2br( esc_html( gr_dato( 'talleres' ) ) ); ?><br><?php echo esc_html( gr_dato( 'cp' ) . ' ' . gr_dato( 'localidad' ) . ' (' . gr_dato( 'provincia' ) . ')' ); ?></p></div>
			<div class="js-up" style="align-self:end"><a class="btn btn-ghost" href="<?php echo esc_url( 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( $direccion ) ); ?>" target="_blank" rel="noopener">Cómo llegar <?php echo gr_flecha(); // phpcs:ignore WordPress.Security.EscapeOutput ?></a></div>
		</div>
	</section>
	<?php
endwhile;

gr_bloque_contacto( false );
get_footer();
