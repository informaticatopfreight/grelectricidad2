<?php
/**
 * Pie de página y aviso de cookies.
 *
 * @package GR_Electricidad
 */

defined( 'ABSPATH' ) || exit;
$ga = gr_dato( 'ga_id' );
?>
</main>

<footer class="site-footer">
	<div class="wrap footer-grid">
		<div>
			<img src="<?php echo esc_url( GR_URI . '/assets/img/logo-gr-electricidad.png' ); ?>" alt="<?php echo esc_attr( gr_dato( 'razon_social' ) ); ?>" width="593" height="422" loading="lazy">
			<p style="margin-top:20px;color:var(--muted)">Instalaciones eléctricas para empresa e industria desde <?php echo esc_html( gr_dato( 'anio_fundacion' ) ); ?>.</p>
		</div>
		<div>
			<p class="mono">Sede</p>
			<p><?php echo esc_html( gr_dato( 'direccion' ) ); ?><br><?php echo esc_html( gr_dato( 'cp' ) . ' ' . gr_dato( 'localidad' ) . ' (' . gr_dato( 'provincia' ) . ')' ); ?></p>
		</div>
		<div>
			<p class="mono">Talleres</p>
			<p><?php echo nl2br( esc_html( gr_dato( 'talleres' ) ) ); ?><br><?php echo esc_html( gr_dato( 'cp' ) . ' ' . gr_dato( 'localidad' ) . ' (' . gr_dato( 'provincia' ) . ')' ); ?></p>
		</div>
		<div>
			<p class="mono">Teléfonos</p>
			<p>
				<a href="<?php echo esc_attr( gr_tel_href( gr_dato( 'tel_comercial' ) ) ); ?>"><?php echo esc_html( gr_dato( 'tel_comercial' ) ); ?></a> · presupuestos<br>
				<a href="<?php echo esc_attr( gr_tel_href( gr_dato( 'tel_admin' ) ) ); ?>"><?php echo esc_html( gr_dato( 'tel_admin' ) ); ?></a> · administración<br>
				<a href="<?php echo esc_attr( gr_tel_href( gr_dato( 'tel_fijo' ) ) ); ?>"><?php echo esc_html( gr_dato( 'tel_fijo' ) ); ?></a> · fijo
			</p>
		</div>
	</div>
	<div class="wrap footer-bottom">
		<span>© <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php echo esc_html( gr_dato( 'razon_social' ) ); ?> · CIF <?php echo esc_html( gr_dato( 'cif' ) ); ?></span>
		<nav aria-label="Legal">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'legal',
					'container'      => false,
					'depth'          => 1,
					'fallback_cb'    => 'gr_menu_legal_reserva',
				)
			);
			?>
			<?php if ( $ga ) : ?>
				<button class="linklike js-cookie-open" type="button">Configurar cookies</button>
			<?php endif; ?>
		</nav>
	</div>
</footer>

<?php if ( $ga ) : ?>
<div class="cookie js-cookie" role="dialog" aria-modal="false" aria-labelledby="cookie-titulo" hidden>
	<p class="cookie-title" id="cookie-titulo">Usamos cookies</p>
	<p>Las técnicas son necesarias para que la web funcione. Con tu permiso, usaremos además cookies de analítica para saber cómo se usa la web. Puedes aceptarlas, rechazarlas o elegir. <a href="<?php echo esc_url( gr_url( 'politica-de-cookies' ) ); ?>">Política de cookies</a></p>
	<div class="cookie-prefs js-cookie-prefs" hidden>
		<label><input type="checkbox" checked disabled> <span>Técnicas (siempre activas)</span></label>
		<label><input type="checkbox" class="js-cookie-analytics"> <span>Analítica (Google Analytics)</span></label>
	</div>
	<div class="cookie-actions">
		<button class="ck js-cookie-accept" type="button">Aceptar todas</button>
		<button class="ck js-cookie-reject" type="button">Rechazar</button>
		<button class="ck ck-ghost js-cookie-config" type="button">Configurar</button>
		<button class="ck js-cookie-save" type="button" hidden>Guardar selección</button>
	</div>
</div>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
