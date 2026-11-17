/**
 * GR Electricidad — animaciones, menú móvil, reloj y aviso de cookies.
 * Sin JavaScript la web se ve completa: las animaciones solo añaden movimiento.
 */
(function () {
	'use strict';

	var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	/* ---------- Menú móvil ---------- */
	var toggle = document.querySelector('.js-nav-toggle');
	var nav = document.querySelector('.js-nav');
	if (toggle && nav) {
		toggle.addEventListener('click', function () {
			var open = nav.classList.toggle('is-open');
			toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
			toggle.textContent = open ? 'Cerrar' : 'Menú';
			document.body.style.overflow = open ? 'hidden' : '';
		});
		nav.addEventListener('click', function (e) {
			if (e.target.closest('a') && nav.classList.contains('is-open')) {
				toggle.click();
			}
		});
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && nav.classList.contains('is-open')) {
				toggle.click();
				toggle.focus();
			}
		});
	}

	/* ---------- Cabecera que se oculta al bajar ---------- */
	var header = document.querySelector('.js-header');
	if (header) {
		var last = window.scrollY;
		window.addEventListener('scroll', function () {
			var y = window.scrollY;
			var hide = y > last && y > 240 && !(nav && nav.classList.contains('is-open'));
			header.classList.toggle('is-hidden', hide);
			last = y;
		}, { passive: true });
	}

	/* ---------- Reloj con la hora de Pantoja ---------- */
	var clock = document.querySelector('.js-clock');
	if (clock) {
		var tick = function () {
			try {
				clock.textContent = new Date().toLocaleTimeString('es-ES', { timeZone: 'Europe/Madrid', hour: '2-digit', minute: '2-digit', second: '2-digit' });
			} catch (e) { /* navegador antiguo */ }
		};
		tick();
		setInterval(tick, 1000);
	}

	/* ---------- Aviso de cookies (solo si hay Google Analytics configurado) ---------- */
	var GA = (window.GR && window.GR.gaId) || '';
	var COOKIE = 'gr_cookie_consent';
	var box = document.querySelector('.js-cookie');

	function readConsent() {
		var m = document.cookie.match(new RegExp('(?:^|; )' + COOKIE + '=([^;]*)'));
		return m ? decodeURIComponent(m[1]) : null;
	}
	function saveConsent(value) {
		var d = new Date();
		d.setTime(d.getTime() + 365 * 24 * 60 * 60 * 1000);
		document.cookie = COOKIE + '=' + encodeURIComponent(value) + '; expires=' + d.toUTCString() + '; path=/; SameSite=Lax' + (location.protocol === 'https:' ? '; Secure' : '');
	}
	function deleteGaCookies() {
		document.cookie.split('; ').forEach(function (c) {
			var name = c.split('=')[0];
			if (name === '_ga' || name.indexOf('_ga_') === 0) {
				var host = location.hostname.replace(/^www\./, '');
				['', host, '.' + host].forEach(function (dom) {
					document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/' + (dom ? '; domain=' + dom : '');
				});
			}
		});
	}
	var gaLoaded = false;
	function loadGa() {
		if (!GA || gaLoaded) { return; }
		gaLoaded = true;
		var s = document.createElement('script');
		s.async = true;
		s.src = 'https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent(GA);
		document.head.appendChild(s);
		window.dataLayer = window.dataLayer || [];
		window.gtag = function () { window.dataLayer.push(arguments); };
		window.gtag('js', new Date());
		window.gtag('config', GA, { anonymize_ip: true });
	}

	if (GA && box) {
		var prefs = box.querySelector('.js-cookie-prefs');
		var chk = box.querySelector('.js-cookie-analytics');
		var btnSave = box.querySelector('.js-cookie-save');
		var openBox = function () {
			chk.checked = readConsent() === 'analytics';
			box.hidden = false;
			box.querySelector('button').focus();
		};
		var closeBox = function (value) {
			saveConsent(value);
			box.hidden = true;
			prefs.hidden = true;
			btnSave.hidden = true;
			if (value === 'analytics') {
				loadGa();
			} else {
				deleteGaCookies();
				if (gaLoaded) { location.reload(); }
			}
		};
		box.querySelector('.js-cookie-accept').addEventListener('click', function () { closeBox('analytics'); });
		box.querySelector('.js-cookie-reject').addEventListener('click', function () { closeBox('necessary'); });
		box.querySelector('.js-cookie-config').addEventListener('click', function () {
			prefs.hidden = !prefs.hidden;
			btnSave.hidden = prefs.hidden;
		});
		btnSave.addEventListener('click', function () { closeBox(chk.checked ? 'analytics' : 'necessary'); });
		document.querySelectorAll('.js-cookie-open').forEach(function (b) {
			b.addEventListener('click', openBox);
		});

		var current = readConsent();
		if (current === 'analytics') {
			loadGa();
		} else if (current === null) {
			box.hidden = false;
		}
	}

	/* ---------- Animaciones (GSAP + ScrollTrigger) ---------- */
	if (reduce || !window.gsap || !window.ScrollTrigger) {
		return;
	}
	var gsap = window.gsap;
	gsap.registerPlugin(window.ScrollTrigger);
	var ease = 'power3.out';

	// Titular de portada: las líneas suben una a una.
	gsap.from('.js-hero-title .line > span', { yPercent: 110, duration: 1.1, ease: 'power4.out', stagger: 0.12, delay: 0.1 });

	// Títulos de páginas interiores.
	document.querySelectorAll('.js-split').forEach(function (el) {
		gsap.from(el, { y: 60, opacity: 0, duration: 1, ease: ease, delay: 0.1 });
	});

	// Imagen principal: cortinilla y zoom lento.
	document.querySelectorAll('.js-reveal').forEach(function (el) {
		gsap.fromTo(el, { clipPath: 'inset(100% 0% 0% 0%)' }, { clipPath: 'inset(0% 0% 0% 0%)', duration: 1.4, ease: 'expo.inOut', delay: 0.3 });
	});
	gsap.from('.js-kenburns', { scale: 1.18, duration: 2.6, ease: 'power2.out', delay: 0.3 });

	// Elementos que aparecen al bajar.
	window.ScrollTrigger.batch('.js-up', {
		start: 'top 90%',
		once: true,
		onEnter: function (els) {
			gsap.fromTo(els, { y: 60, opacity: 0 }, { y: 0, opacity: 1, duration: 0.9, ease: ease, stagger: 0.08, overwrite: true });
		}
	});
	gsap.set('.js-up', { opacity: 0 });
	// Lo que ya está a la vista al cargar aparece enseguida.
	window.ScrollTrigger.refresh();

	// Texto de empresa que se ilumina frase a frase.
	document.querySelectorAll('.js-highlight').forEach(function (el) {
		gsap.fromTo(el.children, { opacity: 0.16 }, {
			opacity: 1, ease: 'none', stagger: 0.5,
			scrollTrigger: { trigger: el, start: 'top 80%', end: 'bottom 45%', scrub: true }
		});
	});

	// Líneas rojas que se dibujan.
	document.querySelectorAll('.js-bar').forEach(function (el) {
		gsap.fromTo(el, { scaleX: 0 }, { scaleX: 1, ease: 'none', scrollTrigger: { trigger: el, start: 'top 92%', end: 'top 55%', scrub: true } });
	});

	// Contadores.
	document.querySelectorAll('.js-count').forEach(function (el) {
		var to = parseInt(el.getAttribute('data-to'), 10);
		var from = parseInt(el.getAttribute('data-from'), 10) || 0;
		if (isNaN(to)) { return; }
		var obj = { v: from };
		el.textContent = from;
		gsap.to(obj, {
			v: to, duration: 2.2, ease: 'power2.out',
			scrollTrigger: { trigger: el, start: 'top 85%', once: true },
			onUpdate: function () { el.textContent = Math.round(obj.v); }
		});
	});

	// Paralaje suave en fotos.
	document.querySelectorAll('.js-parallax').forEach(function (img) {
		gsap.fromTo(img, { yPercent: -6 }, { yPercent: 6, ease: 'none', scrollTrigger: { trigger: img.parentElement, start: 'top bottom', end: 'bottom top', scrub: true } });
	});

	// Lista de acreditaciones que entra desde la izquierda.
	window.ScrollTrigger.batch('.js-slide', {
		start: 'top 92%',
		once: true,
		onEnter: function (els) {
			gsap.fromTo(els, { x: -80, opacity: 0 }, { x: 0, opacity: 1, duration: 0.8, ease: ease, stagger: 0.07 });
		}
	});
	gsap.set('.js-slide', { opacity: 0 });

	// Recalcula posiciones cuando cargan las imágenes.
	window.addEventListener('load', function () { window.ScrollTrigger.refresh(); });
})();
