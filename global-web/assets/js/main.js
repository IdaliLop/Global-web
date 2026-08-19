// main.js — funcionalidades interactivas para Daisuki

document.addEventListener('DOMContentLoaded', () => {
  // NAV: comportamiento consistente del menú hamburguesa en móvil
  const navBurger = document.querySelector('.nav-burger');
  const mainNav = document.querySelector('.main-nav');
  const navLinks = document.querySelectorAll('.main-nav a');
  let navOpen = false;

  function syncNavState() {
    if (!mainNav || !navBurger) return;
    mainNav.classList.toggle('open', navOpen);
    navBurger.setAttribute('aria-label', navOpen ? 'Cerrar menú' : 'Abrir menú');
  }

  if (navBurger) {
    navBurger.addEventListener('click', () => {
      navOpen = !navOpen;
      syncNavState();
    });
  }

  navLinks.forEach((a) => {
    a.addEventListener('click', () => {
      navOpen = false;
      syncNavState();
    });
  });

  // SMOOTH SCROLL & SECTION TRANSITIONS
  const anchors = document.querySelectorAll('a[href^="#"]');
  anchors.forEach((a) => {
    a.addEventListener('click', (e) => {
      const href = a.getAttribute('href');
      if (href === '#' || href === '') return;
      const target = document.querySelector(href);
      if (target) {
        e.preventDefault();
        // add temporary class to current sections for subtle animation
        document.querySelectorAll('main > section, footer, header').forEach((s) => s.classList.add('section-hidden'));
        setTimeout(() => {
          target.scrollIntoView({ behavior: 'smooth', block: 'start' });
          // remove the hidden class after scrolling starts
          setTimeout(() => document.querySelectorAll('main > section, footer, header').forEach((s) => s.classList.remove('section-hidden')), 600);
        }, 80);
      }
    });
  });

  // GALLERY FILTER
  const filterContainer = document.querySelector('.platillo-filtros');
  const platos = Array.from(document.querySelectorAll('.tarjeta-platillo'));
  const FILTER_ANIM = 320; // ms — debe concordar con CSS

  function showAll() {
    platos.forEach((p) => {
      p.classList.remove('hidden');
      // ensure element is visible
      p.style.display = '';
      p.classList.remove('fade-out');
      p.classList.add('fade-in');
      setTimeout(() => p.classList.remove('fade-in'), FILTER_ANIM);
    });
  }

  function filterPlatos(category) {
    platos.forEach((plato) => {
      const cat = plato.dataset.category || 'principal';
      if (category === 'all' || category === cat) {
        // show
        if (plato.classList.contains('hidden')) {
          plato.classList.remove('hidden');
          plato.style.display = '';
        }
        plato.classList.remove('fade-out');
        plato.classList.add('fade-in');
        setTimeout(() => plato.classList.remove('fade-in'), FILTER_ANIM);
      } else {
        // hide with animation
        plato.classList.remove('fade-in');
        plato.classList.add('fade-out');
        setTimeout(() => {
          plato.classList.add('hidden');
          // keep layout stable by letting CSS collapse the card (fade-out sets height:0)
        }, FILTER_ANIM);
      }
    });
  }

  if (filterContainer) {
    filterContainer.addEventListener('click', (e) => {
      const btn = e.target.closest('[data-filter]');
      if (!btn) return;
      const filter = btn.dataset.filter;
      // UI active state
      filterContainer.querySelectorAll('button').forEach((b) => b.classList.remove('active'));
      btn.classList.add('active');
      if (filter === 'all') showAll(); else filterPlatos(filter);
    });
  }

  // CONTACT FORM VALIDATION + AJAX SUBMISSION
  const contactForm = document.getElementById('contact-form');
  function showFieldError(name, message) {
    const el = contactForm.querySelector(`.error[data-for="${name}"]`);
    if (el) { el.textContent = message; el.classList.add('show'); }
  }
  function clearFieldError(name) {
    const el = contactForm.querySelector(`.error[data-for="${name}"]`);
    if (el) { el.textContent = ''; el.classList.remove('show'); }
  }

  function validateEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }

  if (contactForm) {
    contactForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const formData = new FormData(contactForm);
      const nombre = (formData.get('nombre') || '').toString().trim();
      const email = (formData.get('email') || '').toString().trim();
      const mensaje = (formData.get('mensaje') || '').toString().trim();
      let ok = true;
      // limpiar errores previos
      ['nombre','email','mensaje'].forEach(clearFieldError);
      contactForm.querySelector('.form-status').textContent = '';

      if (nombre.length < 2) { showFieldError('nombre','Por favor escribe tu nombre.'); ok = false; }
      if (!validateEmail(email)) { showFieldError('email','Introduce un correo válido.'); ok = false; }
      if (mensaje.length < 6) { showFieldError('mensaje','El mensaje es muy corto.'); ok = false; }

      if (!ok) return;

      // enviar por fetch
      const action = contactForm.getAttribute('action') || '/assets/php/contact.php';
      fetch(action, {
        method: 'POST',
        body: formData,
      }).then((resp) => resp.json())
        .then((data) => {
          if (data && data.success) {
            contactForm.reset();
            contactForm.querySelector('.form-status').textContent = data.message || 'Mensaje enviado. Gracias.';
            contactForm.querySelector('.form-status').style.color = 'var(--color-kin)';
          } else {
            contactForm.querySelector('.form-status').textContent = (data && data.message) ? data.message : 'Ocurrió un error. Intenta nuevamente.';
            contactForm.querySelector('.form-status').style.color = '#ffd6d6';
          }
        }).catch((err) => {
          contactForm.querySelector('.form-status').textContent = 'No se pudo enviar el mensaje. Revisa tu conexión.';
          contactForm.querySelector('.form-status').style.color = '#ffd6d6';
          console.error('Error enviando formulario:', err);
        });
    });
  }

  // Inicializar filtros: agregar botón "Todos" activo por defecto
  if (filterContainer) {
    const defaultBtn = filterContainer.querySelector('[data-filter="all"]');
    if (defaultBtn) defaultBtn.classList.add('active');
  }

  // Hero promo slider (3 seconds)
  const promoSlides = Array.from(document.querySelectorAll('.promo-slide'));
  const promoDots = Array.from(document.querySelectorAll('.promo-dot'));

  if (promoSlides.length > 1) {
    let activePromoIndex = 0;
    const showPromoSlide = (index) => {
      promoSlides.forEach((slide, i) => {
        slide.classList.toggle('is-active', i === index);
      });
      promoDots.forEach((dot, i) => {
        dot.classList.toggle('is-active', i === index);
        dot.setAttribute('aria-pressed', String(i === index));
      });
    };

    promoDots.forEach((dot, idx) => {
      dot.addEventListener('click', () => {
        activePromoIndex = idx;
        showPromoSlide(activePromoIndex);
      });
    });

    setInterval(() => {
      activePromoIndex = (activePromoIndex + 1) % promoSlides.length;
      showPromoSlide(activePromoIndex);
    }, 3000);
  }

});
