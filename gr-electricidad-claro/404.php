<?php
/**
 * Página no encontrada.
 *
 * @package GR_Electricidad
 */

defined( 'ABSPATH' ) || exit;
get_header();
?>
<section class="notfound">
	<div class="wrap">
		<p class="label">Error 404</p>
		<h1 class="disp"><span class="outline">Sin</span> tensión</h1>
		<p class="lead" style="margin-top:32px;font-size:20px;color:var(--soft);max-width:620px">No encontramos esta página. Puede que haya cambiado de dirección al renovar la web.</p>
		<div class="actions" style="margin-top:32px">
			<a class="btn btn-red" href="<?php echo esc_url( home_url( '/' ) ); ?>">Ir al inicio <?php echo gr_flecha(); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
			<a class="btn btn-ghost" href="<?php echo esc_url( gr_url( 'contacto' ) ); ?>">Contacto</a>
		</div>
	</div>
</section>
<?php
get_footer();
