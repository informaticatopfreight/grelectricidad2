<?php
/**
 * Apariencia → Personalizar → Datos de GR Electricidad.
 *
 * @package GR_Electricidad
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'customize_register',
	function ( WP_Customize_Manager $wpc ) {
		$wpc->add_section(
			'gr_datos',
			array(
				'title'       => 'Datos de GR Electricidad',
				'priority'    => 30,
				'description' => 'Datos que aparecen en la web, en los textos legales y en los datos para Google.',
			)
		);

		$campos = array(
			'razon_social'  => array( 'Razón social', 'text' ),
			'cif'           => array( 'CIF', 'text' ),
			'direccion'     => array( 'Dirección de la sede', 'text' ),
			'cp'            => array( 'Código postal', 'text' ),
			'localidad'     => array( 'Localidad', 'text' ),
			'provincia'     => array( 'Provincia', 'text' ),
			'almacen'       => array( 'Dirección del almacén', 'text' ),
			'talleres'      => array( 'Dirección de los talleres', 'textarea' ),
			'tel_comercial' => array( 'Teléfono de contrataciones y presupuestos', 'text' ),
			'tel_admin'     => array( 'Teléfono móvil de administración', 'text' ),
			'tel_fijo'      => array( 'Teléfono fijo / fax', 'text' ),
			'email'         => array( 'Email de contacto (visible en la web)', 'email' ),
			'email_legal'   => array( 'Email para privacidad y temas legales', 'email' ),
			'email_rrhh'    => array( 'Email para currículums', 'email' ),
			'email_form'    => array( 'Email donde llegan las solicitudes de presupuesto', 'email' ),
			'registro'      => array( 'Datos del Registro Mercantil (tomo, folio, hoja)', 'text' ),
			'ga_id'         => array( 'ID de Google Analytics 4 (G-XXXXXXX). Vacío = sin analítica y sin aviso de cookies.', 'text' ),
		);
		$def    = gr_datos_por_defecto();

		foreach ( $campos as $clave => $c ) {
			$wpc->add_setting(
				'gr_' . $clave,
				array(
					'default'           => $def[ $clave ],
					'sanitize_callback' => 'email' === $c[1] ? 'sanitize_email' : ( 'textarea' === $c[1] ? 'sanitize_textarea_field' : 'sanitize_text_field' ),
				)
			);
			$wpc->add_control(
				'gr_' . $clave,
				array(
					'label'   => $c[0],
					'section' => 'gr_datos',
					'type'    => $c[1],
				)
			);
		}
	}
);

/**
 * El ID de Analytics debe tener formato G-XXXX; si no, se ignora.
 */
add_filter(
	'theme_mod_gr_ga_id',
	function ( $v ) {
		$v = strtoupper( trim( (string) $v ) );
		return preg_match( '/^G-[A-Z0-9]{4,}$/', $v ) ? $v : '';
	}
);
