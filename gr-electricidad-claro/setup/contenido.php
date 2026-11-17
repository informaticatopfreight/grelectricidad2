<?php
/**
 * Contenido inicial de la web (lo usa Apariencia → Configurar web GR).
 * Los textos se crean como bloques normales de WordPress: después se pueden
 * editar desde el editor de cada página.
 *
 * @package GR_Electricidad
 */

defined( 'ABSPATH' ) || exit;

/* ---------- Ayudantes para escribir bloques ---------- */
$p  = fn( $t ) => "<!-- wp:paragraph -->\n<p>$t</p>\n<!-- /wp:paragraph -->\n\n";
$h2 = fn( $t ) => "<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">$t</h2>\n<!-- /wp:heading -->\n\n";
$h3 = fn( $t ) => "<!-- wp:heading {\"level\":3} -->\n<h3 class=\"wp-block-heading\">$t</h3>\n<!-- /wp:heading -->\n\n";
$ul = function ( array $items ) {
	$li = '';
	foreach ( $items as $i ) {
		$li .= "<!-- wp:list-item -->\n<li>$i</li>\n<!-- /wp:list-item -->\n\n";
	}
	return "<!-- wp:list -->\n<ul class=\"wp-block-list\">$li</ul>\n<!-- /wp:list -->\n\n";
};
$tabla = function ( array $cab, array $filas ) {
	$th = '<tr><th>' . implode( '</th><th>', $cab ) . '</th></tr>';
	$tb = '';
	foreach ( $filas as $f ) {
		$tb .= '<tr><td>' . implode( '</td><td>', $f ) . '</td></tr>';
	}
	return "<!-- wp:table -->\n<figure class=\"wp-block-table\"><table><thead>$th</thead><tbody>$tb</tbody></table></figure>\n<!-- /wp:table -->\n\n";
};
$sc = fn( $t ) => "<!-- wp:shortcode -->\n$t\n<!-- /wp:shortcode -->\n\n";

$email_legal = '[gr_dato clave="email_legal" enlace="1"]';

