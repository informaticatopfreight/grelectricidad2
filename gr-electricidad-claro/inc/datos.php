<?php
/**
 * Datos de la empresa. Se pueden cambiar en Apariencia → Personalizar → Datos de GR Electricidad.
 *
 * @package GR_Electricidad
 */

defined( 'ABSPATH' ) || exit;

/**
 * Valores por defecto.
 */
function gr_datos_por_defecto() {
	return array(
		'razon_social'   => 'GR Electricidad, S.L.',
		'cif'            => 'B45751120',
		'direccion'      => 'Calle Párroco Don Julián, 3',
		'cp'             => '45290',
		'localidad'      => 'Pantoja',
		'provincia'      => 'Toledo',
		'talleres'       => "Ctra. CM-4004, km 29\nPol. 16, parcela 1 «Las Lagunas»",
		'tel_comercial'  => '609 056 184',
		'tel_admin'      => '653 757 250',
		'tel_fijo'       => '925 554 298',
		'email'          => 'administracion@grelectricidad.es',
		'email_legal'    => 'grelectricidad@grelectricidad.es',
		'email_rrhh'     => 'recursoshumanos@grelectricidad.es',
		'email_form'     => 'grelectricidad@grelectricidad.es',
		'registro'       => 'Registro Mercantil de Toledo [completar: tomo, folio, hoja e inscripción]',
		'ga_id'          => '',
		'anio_fundacion' => '2007',
	);
}

/**
 * Devuelve un dato de la empresa.
 *
 * @param string $clave Clave del dato.
 * @return string
 */
function gr_dato( $clave ) {
	$def = gr_datos_por_defecto();
	$val = get_theme_mod( 'gr_' . $clave, $def[ $clave ] ?? '' );
	return is_string( $val ) ? trim( $val ) : '';
}

/**
 * Atajos para usar los datos dentro de los textos de las páginas, de modo que
 * si cambias un dato en Personalizar se actualiza también en los textos legales:
 *
 * [gr_dato clave="cif"]            → B45751120
 * [gr_dato clave="email_legal" enlace="1"] → enlace mailto
 * [gr_si_analytics]...[/gr_si_analytics]  → solo se muestra si hay Google Analytics
 * [gr_no_analytics]...[/gr_no_analytics]  → solo se muestra si NO hay Google Analytics
 */
add_shortcode(
	'gr_dato',
	function ( $atts ) {
		$atts = shortcode_atts(
			array(
				'clave'  => '',
				'enlace' => '',
			),
			$atts
		);
		$v = gr_dato( sanitize_key( $atts['clave'] ) );
		if ( $atts['enlace'] && is_email( $v ) ) {
			return sprintf( '<a href="mailto:%1$s">%1$s</a>', esc_html( $v ) );
		}
		if ( $atts['enlace'] && preg_match( '/^[\d\s]+$/', $v ) ) {
			return sprintf( '<a href="%s">%s</a>', esc_attr( gr_tel_href( $v ) ), esc_html( $v ) );
		}
		return nl2br( esc_html( $v ) );
	}
);
add_shortcode( 'gr_si_analytics', fn( $a, $c = '' ) => gr_dato( 'ga_id' ) ? do_shortcode( $c ) : '' );
add_shortcode( 'gr_no_analytics', fn( $a, $c = '' ) => gr_dato( 'ga_id' ) ? '' : do_shortcode( $c ) );

/**
 * Enlace tel: a partir de un teléfono español.
 *
 * @param string $tel Teléfono.
 * @return string
 */
function gr_tel_href( $tel ) {
	$num = preg_replace( '/\D+/', '', $tel );
	if ( 9 === strlen( $num ) ) {
		$num = '34' . $num;
	}
	return 'tel:+' . $num;
}

/**
 * Lista de acreditaciones (se muestra en portada y en la página Acreditaciones).
 *
 * @return string[]
 */
function gr_acreditaciones() {
	return apply_filters(
		'gr_acreditaciones',
		array(
			'Empresa autorizada en instalaciones eléctricas de baja tensión, categoría especialista',
			'Empresa autorizada en instalaciones de alta tensión, categoría AT1 (hasta 30 kV)',
			'Empresa autorizada en instalaciones de alta tensión, categoría AT2 (sin límite de tensión)',
			'Líneas de alta tensión, categoría LAT1 (hasta 30 kV)',
			'Instaladora, mantenedora y reparadora de equipos de refrigeración, aire acondicionado y bomba de calor con gases fluorados',
			'Empresa instaladora de telecomunicaciones',
			'Instalador certificado SolarEdge',
			'Inscrita en el REA (Registro de Empresas Acreditadas) para obras de construcción',
			'Inscrita en el Registro Oficial de Licitadores y Empresas Clasificadas del Estado (ROLECE)',
		)
	);
}

/**
 * Páginas hijas de «Servicios», en orden.
 *
 * @return WP_Post[]
 */
function gr_servicios() {
	$padre = get_page_by_path( 'servicios' );
	if ( ! $padre ) {
		return array();
	}
	return get_pages(
		array(
			'parent'      => $padre->ID,
			'sort_column' => 'menu_order,post_title',
			'post_status' => 'publish',
		)
	);
}

/**
 * Fotos de obra: imágenes adjuntas a la página «Obras».
 *
 * @param int $limite Número máximo (-1 = todas).
 * @return WP_Post[]
 */
function gr_fotos_obras( $limite = -1 ) {
	$obras = get_page_by_path( 'obras' );
	if ( ! $obras ) {
		return array();
	}
	return get_children(
		array(
			'post_parent'    => $obras->ID,
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'orderby'        => 'menu_order ID',
			'order'          => 'ASC',
			'numberposts'    => $limite,
		)
	);
}

/**
 * Clientes (logotipos en assets/img/clientes). Se muestran en la portada y en Empresa.
 * Para quitar uno, bórralo de esta lista.
 *
 * @return array<string,string> archivo => nombre
 */
function gr_clientes() {
	return apply_filters(
		'gr_clientes',
		array(
			'melia.png'         => 'Meliá Hotels & Resorts',
			'gestamp.png'       => 'Gestamp',
			'lafarge.png'       => 'Lafarge',
			'intersurgical.png' => 'Intersurgical',
			'ingesan.png'       => 'Ingesan (Grupo OHL)',
			'bongioanni.png'    => 'Bongioanni',
			'mecanotubo.png'    => 'Mecanotubo',
			'mesat.png'         => 'Mesat',
			'mwb.png'           => 'MWB Elektrotechnik Gruppe',
		)
	);
}
