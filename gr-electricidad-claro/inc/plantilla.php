<?php
/**
 * Piezas reutilizables de las plantillas.
 *
 * @package GR_Electricidad
 */

defined( 'ABSPATH' ) || exit;

/**
 * Icono de flecha.
 */
function gr_flecha() {
	return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 12h14M13 6l6 6-6 6"/></svg>';
}

/**
 * Icono de teléfono.
 */
function gr_icono_tel() {
	return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"/></svg>';
}

/**
 * Enlace a una página por su ruta (slug).
 *
 * @param string $ruta Ruta de la página.
 */
function gr_url( $ruta ) {
	$p = get_page_by_path( $ruta );
	return $p ? get_permalink( $p ) : home_url( '/' . $ruta . '/' );
}

/**
 * Cláusula de protección de datos para currículums.
 */
function gr_clausula_cv() {
	return sprintf(
		'<p class="legal-note"><strong>Protección de datos.</strong> Responsable: %1$s (%2$s). Finalidad: valorar tu candidatura en nuestros procesos de selección. Legitimación: tu consentimiento al enviarnos el currículum. Conservación: 12 meses; después lo eliminamos. No se cede a terceros. Puedes acceder, rectificar o suprimir tus datos escribiendo a <a href="mailto:%3$s">%3$s</a>. Más información en la <a href="%4$s">Política de privacidad</a>.</p>',
		esc_html( gr_dato( 'razon_social' ) ),
		esc_html( gr_dato( 'cif' ) ),
		esc_html( gr_dato( 'email_legal' ) ),
		esc_url( gr_url( 'politica-de-privacidad' ) )
	);
}

/**
 * Formulario de presupuesto.
 */
