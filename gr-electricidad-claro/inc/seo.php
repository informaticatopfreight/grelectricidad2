<?php
/**
 * SEO: descripción para Google, Open Graph, datos estructurados de empresa local,
 * redirecciones de las direcciones antiguas y texto alternativo en imágenes.
 *
 * Si se instala Yoast SEO o Rank Math, el tema deja de imprimir sus propias etiquetas.
 *
 * @package GR_Electricidad
 */

defined( 'ABSPATH' ) || exit;

/**
 * ¿Hay un plugin de SEO activo?
 */
function gr_hay_plugin_seo() {
	return defined( 'WPSEO_VERSION' ) || class_exists( 'RankMath' ) || defined( 'AIOSEO_VERSION' ) || defined( 'SEOPRESS_VERSION' );
}

/**
 * Descripción de la página actual.
 */
function gr_descripcion() {
	if ( is_front_page() ) {
		$id = (int) get_option( 'page_on_front' );
	} elseif ( is_singular() ) {
		$id = get_queried_object_id();
	} else {
		$id = 0;
	}
	$desc = $id ? get_post_meta( $id, '_gr_descripcion', true ) : '';
	if ( ! $desc && $id && has_excerpt( $id ) ) {
		$desc = get_the_excerpt( $id );
	}
	if ( ! $desc ) {
		$desc = 'GR Electricidad, S.L.: instalaciones eléctricas, mantenimiento en alta, media y baja tensión, energía solar fotovoltaica y videovigilancia para empresas e industria en Pantoja (Toledo).';
	}
	return wp_strip_all_tags( $desc );
}

/**
 * Separador del título.
 */
add_filter( 'document_title_separator', fn() => '·' );

/**
 * Etiquetas meta en el <head>.
 */
function gr_meta_head() {
	if ( gr_hay_plugin_seo() ) {
		return;
	}
	$desc  = gr_descripcion();
	$title = wp_get_document_title();
	$url   = is_front_page() ? home_url( '/' ) : ( is_singular() ? get_permalink() : '' );
	$img   = '';
	if ( is_singular() && has_post_thumbnail() ) {
		$img = get_the_post_thumbnail_url( null, 'gr-ancha' );
	}
	if ( ! $img ) {
		$img = GR_URI . '/assets/img/portada.jpg';
	}

	printf( '<meta name="description" content="%s">' . "\n", esc_attr( $desc ) );
	echo '<meta property="og:locale" content="es_ES">' . "\n";
	printf( '<meta property="og:type" content="%s">' . "\n", is_front_page() ? 'website' : 'article' );
	printf( '<meta property="og:site_name" content="%s">' . "\n", esc_attr( get_bloginfo( 'name' ) ) );
	printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );
	printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $desc ) );
	if ( $url ) {
		printf( '<meta property="og:url" content="%s">' . "\n", esc_url( $url ) );
	}
	printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $img ) );
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";

	if ( is_search() || is_404() ) {
		echo '<meta name="robots" content="noindex, follow">' . "\n";
	}
}
add_action( 'wp_head', 'gr_meta_head', 1 );

/**
 * Datos estructurados (schema.org) de empresa local: ayudan a aparecer en búsquedas
 * del tipo «electricista industrial Toledo» y en Google Maps.
 */
function gr_schema() {
	if ( ! is_front_page() && ! is_page( array( 'contacto', 'empresa' ) ) ) {
		return;
	}
	$data = array(
		'@context'     => 'https://schema.org',
		'@type'        => 'Electrician',
		'@id'          => home_url( '/#empresa' ),
		'name'         => gr_dato( 'razon_social' ),
		'legalName'    => gr_dato( 'razon_social' ),
		'taxID'        => gr_dato( 'cif' ),
		'url'          => home_url( '/' ),
		'logo'         => GR_URI . '/assets/img/logo-gr-electricidad.png',
		'image'        => GR_URI . '/assets/img/portada.jpg',
		'description'  => 'Instalaciones eléctricas, mantenimiento en alta, media y baja tensión, energía solar fotovoltaica, videovigilancia y asesoramiento técnico para empresas e industria.',
		'telephone'    => '+34 ' . gr_dato( 'tel_comercial' ),
		'email'        => gr_dato( 'email' ),
		'foundingDate' => gr_dato( 'anio_fundacion' ),
		'address'      => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => gr_dato( 'direccion' ),
			'postalCode'      => gr_dato( 'cp' ),
			'addressLocality' => gr_dato( 'localidad' ),
			'addressRegion'   => gr_dato( 'provincia' ),
			'addressCountry'  => 'ES',
		),
		'areaServed'   => array( 'Toledo', 'Castilla-La Mancha', 'Madrid', 'España' ),
		'knowsAbout'   => array( 'Instalaciones eléctricas de alta tensión', 'Instalaciones eléctricas de baja tensión', 'Centros de transformación', 'Mantenimiento eléctrico', 'Energía solar fotovoltaica', 'Autoconsumo', 'CCTV', 'Climatización' ),
	);
	$servicios = gr_servicios();
	if ( $servicios ) {
		$data['hasOfferCatalog'] = array(
			'@type'           => 'OfferCatalog',
			'name'            => 'Servicios',
			'itemListElement' => array_map(
				fn( $p ) => array(
					'@type'       => 'Offer',
					'itemOffered' => array(
						'@type' => 'Service',
						'name'  => get_the_title( $p ),
						'url'   => get_permalink( $p ),
					),
				),
				$servicios
			),
		);
	}
	echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'gr_schema', 20 );

