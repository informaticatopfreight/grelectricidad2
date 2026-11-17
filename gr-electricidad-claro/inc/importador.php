<?php
/**
 * Apariencia → Configurar web GR.
 *
 * Un botón que deja la web lista en una instalación limpia de WordPress:
 * - Crea las páginas con sus textos (servicios, empresa, obras, acreditaciones, contacto y legales).
 * - Sube las fotos de obra a la biblioteca de medios con su texto alternativo y leyenda.
 * - Pone la portada, los menús y los enlaces permanentes.
 * - Borra la entrada «Hola, mundo», la página de ejemplo y el comentario de prueba.
 * - Cierra los comentarios y fija la zona horaria.
 *
 * Se puede pulsar más de una vez: no duplica páginas ni fotos.
 *
 * @package GR_Electricidad
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'admin_menu',
	function () {
		add_theme_page( 'Configurar web GR', 'Configurar web GR', 'manage_options', 'gr-configurar', 'gr_pantalla_importador' );
	}
);

/**
 * Aviso tras activar el tema.
 */
add_action(
	'admin_notices',
	function () {
		if ( ! current_user_can( 'manage_options' ) || get_option( 'gr_importado' ) ) {
			return;
		}
		$screen = get_current_screen();
		if ( $screen && 'appearance_page_gr-configurar' === $screen->id ) {
			return;
		}
		printf(
			'<div class="notice notice-info"><p><strong>GR Electricidad:</strong> la web todavía no tiene su contenido. <a class="button button-primary" href="%s">Configurar la web ahora</a></p></div>',
			esc_url( admin_url( 'themes.php?page=gr-configurar' ) )
		);
	}
);

/**
 * Pantalla del importador.
 */
function gr_pantalla_importador() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$log = array();
	if ( isset( $_POST['gr_importar'] ) ) {
		check_admin_referer( 'gr_importar' );
		$log = gr_importar();
	}
	?>
	<div class="wrap">
		<h1>Configurar web GR Electricidad</h1>
		<p>Este botón crea las páginas con sus textos, sube las fotos de obra, configura la portada y los menús, y elimina el contenido de ejemplo de WordPress. Puedes pulsarlo más de una vez: no duplica nada y <strong>no sobrescribe</strong> las páginas que ya existan.</p>
		<form method="post">
			<?php wp_nonce_field( 'gr_importar' ); ?>
			<p><button class="button button-primary button-hero" name="gr_importar" value="1">Configurar la web</button></p>
		</form>
		<?php if ( $log ) : ?>
			<h2>Resultado</h2>
			<ul style="list-style:disc;padding-left:20px">
				<?php foreach ( $log as $l ) : ?>
					<li><?php echo esc_html( $l ); ?></li>
				<?php endforeach; ?>
			</ul>
			<p><a class="button button-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank">Ver la web</a> <a class="button" href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=gr_datos' ) ); ?>">Revisar los datos de la empresa</a></p>
		<?php endif; ?>
		<hr>
		<h2>Después</h2>
		<ol style="list-style:decimal;padding-left:20px">
			<li>Revisa los datos de la empresa en <a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=gr_datos' ) ); ?>">Apariencia → Personalizar → Datos de GR Electricidad</a> y completa los datos del Registro Mercantil.</li>
			<li>Si quieres medir visitas, pon allí el ID de Google Analytics: el aviso de cookies aparecerá solo.</li>
			<li>En Ajustes → Lectura, comprueba que <em>no</em> está marcado «Disuadir a los motores de búsqueda».</li>
		</ol>
	</div>
	<?php
}

/**
 * Hace todo el trabajo.
 *
 * @return string[] Registro de lo hecho.
 */