function gr_formulario() {
	$sel = '';
	if ( is_page() && wp_get_post_parent_id( get_queried_object_id() ) ) {
		$sel = get_the_title( get_queried_object_id() );
	}
	ob_start();
	?>
	<form class="quote-form js-up" id="presupuesto" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" aria-labelledby="presupuesto-titulo">
		<h2 class="form-title" id="presupuesto-titulo">Pide tu presupuesto</h2>
		<?php echo gr_aviso_formulario(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<input type="hidden" name="action" value="gr_presupuesto">
		<input type="hidden" name="t" value="<?php echo esc_attr( time() ); ?>">
		<?php wp_nonce_field( 'gr_presupuesto', 'gr_nonce' ); ?>
		<div class="hp" aria-hidden="true"><label for="f-web">No rellenar</label><input id="f-web" type="text" name="web" tabindex="-1" autocomplete="off"></div>
		<div class="field"><label for="f-nombre">Nombre *</label><input id="f-nombre" name="nombre" type="text" autocomplete="name" required></div>
		<div class="field"><label for="f-empresa">Empresa</label><input id="f-empresa" name="empresa" type="text" autocomplete="organization"></div>
		<div class="field"><label for="f-tel">Teléfono</label><input id="f-tel" name="telefono" type="tel" autocomplete="tel"></div>
		<div class="field"><label for="f-mail">Email</label><input id="f-mail" name="email" type="email" autocomplete="email"></div>
		<div class="field full"><label for="f-serv">Servicio</label>
			<select id="f-serv" name="servicio">
				<?php foreach ( gr_opciones_servicio() as $op ) : ?>
					<option<?php selected( $sel, $op ); ?>><?php echo esc_html( $op ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="field full"><label for="f-msg">Cuéntanos tu proyecto</label><textarea id="f-msg" name="mensaje" rows="4"></textarea></div>
		<label class="check"><input type="checkbox" name="privacidad" value="1" required>
			<span>He leído y acepto la <a href="<?php echo esc_url( gr_url( 'politica-de-privacidad' ) ); ?>">Política de privacidad</a>. Responsable: <?php echo esc_html( gr_dato( 'razon_social' ) ); ?>. Finalidad: responder a tu solicitud y enviarte el presupuesto. Legitimación: tu consentimiento. No se cederán datos a terceros salvo obligación legal. Derechos: acceso, rectificación y supresión en <?php echo esc_html( gr_dato( 'email_legal' ) ); ?>.</span>
		</label>
		<button class="btn btn-red" type="submit">Enviar solicitud <?php echo gr_flecha(); // phpcs:ignore WordPress.Security.EscapeOutput ?></button>
		<p class="muted" style="flex-basis:100%;margin:0;font-size:13px">* Necesitamos tu nombre y un teléfono o email para contestarte.</p>
	</form>
	<?php
	return ob_get_clean();
}

/**
 * Bloque de contacto completo (datos + formulario).
 *
 * @param bool $titulo_grande Muestra el «¿Hablamos?» gigante.
 */
function gr_bloque_contacto( $titulo_grande = true ) {
	?>
	<section class="contact" id="contacto" aria-label="Contacto">
		<div class="wrap">
			<?php if ( $titulo_grande ) : ?>
				<p class="label js-up">(Contacto)</p>
				<p class="disp js-up" style="font-size:clamp(64px,13vw,220px);margin:0" aria-hidden="true"><span class="cta-word outline">¿Hablamos?</span></p>
			<?php endif; ?>
			<div class="contact-grid">
				<div class="contact-data js-up">
					<div><p class="mono">Contrataciones y presupuestos</p><a class="big-phone" href="<?php echo esc_attr( gr_tel_href( gr_dato( 'tel_comercial' ) ) ); ?>"><?php echo esc_html( gr_dato( 'tel_comercial' ) ); ?></a></div>
					<div><p class="mono">Administración</p>
						<a class="phone" href="<?php echo esc_attr( gr_tel_href( gr_dato( 'tel_admin' ) ) ); ?>"><?php echo esc_html( gr_dato( 'tel_admin' ) ); ?></a> ·
						<a class="phone" href="<?php echo esc_attr( gr_tel_href( gr_dato( 'tel_fijo' ) ) ); ?>"><?php echo esc_html( gr_dato( 'tel_fijo' ) ); ?></a>
					</div>
					<div><p class="mono">Email</p><a class="phone" style="font-size:20px" href="mailto:<?php echo esc_attr( gr_dato( 'email' ) ); ?>"><?php echo esc_html( gr_dato( 'email' ) ); ?></a></div>
					<div><p class="mono">Trabaja con nosotros</p>
						<p>Envía tu currículum a <a href="mailto:<?php echo esc_attr( gr_dato( 'email_rrhh' ) ); ?>"><?php echo esc_html( gr_dato( 'email_rrhh' ) ); ?></a>.</p>
						<?php echo gr_clausula_cv(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</div>
				</div>
				<?php echo gr_formulario(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Cabecera de las páginas interiores.
 *
 * @param string $lead Entradilla opcional.
 */
function gr_cabecera_pagina( $lead = '' ) {
	$id    = get_queried_object_id();
	$padre = wp_get_post_parent_id( $id );
	?>
	<header class="page-hero">
		<div class="wrap">
			<nav class="crumbs" aria-label="Estás aquí">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Inicio</a>
				<?php if ( $padre ) : ?>
					/ <a href="<?php echo esc_url( get_permalink( $padre ) ); ?>"><?php echo esc_html( get_the_title( $padre ) ); ?></a>
				<?php endif; ?>
				/ <span aria-current="page"><?php echo esc_html( get_the_title( $id ) ); ?></span>
			</nav>
			<h1 class="disp js-split"><?php echo esc_html( get_the_title( $id ) ); ?></h1>
			<?php if ( $lead ) : ?>
				<p class="lead js-up"><?php echo esc_html( $lead ); ?></p>
			<?php endif; ?>
		</div>
		<?php if ( has_post_thumbnail( $id ) ) : ?>
			<div class="wrap">
				<div class="page-media js-reveal"><?php echo get_the_post_thumbnail( $id, 'gr-ancha', array( 'class' => 'js-parallax', 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?></div>
			</div>
		<?php endif; ?>
	</header>
	<?php
}

/**
 * Tarjetas con los demás servicios.
 *
 * @param int $excluir ID a excluir.
 */
function gr_otros_servicios( $excluir = 0 ) {
	$lista = array_filter( gr_servicios(), fn( $p ) => $p->ID !== $excluir );
	if ( ! $lista ) {
		return;
	}
	?>
	<section class="more">
		<div class="wrap">
			<h2 class="disp js-up"><?php echo $excluir ? 'Otros servicios' : 'Nuestros servicios'; ?></h2>
			<div class="more-grid">
				<?php foreach ( array_values( $lista ) as $i => $p ) : ?>
					<a class="more-card js-up" href="<?php echo esc_url( get_permalink( $p ) ); ?>">
						<?php
						if ( has_post_thumbnail( $p ) ) {
							echo get_the_post_thumbnail( $p, 'gr-tarjeta', array( 'class' => 'more-img', 'data-decorativa' => '1', 'loading' => 'lazy' ) );
						}
						?>
						<span class="more-shade" aria-hidden="true"></span>
						<span class="more-body">
							<span class="more-top"><span class="mono more-n"><?php echo esc_html( str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span><span class="more-arr"><?php echo gr_flecha(); // phpcs:ignore WordPress.Security.EscapeOutput ?></span></span>
							<strong><?php echo esc_html( get_the_title( $p ) ); ?></strong>
						</span>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Menú de reserva si todavía no se ha creado el menú principal.
 */
function gr_menu_reserva() {
	echo '<ul>';
	foreach ( array( 'empresa' => 'Empresa', 'servicios' => 'Servicios', 'obras' => 'Obras', 'acreditaciones' => 'Acreditaciones', 'contacto' => 'Contacto' ) as $ruta => $txt ) {
		printf( '<li><a href="%s">%s</a></li>', esc_url( gr_url( $ruta ) ), esc_html( $txt ) );
	}
	echo '</ul>';
}

/**
 * Menú legal de reserva.
 */
function gr_menu_legal_reserva() {
	echo '<ul>';
	foreach ( array( 'aviso-legal' => 'Aviso legal', 'politica-de-privacidad' => 'Política de privacidad', 'politica-de-cookies' => 'Política de cookies' ) as $ruta => $txt ) {
		printf( '<li><a href="%s">%s</a></li>', esc_url( gr_url( $ruta ) ), esc_html( $txt ) );
	}
	echo '</ul>';
}

/**
 * Cinta de logotipos de clientes.
 */
function gr_bloque_clientes() {
	$clientes = gr_clientes();
	if ( ! $clientes ) {
		return;
	}
	?>
	<section class="clients" aria-labelledby="titulo-clientes">
		<div class="wrap section-head">
			<h2 class="disp js-up" id="titulo-clientes">Han confiado <span class="outline">en nosotros</span></h2>
			<p class="js-up">Industria, hostelería y construcción: empresas para las que hemos trabajado.</p>
		</div>
		<div class="track clients-track">
			<?php foreach ( array( false, true ) as $copia ) : ?>
				<?php foreach ( $clientes as $archivo => $nombre ) : ?>
					<div class="client"<?php echo $copia ? ' aria-hidden="true"' : ''; ?>>
						<img src="<?php echo esc_url( GR_URI . '/assets/img/clientes/' . $archivo ); ?>" alt="<?php echo $copia ? '' : esc_attr( $nombre ); ?>" loading="lazy">
					</div>
				<?php endforeach; ?>
			<?php endforeach; ?>
		</div>
	</section>
	<?php
}
