<?php
/**
 * Plantilla de reserva (entradas, búsquedas, archivos).
 *
 * @package GR_Electricidad
 */

defined( 'ABSPATH' ) || exit;
get_header();
?>
<header class="page-hero">
	<div class="wrap">
		<h1 class="disp"><?php echo is_search() ? 'Búsqueda' : esc_html( wp_strip_all_tags( get_the_archive_title() ? get_the_archive_title() : 'Noticias' ) ); ?></h1>
	</div>
</header>
<div class="prose">
	<?php if ( have_posts() ) : ?>
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article style="margin-bottom:48px">
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<p class="muted"><?php echo esc_html( get_the_date() ); ?></p>
				<?php if ( is_singular() ) : ?>
					<?php the_content(); ?>
				<?php else : ?>
					<?php the_excerpt(); ?>
				<?php endif; ?>
			</article>
		<?php endwhile; ?>
		<?php the_posts_pagination(); ?>
	<?php else : ?>
		<p>No hay contenido que mostrar.</p>
	<?php endif; ?>
</div>
<?php
get_footer();