function gr_importar() {
	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/plugin.php';

	$log       = array();
	$contenido = require GR_DIR . '/setup/contenido.php';

	/* 1. Limpieza del contenido de ejemplo. */
	$hola = get_posts(
		array(
			'post_type'   => 'post',
			'post_status' => 'any',
			'numberposts' => -1,
			'name__in'    => array( 'hello-world', 'hola-mundo' ),
		)
	);
	foreach ( $hola as $p ) {
		wp_delete_post( $p->ID, true );
		$log[] = 'Borrada la entrada de ejemplo «' . $p->post_title . '».';
	}
	// Página de ejemplo (siempre) y borrador de privacidad que crea WordPress (solo si sigue en borrador).
	$ejemplo = array(
		'sample-page'         => false,
		'pagina-ejemplo'      => false,
		'privacy-policy'      => true,
		'politica-privacidad' => true,
	);
	foreach ( $ejemplo as $slug => $solo_borrador ) {
		$p = get_page_by_path( $slug, OBJECT, 'page' );
		if ( ! $p || ( $solo_borrador && 'draft' !== $p->post_status ) ) {
			continue;
		}
		wp_delete_post( $p->ID, true );
		$log[] = 'Borrada la página de ejemplo «' . $p->post_title . '».';
	}
	foreach ( get_comments( array( 'number' => 50 ) ) as $c ) {
		if ( 'https://wordpress.org/' === $c->comment_author_url || false !== stripos( $c->comment_author, 'WordPress' ) ) {
			wp_delete_comment( $c->comment_ID, true );
			$log[] = 'Borrado el comentario de prueba.';
		}
	}

	/* 2. Ajustes generales. */
	update_option( 'blogname', 'GR Electricidad' );
	update_option( 'blogdescription', 'Instalaciones eléctricas para empresa e industria en Toledo' );
	update_option( 'default_comment_status', 'closed' );
	update_option( 'default_ping_status', 'closed' );
	update_option( 'default_pingback_flag', 0 );
	update_option( 'users_can_register', 0 );
	update_option( 'timezone_string', 'Europe/Madrid' );
	update_option( 'date_format', 'j \d\e F \d\e Y' );
	update_option( 'time_format', 'H:i' );
	update_option( 'start_of_week', 1 );
	update_option( 'uploads_use_yearmonth_folders', 1 );
	$log[] = 'Comentarios cerrados, registro de usuarios desactivado y zona horaria Europe/Madrid.';

	/* 3. Páginas. */
	$ids = array();
	foreach ( $contenido['paginas'] as $pag ) {
		$padre_id = ! empty( $pag['padre'] ) ? ( $ids[ $pag['padre'] ] ?? 0 ) : 0;
		$ruta     = ( $padre_id ? get_post_field( 'post_name', $padre_id ) . '/' : '' ) . $pag['slug'];
		$existe   = get_page_by_path( $ruta );
		if ( $existe ) {
			$ids[ $pag['slug'] ] = $existe->ID;
			$log[]               = 'Ya existía la página «' . $existe->post_title . '» (no se ha tocado).';
			continue;
		}
		$id = wp_insert_post(
			array(
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'post_title'     => $pag['titulo'],
				'post_name'      => $pag['slug'],
				'post_content'   => trim( $pag['contenido'] ?? '' ),
				'post_excerpt'   => $pag['resumen'] ?? '',
				'post_parent'    => $padre_id,
				'menu_order'     => $pag['orden'] ?? 0,
				'comment_status' => 'closed',
				'ping_status'    => 'closed',
			),
			true
		);
		if ( is_wp_error( $id ) ) {
			$log[] = 'Error al crear «' . $pag['titulo'] . '»: ' . $id->get_error_message();
			continue;
		}
		if ( ! empty( $pag['descripcion'] ) ) {
			update_post_meta( $id, '_gr_descripcion', $pag['descripcion'] );
		}
		$ids[ $pag['slug'] ] = $id;
		$log[]               = 'Creada la página «' . $pag['titulo'] . '».';
	}

	/* 4. Fotos. */
	$fotos_ids = array();
	$obras_id  = $ids['obras'] ?? 0;
	foreach ( $contenido['fotos'] as $orden => $f ) {
		$existente = get_posts(
			array(
				'post_type'   => 'attachment',
				'post_status' => 'inherit',
				'numberposts' => 1,
				'meta_key'    => '_gr_foto', // phpcs:ignore WordPress.DB.SlowDBQuery
				'meta_value'  => $f['archivo'], // phpcs:ignore WordPress.DB.SlowDBQuery
			)
		);
		if ( $existente ) {
			$fotos_ids[ $f['archivo'] ] = $existente[0]->ID;
			continue;
		}
		$origen = GR_DIR . '/setup/fotos/' . $f['archivo'];
		if ( ! file_exists( $origen ) ) {
			$log[] = 'No se encuentra la foto ' . $f['archivo'];
			continue;
		}
		$tmp = wp_tempnam( $f['archivo'] );
		copy( $origen, $tmp );
		$att = media_handle_sideload(
			array(
				'name'     => $f['archivo'],
				'tmp_name' => $tmp,
			),
			( isset( $f['galeria'] ) && false === $f['galeria'] ) ? 0 : $obras_id,
			$f['titulo'],
			array(
				'post_excerpt' => $f['leyenda'],
				'menu_order'   => $orden,
			)
		);
		if ( is_wp_error( $att ) ) {
			wp_delete_file( $tmp );
			$log[] = 'Error al subir ' . $f['archivo'] . ': ' . $att->get_error_message();
			continue;
		}
		update_post_meta( $att, '_wp_attachment_image_alt', $f['alt'] );
		update_post_meta( $att, '_gr_foto', $f['archivo'] );
		$fotos_ids[ $f['archivo'] ] = $att;
	}
	$log[] = count( $fotos_ids ) . ' fotos de obra en la biblioteca de medios, con texto alternativo y leyenda.';

	/* 5. Imágenes destacadas. */
	foreach ( $contenido['paginas'] as $pag ) {
		if ( empty( $pag['foto'] ) || empty( $ids[ $pag['slug'] ] ) || empty( $fotos_ids[ $pag['foto'] ] ) ) {
			continue;
		}
		if ( ! has_post_thumbnail( $ids[ $pag['slug'] ] ) ) {
			set_post_thumbnail( $ids[ $pag['slug'] ], $fotos_ids[ $pag['foto'] ] );
		}
	}

	/* 6. Portada y página de privacidad. */
	if ( ! empty( $ids['inicio'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $ids['inicio'] );
		$log[] = 'Portada configurada.';
	}
	if ( ! empty( $ids['politica-de-privacidad'] ) ) {
		update_option( 'wp_page_for_privacy_policy', $ids['politica-de-privacidad'] );
	}

	/* 7. Menús. */
	$locs = get_theme_mod( 'nav_menu_locations', array() );
	if ( empty( $locs['principal'] ) ) {
		$m = wp_create_nav_menu( 'Principal' );
		if ( ! is_wp_error( $m ) ) {
			$serv_item = 0;
			foreach ( array( 'empresa', 'servicios', 'obras', 'acreditaciones', 'contacto' ) as $i => $slug ) {
				if ( empty( $ids[ $slug ] ) ) {
					continue;
				}
				$item = wp_update_nav_menu_item(
					$m,
					0,
					array(
						'menu-item-object-id' => $ids[ $slug ],
						'menu-item-object'    => 'page',
						'menu-item-type'      => 'post_type',
						'menu-item-status'    => 'publish',
						'menu-item-position'  => $i + 1,
					)
				);
				if ( 'servicios' === $slug ) {
					$serv_item = $item;
				}
			}
			foreach ( $contenido['paginas'] as $pag ) {
				if ( 'servicios' === ( $pag['padre'] ?? '' ) && ! empty( $ids[ $pag['slug'] ] ) && $serv_item ) {
					wp_update_nav_menu_item(
						$m,
						0,
						array(
							'menu-item-object-id' => $ids[ $pag['slug'] ],
							'menu-item-object'    => 'page',
							'menu-item-type'      => 'post_type',
							'menu-item-status'    => 'publish',
							'menu-item-parent-id' => $serv_item,
						)
					);
				}
			}
			$locs['principal'] = $m;
			$log[]             = 'Creado el menú principal.';
		}
	}
	if ( empty( $locs['legal'] ) ) {
		$m = wp_create_nav_menu( 'Legal' );
		if ( ! is_wp_error( $m ) ) {
			foreach ( array( 'aviso-legal', 'politica-de-privacidad', 'politica-de-cookies' ) as $i => $slug ) {
				if ( ! empty( $ids[ $slug ] ) ) {
					wp_update_nav_menu_item(
						$m,
						0,
						array(
							'menu-item-object-id' => $ids[ $slug ],
							'menu-item-object'    => 'page',
							'menu-item-type'      => 'post_type',
							'menu-item-status'    => 'publish',
							'menu-item-position'  => $i + 1,
						)
					);
				}
			}
			$locs['legal'] = $m;
			$log[]         = 'Creado el menú legal del pie: Aviso legal, Política de privacidad y Política de cookies.';
		}
	}
	set_theme_mod( 'nav_menu_locations', $locs );

	/* 8. Enlaces permanentes bonitos. */
	if ( '/%postname%/' !== get_option( 'permalink_structure' ) ) {
		update_option( 'permalink_structure', '/%postname%/' );
		$log[] = 'Enlaces permanentes: /nombre-de-la-pagina/.';
	}
	flush_rewrite_rules();

	/* 9. Plugins de ejemplo. */
	foreach ( array( 'hello.php', 'hello-dolly/hello.php', 'akismet/akismet.php', '1and1-wordpress-assistant/1and1-wordpress-assistant.php' ) as $plugin ) {
		if ( file_exists( WP_PLUGIN_DIR . '/' . $plugin ) ) {
			deactivate_plugins( $plugin, true );
			$borrado = delete_plugins( array( $plugin ) );
			$log[]   = ( true === $borrado ? 'Eliminado' : 'Desactivado (bórralo a mano en Plugins)' ) . ' el plugin ' . dirname( $plugin ) . '.';
		}
	}

	/* 10. Usuarios con nombres fáciles. */
	foreach ( array( 'admin', 'admin1' ) as $login ) {
		if ( get_user_by( 'login', $login ) ) {
			$log[] = 'ATENCIÓN: existe el usuario «' . $login . '». Crea otro administrador con un nombre distinto y borra este.';
		}
	}

	update_option( 'gr_importado', time() );
	$log[] = 'Listo.';
	return $log;
}
