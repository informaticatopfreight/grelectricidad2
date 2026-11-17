<?php
/**
 * Página «Obras»: galería con las imágenes adjuntas a esta página.
 * Para añadir fotos: edita la página Obras y sube las imágenes desde ella
 * (o, en Medios, «Adjuntar» la imagen a la página Obras). Rellena siempre
 * el «Texto alternativo» y la «Leyenda» de cada foto.
 *
 * @package GR_Electricidad
 */

defined( 'ABSPATH' ) || exit;
get_header();

while ( have_posts() ) :
	the_post();
	gr_cabecera_pagina( has_excerpt() ? get_the_excerpt() : '' );
	$fotos = gr_fotos_obras( -1 );
	?>
	<?php if ( trim( get_the_content() ) ) : ?>
		<div class="prose" style="padding-bottom:0"><?php the_content(); ?></div>
	<?php endif; ?>
	<section class="works">
		<div class="wrap works-grid">
			<?php foreach ( $fotos as $f ) : ?>
				<figure class="js-up">
					<a href="<?php echo esc_url( wp_get_attachment_image_url( $f->ID, 'full' ) ); ?>">
						<?php echo wp_get_attachment_image( $f->ID, 'gr-tarjeta', false, array( 'loading' => 'lazy' ) ); ?>
					</a>
					<?php if ( wp_get_attachment_caption( $f->ID ) ) : ?>
						<figcaption><?php echo esc_html( wp_get_attachment_caption( $f->ID ) ); ?></figcaption>
					<?php endif; ?>
				</figure>
			<?php endforeach; ?>
		</div>
	</section>
	<?php
endwhile;

gr_bloque_contacto( true );
get_footer();
