<?php
/**
 * Formulario de presupuesto: validación, protección antispam y envío por email.
 * No se guardan los mensajes en la base de datos: solo se envían por correo.
 *
 * @package GR_Electricidad
 */

defined( 'ABSPATH' ) || exit;

/**
 * Servicios que se pueden elegir en el formulario.
 */
function gr_opciones_servicio() {
	$lista = array_map( fn( $p ) => get_the_title( $p ), gr_servicios() );
	if ( ! $lista ) {
		$lista = array( 'Instalación y montaje eléctrico', 'Mantenimiento AT · MT · BT', 'Energía solar fotovoltaica', 'CCTV y videovigilancia', 'Climatización', 'Asesoramiento técnico' );
	}
	$lista[] = 'Otro';
	return $lista;
}

/**
 * Procesa el envío.
 */
function gr_procesar_presupuesto() {
	$volver = wp_get_referer() ? wp_get_referer() : home_url( '/contacto/' );
	$volver = remove_query_arg( 'presupuesto', $volver );
	$salir  = function ( $estado ) use ( $volver ) {
		wp_safe_redirect( add_query_arg( 'presupuesto', $estado, $volver ) . '#presupuesto' );
		exit;
	};

	if ( ! isset( $_POST['gr_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['gr_nonce'] ), 'gr_presupuesto' ) ) {
		$salir( 'caducado' );
	}

	// Trampa para robots: un campo oculto que una persona nunca rellena.
	if ( ! empty( $_POST['web'] ) ) {
		$salir( 'ok' );
	}

	// Un robot envía el formulario en menos de 3 segundos.
	$t = isset( $_POST['t'] ) ? (int) $_POST['t'] : 0;
	if ( $t && ( time() - $t ) < 3 ) {
		$salir( 'ok' );
	}

	// Límite: 5 envíos por hora desde la misma conexión.
	$ip    = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$clave = 'gr_form_' . md5( $ip . wp_salt() );
	$n     = (int) get_transient( $clave );
	if ( $n >= 5 ) {
		$salir( 'limite' );
	}

	$nombre   = sanitize_text_field( wp_unslash( $_POST['nombre'] ?? '' ) );
	$empresa  = sanitize_text_field( wp_unslash( $_POST['empresa'] ?? '' ) );
	$telefono = sanitize_text_field( wp_unslash( $_POST['telefono'] ?? '' ) );
	$email    = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$servicio = sanitize_text_field( wp_unslash( $_POST['servicio'] ?? '' ) );
	$mensaje  = sanitize_textarea_field( wp_unslash( $_POST['mensaje'] ?? '' ) );
	$acepta   = ! empty( $_POST['privacidad'] );

	if ( ! $nombre || ( ! $telefono && ! is_email( $email ) ) || ! $acepta ) {
		$salir( 'faltan' );
	}
	if ( ! in_array( $servicio, gr_opciones_servicio(), true ) ) {
		$servicio = 'Otro';
	}

	$para   = gr_dato( 'email_form' );
	$asunto = sprintf( '[Web] Solicitud de presupuesto: %s — %s', $servicio, $nombre );
	$cuerpo = "Nueva solicitud de presupuesto desde la web.\n\n"
		. "Nombre: $nombre\n"
		. "Empresa: $empresa\n"
		. "Teléfono: $telefono\n"
		. "Email: $email\n"
		. "Servicio: $servicio\n\n"
		. "Mensaje:\n$mensaje\n\n"
		. '— Ha aceptado la política de privacidad el ' . wp_date( 'd/m/Y \a \l\a\s H:i' ) . ".\n";

	$cabeceras = array( 'Content-Type: text/plain; charset=UTF-8' );
	if ( is_email( $email ) ) {
		$cabeceras[] = 'Reply-To: ' . str_replace( array( "\r", "\n", '<', '>' ), '', $nombre ) . ' <' . $email . '>';
	}

	$ok = wp_mail( $para, $asunto, $cuerpo, $cabeceras );
	set_transient( $clave, $n + 1, HOUR_IN_SECONDS );

	$salir( $ok ? 'ok' : 'error' );
}
add_action( 'admin_post_nopriv_gr_presupuesto', 'gr_procesar_presupuesto' );
add_action( 'admin_post_gr_presupuesto', 'gr_procesar_presupuesto' );

/**
 * Mensaje tras el envío.
 */
function gr_aviso_formulario() {
	$estado = isset( $_GET['presupuesto'] ) ? sanitize_key( $_GET['presupuesto'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	$textos = array(
		'ok'       => array( 'ok', 'Gracias. Hemos recibido tu solicitud y te contestaremos lo antes posible.' ),
		'faltan'   => array( 'err', 'Falta algún dato: indica tu nombre, un teléfono o email y acepta la política de privacidad.' ),
		'limite'   => array( 'err', 'Has enviado varias solicitudes seguidas. Llámanos o inténtalo dentro de un rato.' ),
		'caducado' => array( 'err', 'La página llevaba mucho tiempo abierta. Vuelve a enviar el formulario, por favor.' ),
		'error'    => array( 'err', 'No hemos podido enviar el mensaje. Llámanos o escríbenos por email, por favor.' ),
	);
	if ( ! isset( $textos[ $estado ] ) ) {
		return '';
	}
	return sprintf( '<p class="notice notice-%s" role="status">%s</p>', esc_attr( $textos[ $estado ][0] ), esc_html( $textos[ $estado ][1] ) );
}
