<?php
/**
 * Portada.
 *
 * @package GR_Electricidad
 */

defined( 'ABSPATH' ) || exit;
get_header();

$inicio    = (int) get_option( 'page_on_front' );
$servicios = gr_servicios();
$fotos     = gr_fotos_obras( 12 );
$linea     = 'M0 100 H360 L400 40 H760 L800 120 H1080 L1120 70 H1440';
?>

<section class="hero" aria-labelledby="titulo-portada">
	<div class="wrap">
		<div class="hero-meta mono js-up">
			<span><span class="live" aria-hidden="true"></span><?php echo esc_html( gr_dato( 'razon_social' ) ); ?></span>
		</div>
		<h1 class="disp js-hero-title" id="titulo-portada">
			<span class="line"><span>Instalamos</span></span>
			<span class="line"><span>la <span class="outline">energía</span></span></span>
			<span class="line"><span>que mueve</span></span>
			<span class="line"><span>tu empresa<span style="color:var(--red)">.</span></span></span>
		</h1>
	</div>

	<svg class="current-line" viewBox="0 0 1440 160" preserveAspectRatio="none" aria-hidden="true" focusable="false">
		<path d="<?php echo esc_attr( $linea ); ?>" fill="none" stroke="rgba(20,20,20,.14)" stroke-width="1.5" pathLength="1000"/>
		<path class="pulse" d="<?php echo esc_attr( $linea ); ?>" fill="none" stroke="#E3262E" stroke-width="3" stroke-linecap="round" pathLength="1000"/>
		<path class="pulse2" d="<?php echo esc_attr( $linea ); ?>" fill="none" stroke="#141414" stroke-width="2" stroke-linecap="round" pathLength="1000"/>
	</svg>

	<div class="wrap hero-bottom">
		<div class="hero-media js-reveal">
			<?php
			if ( $inicio && has_post_thumbnail( $inicio ) ) {
				echo get_the_post_thumbnail( $inicio, 'gr-ancha', array( 'loading' => 'eager', 'fetchpriority' => 'high', 'class' => 'js-kenburns' ) );
			} else {
				printf( '<img class="js-kenburns" src="%s" alt="Instalación de paneles solares fotovoltaicos de GR Electricidad" width="1800" height="1350" fetchpriority="high">', esc_url( GR_URI . '/assets/img/portada.jpg' ) );
			}
			?>
			<span class="tag mono">Autoconsumo fotovoltaico</span>
		</div>
		<div class="hero-copy js-up">
			<p>Instalaciones eléctricas completas, mantenimiento en alta, media y baja tensión, energía solar fotovoltaica y videovigilancia para industria, empresas e instituciones.</p>
			<div class="actions">
				<a class="btn btn-red" href="#presupuesto">Pide presupuesto <?php echo gr_flecha(); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
				<a class="btn btn-ghost" href="#servicios">Ver servicios</a>
			</div>
			<div class="clock mono"><span><?php echo esc_html( gr_dato( 'localidad' ) ); ?>, ahora</span><strong class="js-clock">—</strong></div>
		</div>
	</div>
</section>

<div class="band" aria-hidden="true">
	<div class="track disp">
		<span>Alta tensión ✦ Media tensión ✦ Baja tensión ✦ Fotovoltaica ✦ CCTV ✦ Climatización ✦ Asesoramiento técnico ✦</span>
		<span>Alta tensión ✦ Media tensión ✦ Baja tensión ✦ Fotovoltaica ✦ CCTV ✦ Climatización ✦ Asesoramiento técnico ✦</span>
	</div>
</div>

<section class="intro" id="empresa" aria-label="Empresa">
	<div class="wrap">
		<p class="label">(Empresa)</p>
		<p class="big disp js-highlight">
			<span>Desde <?php echo esc_html( gr_dato( 'anio_fundacion' ) ); ?> diseñamos, montamos</span>
			<span>y mantenemos instalaciones eléctricas</span>
			<span>para industrias, empresas, comercios</span>
			<span>e instituciones.</span>
			<span class="accent">En alta, media y baja tensión.</span>
			<span>Con todas las autorizaciones,</span>
			<span>plazos que se cumplen</span>
			<span>y un equipo propio.</span>
		</p>
	</div>
	<div class="wrap" style="margin-top:48px">
		<a class="btn btn-ghost js-up" href="<?php echo esc_url( gr_url( 'empresa' ) ); ?>">Conoce la empresa <?php echo gr_flecha(); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
	</div>
</section>