/**
 * Texto alternativo de reserva: si una imagen no tiene «alt», se usa su título.
 */
add_filter(
	'wp_get_attachment_image_attributes',
	function ( $attr, $attachment ) {
		// Las imágenes decorativas (marcadas con data-decorativa) mantienen alt="".
		if ( isset( $attr['data-decorativa'] ) ) {
			$attr['alt'] = '';
			unset( $attr['data-decorativa'] );
			return $attr;
		}
		if ( empty( $attr['alt'] ) ) {
			$attr['alt'] = get_the_title( $attachment );
		}
		return $attr;
	},
	10,
	2
);

/**
 * Redirecciones 301 desde las direcciones de la web antigua, para no perder posicionamiento.
 */
function gr_redirecciones_antiguas() {
	if ( ! is_404() ) {
		return;
	}
	$mapa = array(
		'instalacion-y-montaje-de-instalaciones-electricas'    => 'servicios/instalaciones-electricas',
		'mantenimiento-electrico-de-alta-media-y-baja-tension' => 'servicios/mantenimiento-electrico',
		'instalaciones-de-energia-solar-fotovoltaica'          => 'servicios/energia-solar-fotovoltaica',
		'instalaciones-de-cctv-vigilancia-con-camaras'         => 'servicios/cctv-videovigilancia',
		'trabajos'                    => 'obras',
		'acreditaciones-profesionales' => 'acreditaciones',
		'hello-world'                 => '',
		'sitemap.xml'                 => 'wp-sitemap.xml',
	);
	$ruta = trim( (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ), '/' ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
	if ( isset( $mapa[ $ruta ] ) ) {
		wp_safe_redirect( home_url( '/' . ( $mapa[ $ruta ] ? $mapa[ $ruta ] . ( str_contains( $mapa[ $ruta ], '.xml' ) ? '' : '/' ) : '' ) ), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'gr_redirecciones_antiguas', 5 );

/* ---------- Caja «Descripción para Google» en el editor de páginas ---------- */

add_action(
	'add_meta_boxes',
	function () {
		if ( gr_hay_plugin_seo() ) {
			return;
		}
		add_meta_box( 'gr_seo', 'Descripción para Google', 'gr_caja_seo', array( 'page', 'post' ), 'side', 'default' );
	}
);

/**
 * Caja de la descripción.
 *
 * @param WP_Post $post Entrada.
 */
function gr_caja_seo( $post ) {
	wp_nonce_field( 'gr_seo', 'gr_seo_nonce' );
	$val = get_post_meta( $post->ID, '_gr_descripcion', true );
	echo '<p><label for="gr_descripcion">Texto que Google muestra bajo el título (entre 120 y 155 caracteres).</label></p>';
	printf( '<textarea id="gr_descripcion" name="gr_descripcion" rows="5" style="width:100%%" maxlength="320">%s</textarea>', esc_textarea( $val ) );
	echo '<p class="description" id="gr_desc_count"></p>';
	echo "<script>(function(){var t=document.getElementById('gr_descripcion'),c=document.getElementById('gr_desc_count');function u(){c.textContent=t.value.length+' caracteres';}t.addEventListener('input',u);u();})();</script>";
}

add_action(
	'save_post',
	function ( $post_id ) {
		if ( ! isset( $_POST['gr_seo_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['gr_seo_nonce'] ), 'gr_seo' ) ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		$val = isset( $_POST['gr_descripcion'] ) ? sanitize_textarea_field( wp_unslash( $_POST['gr_descripcion'] ) ) : '';
		update_post_meta( $post_id, '_gr_descripcion', $val );
	}
);
