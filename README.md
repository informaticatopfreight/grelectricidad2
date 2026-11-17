# Web de GR Electricidad — versión con fondo claro

> Es la misma web que la de [informaticatopfreight/grelectricidad](https://github.com/informaticatopfreight/grelectricidad) (fondo oscuro), con los mismos menús, textos, fotos y funciones, pero con fondo claro. El tema se llama **«GR Electricidad (fondo claro)»** y su carpeta es `gr-electricidad-claro`, así que se pueden instalar los dos en el mismo WordPress para comparar (solo uno activo a la vez).

Tema de WordPress a medida para **grelectricidad.es**: diseño claro con el rojo de la marca, animaciones (GSAP), aviso de cookies conforme a la AEPD, formulario de presupuesto, textos legales y SEO local.

## Qué hay en este repositorio

| Ruta | Qué es |
|---|---|
| `dist/gr-electricidad-claro.zip` | **El tema listo para subir a WordPress.** Es lo único que necesitas. |
| `gr-electricidad-claro/` | El código del tema (el mismo contenido que el zip). |
| `gr-electricidad-claro/setup/` | Textos de todas las páginas y las 27 fotos (obras, flota y cámaras), que se cargan con un botón. |
| `build.sh` | Vuelve a generar el zip si cambias algo del código. |

---

## Instalación en Dinahosting, paso a paso

> La web antigua tiene malware. **No copies nada de ella**: ni la base de datos, ni la carpeta `wp-content`, ni plugins. Esta instalación parte de cero y los textos y fotos ya van dentro del tema, corregidos.

### 1. Guarda una copia de la web antigua y bórrala
1. En el panel de Dinahosting, haz una copia de seguridad completa del alojamiento (archivos y base de datos) y descárgala. Es solo por si acaso: no la vuelvas a subir.
2. Borra todos los archivos de la web antigua (carpeta `www` o `public_html` del dominio) y su base de datos.
3. Cambia **todas** las contraseñas: panel de Dinahosting, FTP/SSH, cuentas de correo del dominio. Si el malware las tenía, así dejan de servir.

### 2. Instala un WordPress nuevo
1. Desde el instalador de aplicaciones de Dinahosting, instala **la última versión de WordPress** en español, con una **base de datos nueva**.
2. Cuando pida el usuario administrador:
   - **No uses** `admin`, `admin1`, `administrador` ni el nombre de la empresa. Usa algo como `gestion-gr-7k`.
   - Contraseña larga (la que te proponga WordPress) y guárdala en un gestor de contraseñas.
   - Email: uno vuestro, de la empresa.
3. Comprueba que la web carga con `https://` (el certificado ya está activo).

### 3. Sube el tema
1. Descarga `dist/gr-electricidad-claro.zip` de este repositorio (botón **Download raw file** en GitHub).
2. En WordPress: **Apariencia → Temas → Añadir nuevo tema → Subir tema** → elige el zip → **Instalar** → **Activar**.

### 4. Carga el contenido con un botón
1. Ve a **Apariencia → Configurar web GR** y pulsa **Configurar la web**.
2. En unos segundos:
   - crea las 15 páginas (inicio, 6 servicios, empresa, obras, acreditaciones, contacto, aviso legal, privacidad y cookies);
   - sube las 27 fotos con su texto alternativo y su leyenda;
   - pone la portada, el menú principal y el menú legal del pie;
   - **borra** la entrada «¡Hola, mundo!», la página de ejemplo y el comentario de prueba;
   - cierra los comentarios y desactiva el registro de usuarios;
   - elimina los plugins de ejemplo (Hello Dolly, Akismet) si los hay.

### 5. Revisa los datos
En **Apariencia → Personalizar → Datos de GR Electricidad** están el CIF, las direcciones, los teléfonos y los emails. Todo lo que cambies ahí se actualiza en toda la web, **también en los textos legales**.

- **Pendiente:** rellena «Datos del Registro Mercantil» (tomo, folio, hoja e inscripción). Aparece en el Aviso legal.
- «Email donde llegan las solicitudes de presupuesto»: por defecto `grelectricidad@grelectricidad.es`.

### 6. Seguridad en `wp-config.php` (opcional, recomendado)
Con el gestor de archivos de Dinahosting, abre `wp-config.php` y, justo antes de la línea `/* That's all, stop editing! */` (o `/* ¡Eso es todo, deja de editar! */`), añade:

```php
define( 'DISALLOW_FILE_EDIT', true );
```

Así nadie puede modificar el código desde el escritorio de WordPress aunque consiga entrar.

### 7. Google
- **Ajustes → Lectura**: asegúrate de que **no** está marcado «Disuadir a los motores de búsqueda».
- Da de alta la web en **Google Search Console** y envía el mapa del sitio: `https://grelectricidad.es/wp-sitemap.xml`.
- Crea o reclama la ficha de **Google Business Profile** con la dirección de Pantoja y enlaza la web.
- **Analítica (opcional):** si quieres medir visitas, crea una propiedad de Google Analytics 4 y pega su ID (`G-XXXXXXX`) en Personalizar → Datos de GR Electricidad. El aviso de cookies (Aceptar / Rechazar / Configurar) aparece entonces automáticamente y la Política de cookies se adapta sola. **Si lo dejas vacío, no hay cookies de terceros ni aviso**, que es lo más sencillo.

### 8. Prueba el formulario
Envía una solicitud de presupuesto desde la web y comprueba que llega. Si no llega, instala el plugin **WP Mail SMTP** y configúralo con una cuenta de correo del dominio (Dinahosting te da los datos SMTP).

---

## Qué resuelve respecto a la web antigua

**Legal**
- Aviso legal, Política de privacidad y Política de cookies completas, en un menú del pie.
- Aviso de cookies con Aceptar / Rechazar / Configurar (solo si hay analítica). Sin Histats ni otros contadores.
- Cláusula de protección de datos junto al email de currículums y en el formulario.
- Las fuentes tipográficas se sirven desde el propio servidor (no se envían datos a Google Fonts) y el mapa es un enlace, no un mapa incrustado: no hace falta clave de Google Maps ni se instalan cookies de Google.

**Seguridad** (en `inc/seguridad.php`)
- WordPress nuevo, sin Divi, sin el plugin de IONOS/1&1 y sin plugins innecesarios.
- No se pueden averiguar los nombres de usuario (`/author/`, `?author=`, API REST y mapa del sitio bloqueados).
- Aviso en el escritorio si existe un usuario `admin` o `admin1`.
- XML-RPC desactivado, versión de WordPress oculta, comentarios desactivados en toda la web.
- Cabeceras de seguridad y mensajes de error de acceso genéricos.
- Actualizaciones automáticas de WordPress y plugins activadas.

**Textos**
- Faltas de ortografía corregidas y datos coherentes (fundada en 2007, © con el año actual, kV, «Acreditaciones» en menú y página).
- Acreditaciones: AT1 (hasta 30 kV) y AT2 (sin límite).
- Páginas nuevas de Climatización y Asesoramiento técnico.

**Clientes y SEO**
- Todos los teléfonos se pueden pulsar en el móvil.
- Formulario de presupuesto con casilla de privacidad, protección antispam y límite de envíos.
- Un único título principal (H1) por página.
- Descripción para Google en cada página (editable en la caja «Descripción para Google» del editor).
- Datos estructurados de empresa local (schema.org `Electrician`) para Google y Maps.
- Fotos renombradas, optimizadas, sin datos de ubicación GPS y con texto alternativo y leyenda.
- Redirecciones 301 desde las direcciones antiguas (`/trabajos`, `/acreditaciones-profesionales`, etc.) para no perder posicionamiento.
- Un solo mapa del sitio (el de WordPress).
- Se puede ampliar con los dedos en el móvil.
- Ligero: sin jQuery ni constructores visuales; scripts diferidos.

## Editar la web después

- **Textos de cada página:** Páginas → editar. Los servicios son las páginas «hijas» de *Servicios*; el **extracto** de cada una es el texto corto que sale en la portada.
- **Fotos de obra:** edita la página *Obras* y sube las imágenes desde ella (quedan adjuntas). Rellena siempre «Texto alternativo» y «Leyenda».
- **Imagen grande de cada página:** «Imagen destacada» en el editor.
- **Logotipos de clientes:** en `gr-electricidad-claro/assets/img/clientes/` y la lista en `gr_clientes()` de `inc/datos.php`. Confirmad con cada cliente que podéis mostrar su logo.
- **Lista de acreditaciones:** en `gr-electricidad-claro/inc/datos.php`, función `gr_acreditaciones()`.
- **Animaciones:** `gr-electricidad-claro/assets/js/main.js`. Quien tenga activado «reducir movimiento» en su dispositivo ve la web sin animaciones.

## Generar el zip

```bash
./build.sh
```
