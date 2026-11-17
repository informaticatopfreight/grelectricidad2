<?php
/**
 * Página «Acreditaciones».
 *
 * @package GR_Electricidad
 */

defined( 'ABSPATH' ) || exit;
get_header();

while ( have_posts() ) :
	the_post();
	gr_cabecera_pagina( has_excerpt() ? get_the_excerpt() : '' );
	?>
	<div class="prose" style="padding-bottom:40px"><?php the_content(); ?></div>
	<section class="accr" style="background:none;padding-top:0">
		<div class="wrap">
			<ul class="accr-list" style="flex-basis:100%">
				<?php foreach ( gr_acreditaciones() as $i => $a ) : ?>
					<li class="js-slide"><span class="mono"><?php echo esc_html( str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span><span><?php echo esc_html( $a ); ?></span></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>
	<?php
endwhile;

gr_bloque_contacto( true );
get_footer();