return array(

	/* ======================================================================
	 * PÁGINAS
	 * ==================================================================== */
	'paginas' => array(

		array(
			'slug'        => 'inicio',
			'titulo'      => 'Inicio',
			'contenido'   => '',
			'descripcion' => 'Empresa de instalaciones eléctricas en Pantoja (Toledo): alta y baja tensión, mantenimiento, energía solar fotovoltaica y CCTV para industria y empresas.',
			'foto'        => 'instalacion-fotovoltaica.jpg',
		),

		/* ---------- Servicios ---------- */
		array(
			'slug'        => 'servicios',
			'titulo'      => 'Servicios',
			'orden'       => 1,
			'resumen'     => 'Todo lo que necesita la instalación eléctrica de tu empresa, con un solo interlocutor.',
			'descripcion' => 'Servicios de GR Electricidad en Toledo: instalaciones eléctricas, mantenimiento AT, MT y BT, fotovoltaica, CCTV, climatización y asesoramiento técnico.',
			'contenido'   => $p( 'Desde 2007 trabajamos para industrias, empresas, comercios, organismos e instituciones que necesitan servicios eléctricos a medida. Diseñamos, montamos, legalizamos y mantenemos sus instalaciones, en alta, media y baja tensión, con equipo propio y todas las autorizaciones.' ),
			'foto'        => 'trabajo-en-altura-linea-aerea.jpg',
		),
		array(
			'slug'        => 'instalaciones-electricas',
			'padre'       => 'servicios',
			'orden'       => 1,
			'titulo'      => 'Instalación y montaje eléctrico',
			'resumen'     => 'Obra nueva y reformas en industrias, naves y locales: centros de transformación, líneas, cuadros, iluminación, redes y automatismos.',
			'descripcion' => 'Instalación y montaje de instalaciones eléctricas de alta y baja tensión en Toledo: centros de transformación, cuadros, líneas e iluminación para industria.',
			'foto'        => 'cuadro-general-baja-tension-embarrado.jpg',
			'contenido'   =>
				$h2( 'Instalaciones eléctricas para la empresa y la industria' )
				. $p( 'Nuestro compromiso es ofrecer un servicio de calidad, especializado en industrias y empresas. Realizamos proyectos de <strong>obra nueva y reformas</strong> en industrias, naves y locales comerciales, con un equipo propio de profesionales cualificados que se ocupa de todas las fases: proyecto, tramitación, montaje y puesta en servicio.' )
				. $h3( 'Alta tensión' )
				. $ul( array( 'Centros de transformación.', 'Líneas subterráneas y aéreas.' ) )
				. $h3( 'Baja tensión' )
				. $ul( array( 'Cuadros eléctricos: diseño, fabricación en nuestro taller y montaje.', 'Líneas eléctricas.', 'Iluminación interior y exterior.' ) )
				. $h3( 'Redes informáticas y telecomunicaciones' )
				. $ul( array( 'Cableado estructurado para redes informáticas.' ) )
				. $h3( 'Automatismos industriales' )
				. $ul( array( 'Automatismos eléctricos y electrónicos.', 'Automatización de procesos industriales.' ) )
				. $h3( 'Y además' )
				. $p( 'Instalamos <a href="/servicios/climatizacion/">climatización industrial</a> y <a href="/servicios/cctv-videovigilancia/">sistemas de videovigilancia</a>, para que tengas un único interlocutor en toda la obra.' ),
		),
		array(
			'slug'        => 'mantenimiento-electrico',
			'padre'       => 'servicios',
			'orden'       => 2,
			'titulo'      => 'Mantenimiento eléctrico',
			'resumen'     => 'Mantenimiento predictivo, preventivo y correctivo en alta, media y baja tensión: termografías, ensayos, apoyo a OCA y corrección de defectos.',
			'descripcion' => 'Mantenimiento eléctrico de alta, media y baja tensión en Toledo: centros de transformación, termografías, ensayos de protecciones y apoyo en inspecciones OCA.',
			'foto'        => 'ensayos-centro-transformacion.jpg',
			'contenido'   =>
				$p( 'Realizamos el mantenimiento eléctrico preventivo y correctivo de instalaciones de industrias, empresas, instituciones y recintos públicos, para adecuarlas a la legislación vigente (Reglamento Electrotécnico para Baja Tensión y Reglamento sobre Condiciones Técnicas y Garantías de Seguridad en Instalaciones Eléctricas de Alta Tensión), evitar averías y garantizar la seguridad de la instalación.' )
				. $p( 'El mantenimiento eléctrico es una de las especialidades de GR Electricidad, S.L.' )
				. $h2( 'Mantenimiento predictivo' )
				. $p( 'Se realiza con la instalación en pleno funcionamiento, para comprobar que todos los elementos trabajan correctamente y detectar a tiempo cualquier anomalía. Empleamos técnicas como:' )
				. $ul( array( 'Termografía y termovisión en centros de transformación y cuadros (contactos, aprietes, puntos calientes…).', 'Medida de magnitudes eléctricas (tensiones, intensidades…) con analizador de redes.', 'Comprobación de las instalaciones según la normativa vigente.', 'Apoyo en las inspecciones de los Organismos de Control Autorizados (OCA).' ) )
				. $h2( 'Mantenimiento preventivo' )
				. $p( 'Se realiza sin tensión, con las pruebas necesarias para comprobar el estado de los equipos. Entre otras:' )
				. $ul( array( 'Ensayos de protecciones (relés directos e indirectos…).', 'Medidas de aislamiento (transformadores, interruptores, líneas…).' ) )
				. $h2( 'Mantenimiento correctivo' )
				. $p( 'Reparamos las anomalías de la instalación:' )
				. $ul( array( 'Reparación de aparamenta eléctrica, transformadores, etc.', 'Adecuación de las instalaciones a la normativa vigente.', 'Amplio stock de material de repuesto para averías.' ) )
				. $h2( 'Otros mantenimientos' )
				. $p( 'También mantenemos, de forma puntual o periódica según lo que necesite cada cliente:' )
				. $ul( array( 'Instalaciones de media y alta tensión, en líneas aéreas y subterráneas.', 'Centros de transformación y subestaciones eléctricas.', 'Cuadros eléctricos de protección y de automatización de procesos.', 'Instalaciones de aire acondicionado, fancoils y enfriadoras.' ) ),
		),
		array(
			'slug'        => 'energia-solar-fotovoltaica',
			'padre'       => 'servicios',
			'orden'       => 3,
			'titulo'      => 'Energía solar fotovoltaica',
			'resumen'     => 'Autoconsumo para empresas, con o sin excedentes: estudio previo, proyecto, instalación y puesta en marcha. Instaladores certificados SolarEdge.',
			'descripcion' => 'Instalaciones de autoconsumo fotovoltaico para empresas y naves en Toledo: estudio, proyecto, instalación y legalización. Instaladores certificados SolarEdge.',
			'foto'        => 'montaje-paneles-solares-cubierta.jpg',
			'contenido'   =>
				$h2( 'Autoconsumo para empresas' )
				. $p( 'El autoconsumo es la producción de electricidad con paneles solares fotovoltaicos para consumo propio. Permite a las empresas reducir su factura eléctrica y, al mismo tiempo, contribuir a la transición energética y a la lucha contra el cambio climático.' )
				. $p( 'Lo instalamos en empresas, naves industriales y otros negocios que quieran sacar rendimiento a sus tejados y cubiertas, en cualquiera de sus modalidades:' )
				. $ul( array( '<strong>Sin excedentes:</strong> toda la energía producida se consume en la propia instalación.', '<strong>Con excedentes:</strong> conectado a la red, de modo que la electricidad sobrante se vende o se compensa, y se toma de la red cuando la producción propia no es suficiente.', '<strong>Aislado:</strong> sin conexión a la red eléctrica, con baterías.' ) )
				. $h2( 'Cómo lo hacemos' )
				. $ul( array( '<strong>Estudio previo:</strong> analizamos las necesidades energéticas y los consumos de la empresa.', '<strong>Proyecto:</strong> elaboramos, cuando es necesario, el proyecto de autoconsumo a medida y nos ocupamos de la tramitación.', '<strong>Instalación:</strong> montamos los paneles, inversores y, si procede, baterías, y ponemos en marcha el sistema.', '<strong>Ahorro:</strong> probamos el sistema y la empresa empieza a ahorrar en la factura desde el primer día.' ) )
				. $h2( 'Ventajas para tu empresa' )
				. $ul( array( 'Menos gasto en la factura de la luz desde el primer momento.', 'La instalación es cada vez más asequible y existen ayudas en muchas comunidades autónomas.', 'Aumenta el valor de tu inmueble.', 'Consumes energía limpia, sin emisiones de CO<sub>2</sub>.' ) )
				. $p( 'Somos <strong>instaladores certificados SolarEdge</strong>.' ),
		),
		array(
			'slug'        => 'cctv-videovigilancia',
			'padre'       => 'servicios',
			'orden'       => 4,
			'titulo'      => 'CCTV y videovigilancia',
			'resumen'     => 'Cámaras IP y analógicas, día/noche, domo y antivandálicas. Grabadores DVR, HVR y NVR. Vigilancia perimetral e interior.',
			'descripcion' => 'Instalación de cámaras de videovigilancia y CCTV para naves, empresas y comercios en Toledo: cámaras IP, grabadores NVR y vigilancia perimetral.',
			'foto'        => 'camaras-videovigilancia.jpg',
			'contenido'   =>
				$h2( 'Soluciones profesionales de videovigilancia' )
				. $p( 'Instalamos todo tipo de cámaras y grabadores de videovigilancia, con cámaras día/noche y equipos profesionales con la mejor relación calidad-precio. Tenemos amplia experiencia en centros logísticos, empresas, almacenes, comercios y oficinas donde es necesario ver todos los detalles.' )
				. $h3( 'Instalaciones a tu medida' )
				. $ul( array( 'Vigilancia perimetral de edificios.', 'Vigilancia del interior de edificios.', 'Instalación de cámaras analógicas o IP, incluido el cableado, la electrónica de red y la configuración del sistema.' ) )
				. $h3( 'Cámaras' )
				. $ul( array( 'Cámaras día y noche en formatos estándar.', 'Cámaras domo, minidomo y domo motorizadas.', 'Cámaras IP para interior y exterior.', 'Cámaras analógicas, wifi, antivandálicas y muchas más opciones.' ) )
				. $h3( 'Grabadores' )
				. $ul( array( 'Grabadores DVR estándar.', 'Grabadores híbridos HVR (cámaras analógicas e IP).', 'Grabadores 100 % IP (NVR).' ) )
				. $p( 'Recuerda que las cámaras que graban personas deben cumplir la normativa de protección de datos (por ejemplo, el cartel informativo de zona videovigilada). Te asesoramos al respecto.' ),
		),
		array(
			'slug'        => 'climatizacion',
			'padre'       => 'servicios',
			'orden'       => 5,
			'titulo'      => 'Climatización',
			'resumen'     => 'Instalación y mantenimiento de aire acondicionado, bomba de calor, enfriadoras y fancoils en naves y edificios. Empresa habilitada en gases fluorados.',
			'descripcion' => 'Climatización industrial en Toledo: aire acondicionado, bomba de calor, enfriadoras y fancoils. Empresa habilitada en gases fluorados.',
			'foto'        => 'enfriadora-climatizacion-industrial.jpg',
			'contenido'   =>
				$h2( 'Climatización eficiente para naves y edificios' )
				. $p( 'Instalamos, mantenemos y reparamos equipos de climatización de alta eficiencia energética para empresas e industria. Como empresa habilitada para trabajar con <strong>gases fluorados</strong>, podemos intervenir en equipos con cualquier carga de refrigerante.' )
				. $ul( array( 'Aire acondicionado en naves y edificios.', 'Bomba de calor.', 'Enfriadoras y fancoils.', 'Equipos de refrigeración.', 'Mantenimiento preventivo y reparación de averías.' ) )
				. $p( 'Al hacer también la instalación eléctrica, coordinamos toda la obra: alimentación, protecciones y puesta en marcha.' ),
		),
		array(
			'slug'        => 'asesoramiento-tecnico',
			'padre'       => 'servicios',
			'orden'       => 6,
			'titulo'      => 'Asesoramiento técnico',
			'resumen'     => 'Te ayudamos a decidir antes de invertir: adecuación a normativa, inspecciones, ahorro energético y legalización de instalaciones.',
			'descripcion' => 'Asesoramiento técnico eléctrico para empresas en Toledo: adecuación a normativa, inspecciones OCA, análisis de redes, ahorro energético y legalización.',
			'foto'        => 'analizador-de-redes.jpg',
			'contenido'   =>
				$h2( 'Antes de invertir, pregúntanos' )
				. $p( 'Te ayudamos a tomar buenas decisiones sobre tu instalación eléctrica, con la experiencia de una empresa que la ejecuta y la mantiene cada día.' )
				. $ul( array( '<strong>Adecuación a la normativa:</strong> revisamos tu instalación y te decimos qué hay que corregir y cómo.', '<strong>Inspecciones:</strong> te preparamos para las inspecciones periódicas de los Organismos de Control Autorizados (OCA) y corregimos los defectos que señalen.', '<strong>Análisis de redes:</strong> medimos consumos y calidad de suministro para ajustar la potencia contratada y detectar problemas.', '<strong>Ahorro energético:</strong> estudiamos si te compensa el autoconsumo fotovoltaico o renovar iluminación y climatización.', '<strong>Legalización:</strong> tramitamos la documentación de instalaciones nuevas y reformadas.' ) ),
		),

		/* ---------- Empresa ---------- */
		array(
			'slug'        => 'empresa',
			'titulo'      => 'Empresa',
			'orden'       => 2,
			'resumen'     => 'Desde 2007 trabajamos en el sector eléctrico, con todas las autorizaciones para trabajos en baja, media y alta tensión y en telecomunicaciones.',
			'descripcion' => 'GR Electricidad, S.L., empresa instaladora autorizada desde 2007 en Pantoja (Toledo). Trabajos de alta, media y baja tensión en toda España.',
			'foto'        => 'flota-furgonetas-gr-electricidad.jpg',
			'contenido'   =>
				$p( 'Fundada en 2007, GR Electricidad, S.L. ha especializado sus servicios en el sector industrial: empresas, comercios, organismos e instituciones que necesitan servicios eléctricos a medida. Tenemos nuestra sede y nuestros talleres en Pantoja (Toledo).' )
				. $h2( 'Estamos autorizados' )
				. $p( 'Contamos con las autorizaciones legales y los carnés necesarios para todo tipo de trabajos en baja, media y alta tensión, así como en telecomunicaciones. <a href="/acreditaciones/">Ver todas las acreditaciones</a>.' )
				. $h2( 'Seriedad' )
				. $p( 'Los plazos de entrega de las instalaciones, el asesoramiento y los presupuestos se ajustan a lo pactado, a tus necesidades y a las características de cada trabajo.' )
				. $h2( 'Somos profesionales' )
				. $p( 'Nuestro personal está cualificado para garantizar la correcta instalación y puesta en servicio de cualquier tipo de instalación eléctrica, en todas sus fases: proyecto, tramitación, montaje y puesta en servicio.' )
				. $h2( 'Nos desplazamos' )
				. $p( 'Trabajamos en cualquier provincia de España y nos ocupamos de obtener con antelación todos los permisos que exija cada comunidad autónoma. Hemos realizado trabajos en comunidades como Andalucía, Asturias, Madrid o Castilla-La Mancha. Hacemos proyectos pequeños y grandes.' ),
		),

		/* ---------- Obras ---------- */
		array(
			'slug'        => 'obras',
			'titulo'      => 'Obras',
			'orden'       => 3,
			'resumen'     => 'Algunos de nuestros trabajos: fotos reales de obra, no de un banco de imágenes.',
			'descripcion' => 'Galería de obras de GR Electricidad: centros de transformación, cuadros eléctricos, líneas de media tensión, fotovoltaica y climatización.',
			'contenido'   => '',
			'foto'        => 'cuadro-distribucion-circuitos-rotulados.jpg',
		),

		/* ---------- Acreditaciones ---------- */
		array(
			'slug'        => 'acreditaciones',
			'titulo'      => 'Acreditaciones',
			'orden'       => 4,
			'resumen'     => 'Profesionalidad, calidad y el mejor servicio, avalados por todas las autorizaciones necesarias.',
			'descripcion' => 'Acreditaciones de GR Electricidad: empresa autorizada en baja tensión categoría especialista, alta tensión AT1 y AT2, LAT1, gases fluorados, REA y ROLECE.',
			'foto'        => 'centro-transformacion-prefabricado.jpg',
			'contenido'   =>
				$p( 'Para trabajar en actividades sujetas a los reglamentos de seguridad industrial es imprescindible disponer de una cualificación profesional y de autorizaciones de empresa. La mayoría se acreditan con un carné profesional; otras, cumpliendo una serie de requisitos.' )
				. $p( 'Para ti, es la seguridad de contar con una empresa profesional y fiable. GR Electricidad, S.L. cuenta con todas las autorizaciones necesarias para sus trabajos y con una plantilla con la formación adecuada para realizarlos.' ),
		),

		/* ---------- Contacto ---------- */
		array(
			'slug'        => 'contacto',
			'titulo'      => 'Contacto',
			'orden'       => 5,
			'resumen'     => 'Cuéntanos tu proyecto: te llamamos y te preparamos un presupuesto sin compromiso.',
			'descripcion' => 'Contacta con GR Electricidad en Pantoja (Toledo): teléfono 609 056 184, email y formulario para pedir presupuesto de instalaciones eléctricas.',
			'contenido'   => '',
		),

		/* ---------- Legales ---------- */
		array(
			'slug'        => 'aviso-legal',
			'titulo'      => 'Aviso legal',
			'orden'       => 10,
			'descripcion' => 'Aviso legal de la web de GR Electricidad, S.L.',
			'contenido'   =>
				$h2( '1. Quiénes somos' )
				. $p( 'En cumplimiento de la Ley 34/2002, de Servicios de la Sociedad de la Información y de Comercio Electrónico (LSSI), te informamos de que esta web pertenece a:' )
				. $ul(
					array(
						'<strong>Titular:</strong> [gr_dato clave="razon_social"]',
						'<strong>CIF:</strong> [gr_dato clave="cif"]',
						'<strong>Domicilio:</strong> [gr_dato clave="direccion"], [gr_dato clave="cp"] [gr_dato clave="localidad"] ([gr_dato clave="provincia"])',
						'<strong>Email:</strong> ' . $email_legal,
						'<strong>Teléfono:</strong> [gr_dato clave="tel_fijo" enlace="1"]',
						'<strong>Datos registrales:</strong> inscrita en el [gr_dato clave="registro"]',
					)
				)
				. $h2( '2. Para qué sirve esta web' )
				. $p( 'Esta web informa sobre los servicios de [gr_dato clave="razon_social"]: instalaciones eléctricas, mantenimiento en alta, media y baja tensión, energía solar fotovoltaica, videovigilancia, climatización y asesoramiento técnico. También permite pedir presupuestos y contactar con nosotros.' )
				. $h2( '3. Uso de la web' )
				. $p( 'Al navegar por esta web te comprometes a usarla de forma lícita, sin dañar la web ni a terceros. No está permitido introducir virus o código malicioso, intentar acceder a zonas restringidas ni usar los formularios para enviar publicidad no solicitada.' )
				. $h2( '4. Propiedad intelectual' )
				. $p( 'Los textos, fotografías, logotipos y diseño de esta web son propiedad de [gr_dato clave="razon_social"] o se usan con permiso de sus titulares. No puedes copiarlos, distribuirlos ni modificarlos sin nuestra autorización por escrito.' )
				. $h2( '5. Responsabilidad' )
				. $p( 'Revisamos con cuidado la información de esta web, pero puede contener errores o no estar actualizada. La información es orientativa: las condiciones de cada trabajo se fijan en su presupuesto. No respondemos de los daños causados por un uso indebido de la web ni por interrupciones técnicas ajenas a nosotros.' )
				. $h2( '6. Enlaces a otras webs' )
				. $p( 'Si esta web enlaza con páginas de terceros, lo hacemos solo para tu comodidad. No controlamos su contenido y no somos responsables de él.' )
				. $h2( '7. Protección de datos y cookies' )
				. $p( 'Cómo tratamos tus datos personales se explica en la <a href="/politica-de-privacidad/">Política de privacidad</a>, y qué cookies usamos, en la <a href="/politica-de-cookies/">Política de cookies</a>.' )
				. $h2( '8. Ley aplicable' )
				. $p( 'Este aviso legal se rige por la ley española. Para cualquier conflicto, las partes se someten a los juzgados y tribunales de Toledo, salvo que la ley establezca otro fuero, como ocurre cuando el usuario es un consumidor.' ),
		),
		array(
			'slug'        => 'politica-de-privacidad',
			'titulo'      => 'Política de privacidad',
			'orden'       => 11,
			'descripcion' => 'Política de privacidad de GR Electricidad, S.L.: qué datos tratamos, para qué y cómo ejercer tus derechos.',
			'contenido'   =>
				$p( 'Aquí te explicamos, de forma sencilla, qué datos personales recogemos, para qué y qué derechos tienes, según el Reglamento General de Protección de Datos (RGPD) y la Ley Orgánica 3/2018 (LOPDGDD).' )
				. $h2( '1. Responsable' )
				. $ul(
					array(
						'<strong>Empresa:</strong> [gr_dato clave="razon_social"]',
						'<strong>CIF:</strong> [gr_dato clave="cif"]',
						'<strong>Domicilio:</strong> [gr_dato clave="direccion"], [gr_dato clave="cp"] [gr_dato clave="localidad"] ([gr_dato clave="provincia"])',
						'<strong>Email de contacto para privacidad:</strong> ' . $email_legal,
					)
				)
				. $h2( '2. Qué datos tratamos y para qué' )
				. $tabla(
					array( 'Cuándo', 'Para qué', 'Base legal', 'Cuánto tiempo' ),
					array(
						array( 'Nos pides presupuesto por el formulario, email o teléfono', 'Responderte y preparar el presupuesto', 'Tu consentimiento y la aplicación de medidas precontractuales', '1 año si no llegamos a contratar' ),
						array( 'Eres cliente', 'Realizar el trabajo, facturar y dar garantía', 'Ejecución del contrato y obligaciones legales', 'Mientras dure la relación y después los plazos legales (6 años en materia contable y fiscal)' ),
						array( 'Nos envías tu currículum', 'Valorar tu candidatura en procesos de selección', 'Tu consentimiento', '12 meses' ),
					)
				)
				. $sc( '[gr_si_analytics]<p>Si aceptas las cookies de analítica, también tratamos datos de navegación de forma agregada para saber cómo se usa la web. Base legal: tu consentimiento. Más información en la <a href="/politica-de-cookies/">Política de cookies</a>.</p>[/gr_si_analytics]' )
				. $p( 'Solo pedimos los datos necesarios: nombre, empresa, teléfono, email y lo que nos cuentes sobre tu proyecto. No tomamos decisiones automatizadas ni elaboramos perfiles.' )
				. $h2( '3. A quién comunicamos tus datos' )
				. $p( 'No vendemos ni cedemos tus datos. Solo los comunicamos cuando la ley nos obliga (por ejemplo, a la Agencia Tributaria) o a proveedores que nos prestan servicios y que firman con nosotros un contrato de encargado del tratamiento, como el alojamiento de la web y del correo electrónico (Dinahosting, en España).' )
				. $sc( '[gr_si_analytics]<p>Si aceptas las cookies de analítica, Google (Google Analytics) puede tratar datos fuera de la Unión Europea. Lo hace con las garantías del Marco de Privacidad de Datos UE-EE. UU. y de las cláusulas contractuales tipo aprobadas por la Comisión Europea.</p>[/gr_si_analytics]' )
				. $h2( '4. Tus derechos' )
				. $p( 'Puedes pedirnos en cualquier momento:' )
				. $ul( array( '<strong>Acceso:</strong> saber qué datos tuyos tenemos.', '<strong>Rectificación:</strong> corregirlos si son inexactos.', '<strong>Supresión:</strong> que los borremos.', '<strong>Oposición y limitación:</strong> que dejemos de usarlos o los usemos solo para ciertos fines.', '<strong>Portabilidad:</strong> recibirlos en un formato que puedas llevar a otra empresa.', '<strong>Retirar tu consentimiento</strong> cuando quieras, sin que afecte a lo hecho antes.' ) )
				. $p( 'Escríbenos a ' . $email_legal . ' o por correo postal a nuestro domicilio, indicando qué derecho quieres ejercer. Si tenemos dudas sobre tu identidad, podremos pedirte que la acredites. Te responderemos en un mes como máximo.' )
				. $p( 'Si crees que no hemos tratado bien tus datos, puedes reclamar ante la Agencia Española de Protección de Datos en <a href="https://www.aepd.es">www.aepd.es</a>.' )
				. $h2( '5. Seguridad' )
				. $p( 'Aplicamos medidas técnicas y organizativas para proteger tus datos: la web funciona con conexión cifrada (HTTPS), el acceso a los datos está limitado al personal que los necesita y mantenemos los sistemas actualizados.' )
				. $h2( '6. Menores' )
				. $p( 'Esta web se dirige a empresas y profesionales. No recogemos a sabiendas datos de menores de 14 años.' )
				. $h2( '7. Cambios en esta política' )
				. $p( 'Si cambiamos esta política, publicaremos aquí la nueva versión con su fecha de actualización.' ),
		),
		array(
			'slug'        => 'politica-de-cookies',
			'titulo'      => 'Política de cookies',
			'orden'       => 12,
			'descripcion' => 'Política de cookies de la web de GR Electricidad, S.L.',
			'contenido'   =>
				$h2( '1. Qué son las cookies' )
				. $p( 'Las cookies son pequeños archivos que una web guarda en tu navegador para recordar información, por ejemplo, si ya aceptaste o rechazaste el aviso de cookies.' )
				. $h2( '2. Qué cookies usamos' )
				. $sc(
					'[gr_no_analytics]<p>Esta web <strong>no usa cookies de analítica ni de publicidad</strong>. Solo puede usar cookies técnicas, necesarias para que funcione (por ejemplo, las que mantienen la sesión de quien administra la web). Estas cookies no necesitan tu consentimiento.</p>[/gr_no_analytics]'
					. '[gr_si_analytics]<p>Usamos las mínimas posibles. Las de analítica solo se activan si las aceptas.</p>'
					. '<figure class="wp-block-table"><table><thead><tr><th>Cookie</th><th>De quién</th><th>Para qué</th><th>Duración</th><th>Tipo</th></tr></thead><tbody>'
					. '<tr><td><code>gr_cookie_consent</code></td><td>GR Electricidad</td><td>Recordar tu elección sobre las cookies</td><td>12 meses</td><td>Técnica (necesaria)</td></tr>'
					. '<tr><td><code>_ga</code></td><td>Google Analytics</td><td>Distinguir visitas de forma anónima para hacer estadísticas</td><td>2 años</td><td>Analítica (opcional)</td></tr>'
					. '<tr><td><code>_ga_*</code></td><td>Google Analytics</td><td>Mantener el estado de la visita</td><td>2 años</td><td>Analítica (opcional)</td></tr>'
					. '</tbody></table></figure>'
					. '<p>Las cookies técnicas son necesarias para que la web funcione y no requieren tu consentimiento. Las de analítica sí, y no se instalan hasta que pulsas «Aceptar». Puedes consultar cómo trata Google los datos en <a href="https://policies.google.com/privacy?hl=es">su política de privacidad</a>.</p>'
					. '<h2>3. Cómo aceptar, rechazar o cambiar tu elección</h2>'
					. '<p>La primera vez que entras te mostramos un aviso con tres botones: Aceptar todas, Rechazar y Configurar. Puedes cambiar tu elección cuando quieras con el botón «Configurar cookies» del pie de página o el que aparece al final de esta página.</p>[/gr_si_analytics]'
				)
				. $p( 'También puedes borrar o bloquear las cookies desde tu navegador:' )
				. $ul( array( '<a href="https://support.google.com/chrome/answer/95647?hl=es">Google Chrome</a>', '<a href="https://support.mozilla.org/es/kb/Borrar%20cookies">Mozilla Firefox</a>', '<a href="https://support.apple.com/es-es/guide/safari/sfri11471/mac">Safari</a>', '<a href="https://support.microsoft.com/es-es/microsoft-edge">Microsoft Edge</a>' ) )
				. $h2( 'Más información' )
				. $p( 'Para cualquier duda, escríbenos a ' . $email_legal . '. Cómo tratamos tus datos personales se explica en la <a href="/politica-de-privacidad/">Política de privacidad</a>.' ),
		),
	),

	/* ======================================================================
	 * FOTOS DE OBRA (setup/fotos). Orden = orden en la galería.
	 * ==================================================================== */
	'fotos'   => array(
		array( 'archivo' => 'flota-furgonetas-gr-electricidad.jpg', 'titulo' => 'Flota de furgonetas', 'alt' => 'Flota de furgonetas de GR Electricidad', 'leyenda' => 'Nuestra flota' ),
		array( 'archivo' => 'camaras-videovigilancia.jpg', 'titulo' => 'Cámaras de videovigilancia', 'alt' => 'Cámaras de videovigilancia domo, bala y motorizadas', 'leyenda' => 'Cámaras de videovigilancia', 'galeria' => false ),
		array( 'archivo' => 'trabajo-en-altura-linea-aerea.jpg', 'titulo' => 'Trabajo en altura en línea aérea', 'alt' => 'Electricista de GR Electricidad trabajando en altura en un apoyo de línea aérea de media tensión', 'leyenda' => 'Trabajo en línea aérea de media tensión' ),
		array( 'archivo' => 'cuadro-general-baja-tension-embarrado.jpg', 'titulo' => 'Cuadro general de baja tensión', 'alt' => 'Cuadro general de baja tensión con embarrado de cobre', 'leyenda' => 'Cuadro general de baja tensión' ),
		array( 'archivo' => 'montaje-paneles-solares-cubierta.jpg', 'titulo' => 'Montaje de paneles solares en cubierta', 'alt' => 'Técnico montando paneles solares fotovoltaicos en la cubierta de una nave', 'leyenda' => 'Autoconsumo fotovoltaico en cubierta' ),
		array( 'archivo' => 'centro-transformacion-prefabricado.jpg', 'titulo' => 'Centro de transformación prefabricado', 'alt' => 'Centro de transformación prefabricado con armario de acometida', 'leyenda' => 'Centro de transformación' ),
		array( 'archivo' => 'enfriadora-climatizacion-industrial.jpg', 'titulo' => 'Enfriadora de climatización industrial', 'alt' => 'Enfriadora de climatización industrial instalada en cubierta', 'leyenda' => 'Climatización industrial' ),
		array( 'archivo' => 'ensayos-centro-transformacion.jpg', 'titulo' => 'Ensayos en centro de transformación', 'alt' => 'Técnicos realizando ensayos en las celdas de un centro de transformación', 'leyenda' => 'Mantenimiento de centro de transformación' ),
		array( 'archivo' => 'instalacion-fotovoltaica.jpg', 'titulo' => 'Instalación fotovoltaica', 'alt' => 'Filas de paneles solares fotovoltaicos de una instalación de autoconsumo', 'leyenda' => 'Instalación fotovoltaica' ),
		array( 'archivo' => 'terminales-cables-media-tension.jpg', 'titulo' => 'Terminales de media tensión', 'alt' => 'Terminales de cables de media tensión en una celda', 'leyenda' => 'Terminales de media tensión' ),
		array( 'archivo' => 'cuadro-distribucion-circuitos-rotulados.jpg', 'titulo' => 'Cuadro de distribución', 'alt' => 'Cuadro de distribución con los circuitos rotulados', 'leyenda' => 'Cuadro de distribución rotulado' ),
		array( 'archivo' => 'apoyo-linea-aerea-media-tension.jpg', 'titulo' => 'Apoyo de línea aérea', 'alt' => 'Apoyo metálico de una línea aérea de media tensión', 'leyenda' => 'Línea aérea de media tensión' ),
		array( 'archivo' => 'analizador-de-redes.jpg', 'titulo' => 'Analizador de redes', 'alt' => 'Analizador de redes conectado midiendo la calidad del suministro', 'leyenda' => 'Análisis de redes' ),
		array( 'archivo' => 'celda-media-tension-rele-proteccion.jpg', 'titulo' => 'Celda de media tensión', 'alt' => 'Celda de media tensión con relé de protección', 'leyenda' => 'Celda de media tensión' ),
		array( 'archivo' => 'fabricacion-cuadros-electricos-taller.jpg', 'titulo' => 'Fabricación de cuadros en taller', 'alt' => 'Estructuras de cuadros eléctricos en fabricación en el taller de Pantoja', 'leyenda' => 'Fabricación de cuadros en nuestro taller' ),
		array( 'archivo' => 'climatizacion-cubierta-conexiones.jpg', 'titulo' => 'Climatización en cubierta', 'alt' => 'Equipo de climatización en cubierta con sus conexiones hidráulicas', 'leyenda' => 'Climatización en cubierta' ),
		array( 'archivo' => 'equipo-ensayo-protecciones.jpg', 'titulo' => 'Ensayo de protecciones', 'alt' => 'Equipo de ensayo de protecciones en un centro de transformación', 'leyenda' => 'Ensayo de protecciones' ),
		array( 'archivo' => 'centro-transformacion-interior.jpg', 'titulo' => 'Interior de centro de transformación', 'alt' => 'Interior de un centro de transformación con rejilla de protección', 'leyenda' => 'Centro de transformación' ),
		array( 'archivo' => 'medida-de-aislamiento.jpg', 'titulo' => 'Medida de aislamiento', 'alt' => 'Medida de aislamiento con equipo de comprobación', 'leyenda' => 'Medida de aislamiento' ),
		array( 'archivo' => 'montaje-cuadro-electrico-nave.jpg', 'titulo' => 'Montaje de cuadro eléctrico', 'alt' => 'Montaje de un cuadro eléctrico en una nave industrial', 'leyenda' => 'Montaje de cuadro en nave' ),
		array( 'archivo' => 'armario-distribucion-interruptores.jpg', 'titulo' => 'Armario de distribución', 'alt' => 'Armario de distribución con interruptores automáticos', 'leyenda' => 'Armario de distribución' ),
		array( 'archivo' => 'maleta-ensayo-reles.jpg', 'titulo' => 'Ensayo de relés', 'alt' => 'Maleta de inyección para el ensayo de relés de protección', 'leyenda' => 'Ensayo de relés de protección' ),
		array( 'archivo' => 'embarrado-cobre-cuadro.jpg', 'titulo' => 'Embarrado de cobre', 'alt' => 'Embarrado de cobre de un cuadro eléctrico en montaje', 'leyenda' => 'Embarrado de cobre' ),
		array( 'archivo' => 'cuadro-electrico-interruptor-general.jpg', 'titulo' => 'Cuadro con interruptor general', 'alt' => 'Cuadro eléctrico nuevo con interruptor general, listo para instalar', 'leyenda' => 'Cuadro listo para instalar' ),
		array( 'archivo' => 'tendido-cableado-cuadro-electrico.jpg', 'titulo' => 'Tendido de cableado', 'alt' => 'Tendido de cableado hasta un cuadro eléctrico', 'leyenda' => 'Tendido de cableado' ),
		array( 'archivo' => 'cuadro-electrico-sala-tecnica.jpg', 'titulo' => 'Cuadro en sala técnica', 'alt' => 'Cuadro eléctrico en una sala técnica', 'leyenda' => 'Cuadro en sala técnica' ),
		array( 'archivo' => 'cuadro-electrico-oficinas.jpg', 'titulo' => 'Cuadro de oficinas', 'alt' => 'Cuadro eléctrico de unas oficinas', 'leyenda' => 'Cuadro de oficinas' ),
	),
);