<?php if ( $servicios ) : ?>
<section class="services" id="servicios" aria-labelledby="titulo-servicios">
	<div class="wrap">
		<div class="section-head">
			<h2 class="disp js-up" id="titulo-servicios">Servicios</h2>
			<p class="mono js-up">(<?php echo esc_html( str_pad( (string) count( $servicios ), 2, '0', STR_PAD_LEFT ) ); ?>)</p>
		</div>
		<?php foreach ( $servicios as $i => $p ) : ?>
			<a class="svc js-up" href="<?php echo esc_url( get_permalink( $p ) ); ?>">
				<span class="svc-n mono"><?php echo esc_html( str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
				<span class="svc-t disp"><?php echo esc_html( get_the_title( $p ) ); ?></span>
				<span class="svc-d"><?php echo esc_html( get_the_excerpt( $p ) ); ?></span>
				<?php
				if ( has_post_thumbnail( $p ) ) {
					echo get_the_post_thumbnail( $p, 'gr-tarjeta', array( 'class' => 'svc-peek', 'data-decorativa' => '1', 'loading' => 'lazy' ) );
				}
				?>
				<span class="svc-arr"><?php echo gr_flecha(); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
			</a>
		<?php endforeach; ?>
	</div>
</section>
<?php endif; ?>

<section class="facts" aria-label="Datos de la empresa">
	<div class="wrap">
		<div class="fact js-up">
			<div class="disp"><span class="js-count" data-from="1950" data-to="<?php echo esc_attr( gr_dato( 'anio_fundacion' ) ); ?>"><?php echo esc_html( gr_dato( 'anio_fundacion' ) ); ?></span></div>
			<div class="bar js-bar"></div>
			<p>Año de fundación</p>
		</div>
		<div class="fact js-up">
			<div class="disp">AT·BT</div>
			<div class="bar js-bar"></div>
			<p>Autorizada en alta tensión (AT1 y AT2) y en baja tensión, categoría especialista</p>
		</div>
		<div class="fact js-up">
			<div class="disp"><span class="js-count" data-from="0" data-to="3">3</span></div>
			<div class="bar js-bar"></div>
			<p>Centros en <?php echo esc_html( gr_dato( 'localidad' ) ); ?>: sede, almacén y talleres</p>
		</div>
		<div class="fact js-up">
			<div class="disp">REA</div>
			<div class="bar js-bar"></div>
			<p>Inscritos en el Registro de Empresas Acreditadas y en el ROLECE</p>
		</div>
	</div>
</section>

<?php if ( $fotos ) : ?>
<section class="gallery" id="obras" aria-labelledby="titulo-obras">
	<div class="wrap section-head">
		<h2 class="disp js-up" id="titulo-obras">Obra <span class="outline">real</span></h2>
		<p class="js-up">Fotos de nuestros trabajos, no de un banco de imágenes. <a href="<?php echo esc_url( gr_url( 'obras' ) ); ?>">Ver todas las obras</a></p>
	</div>
	<div class="track-slow">
		<?php
		// Se repite la serie para que la cinta sea continua; la copia se oculta a lectores de pantalla.
		foreach ( array( false, true ) as $copia ) :
			foreach ( $fotos as $f ) :
				?>
				<figure<?php echo $copia ? ' aria-hidden="true"' : ''; ?>>
					<div class="ph"><?php echo wp_get_attachment_image( $f->ID, 'gr-tarjeta', false, $copia ? array( 'class' => 'js-parallax', 'loading' => 'lazy', 'data-decorativa' => '1' ) : array( 'class' => 'js-parallax', 'loading' => 'lazy' ) ); ?></div>
					<figcaption class="mono"><?php echo esc_html( wp_get_attachment_caption( $f->ID ) ); ?></figcaption>
				</figure>
				<?php
			endforeach;
		endforeach;
		?>
	</div>
</section>
<?php endif; ?>

<section class="process" aria-labelledby="titulo-proceso">
	<div class="wrap">
		<h2 class="disp js-up" id="titulo-proceso">Cómo trabajamos</h2>
		<div class="steps">
			<div class="step js-up"><div class="bar js-bar"></div><p class="mono">Paso 01</p><h3>Estudio previo</h3><p>Visitamos la instalación y analizamos tus necesidades y consumos.</p></div>
			<div class="step js-up"><div class="bar js-bar"></div><p class="mono">Paso 02</p><h3>Proyecto y presupuesto</h3><p>Te entregamos una propuesta cerrada, con plazos y precio claros.</p></div>
			<div class="step js-up"><div class="bar js-bar"></div><p class="mono">Paso 03</p><h3>Ejecución</h3><p>Equipo propio, material de primera y cumplimiento de la normativa vigente.</p></div>
			<div class="step js-up"><div class="bar js-bar"></div><p class="mono">Paso 04</p><h3>Legalización y mantenimiento</h3><p>Tramitamos la documentación y cuidamos la instalación después.</p></div>
		</div>
	</div>
</section>

<section class="accr" id="acreditaciones" aria-labelledby="titulo-acred">
	<div class="wrap">
		<div class="accr-side">
			<h2 class="disp js-up" id="titulo-acred">Acredi&shy;taciones</h2>
			<p class="js-up">Trabajar con una empresa autorizada es una garantía de seguridad y de que tu instalación será legal.</p>
			<svg class="badge" viewBox="0 0 200 200" aria-hidden="true" focusable="false">
				<defs><path id="circ" d="M100 100 m-78 0 a78 78 0 1 1 156 0 a78 78 0 1 1 -156 0"/></defs>
				<text style="font-family:'JetBrains Mono',monospace;font-size:15px;letter-spacing:3px;fill:#141414"><textPath href="#circ">EMPRESA AUTORIZADA · DESDE <?php echo esc_html( gr_dato( 'anio_fundacion' ) ); ?> · </textPath></text>
				<path d="M104 62 L84 104 H100 L94 138 L118 92 H102 Z" fill="#E3262E"/>
			</svg>
		</div>
		<ul class="accr-list">
			<?php foreach ( gr_acreditaciones() as $i => $a ) : ?>
				<li class="js-slide"><span class="mono"><?php echo esc_html( str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span><span><?php echo esc_html( $a ); ?></span></li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>

<?php
gr_bloque_clientes();
gr_bloque_contacto( true );
get_footer();
