(function () {
  "use strict";
  
  document.addEventListener('DOMContentLoaded', function () {
    const themeToggleBtn = document.getElementById('themeToggleBtn');
    const navbar = document.querySelector('.navbar');
    const sidebar = document.querySelector('.sidebar');
    const rootEl = document.documentElement;
    
    function applyTheme(theme) {
      localStorage.setItem("theme", theme);
      rootEl.setAttribute("data-theme", theme);
      
      if (theme === 'dark') {
        document.body.classList.add('theme-dark');
        // Navbar
        navbar.classList.remove('navbar-light', 'bg-white');
        navbar.classList.add('navbar-dark', 'bg-dark');
        // Sidebar
        if (sidebar) {
          sidebar.classList.remove('bg-light');
          sidebar.classList.add('bg-dark', 'text-white');
        }
      } else {
        document.body.classList.remove('theme-dark');
        // Navbar
        navbar.classList.remove('navbar-dark', 'bg-dark');
        navbar.classList.add('navbar-light', 'bg-white');
        // Sidebar
        if (sidebar) {
          sidebar.classList.remove('bg-dark', 'text-white');
          sidebar.classList.add('bg-light');
        }
      }
      
      // Update button text and aria label
      if (themeToggleBtn) {
        themeToggleBtn.innerText = theme;
        themeToggleBtn.setAttribute("aria-label", theme);
      }
    }
    
    // Load and apply saved theme
    const savedTheme = localStorage.getItem("theme") || "light";
    applyTheme(savedTheme);
    
    // Toggle theme
    if (themeToggleBtn) {
      themeToggleBtn.addEventListener("click", () => {
        const newTheme = (localStorage.getItem("theme") === "dark") ? "light" : "dark";
        applyTheme(newTheme);
        showToast("Theme switched to " + newTheme);
      });
    }
    
    // Project tile inline search
    document.getElementById('projectSearch')?.addEventListener('input', function () {
      const keyword = this.value.toLowerCase();
      document.querySelectorAll('.card-footer a').forEach(link => {
        const tile = link.closest('.col-6, .col-sm-4, .col-md-3, .col-xl-2');
        tile.style.display = link.textContent.toLowerCase().includes(keyword) ? '' : 'none';
      });
    });
    
    // Language selector and translations
    const langSelect = document.getElementById("lang-select");
    let currentLang = localStorage.getItem("lang") || "en";
    if (langSelect) {
      langSelect.value = currentLang;
      langSelect.addEventListener("change", () => {
        localStorage.setItem("lang", langSelect.value);
        applyTranslations(langSelect.value);
      });
    }

    function applyTranslations(lang) {
      fetch(`assets/languages/${lang}.json`)
        .then(r => r.ok ? r.json() : Promise.reject())
        .catch(() => fetch('assets/languages/en.json').then(r => r.json()).then(d => { lang = 'en'; return d; }))
        .then(data => {
          document.documentElement.lang = lang;
          if (lang === 'ar') {
            document.documentElement.dir = 'rtl';
          } else {
            document.documentElement.removeAttribute('dir');
          }
          document.querySelectorAll('[data-i18n]').forEach(el => {
            const key = el.getAttribute('data-i18n');
            if (data[key]) {
              el.innerHTML = data[key];
            }
          });
          document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
            const key = el.getAttribute('data-i18n-placeholder');
            if (data[key]) {
              el.setAttribute('placeholder', data[key]);
            }
          });
        })
        .catch(() => {});
    }

    applyTranslations(currentLang);
  });
  
  // Global Toast function
  window.showToast = function (message) {
    const toastBody = document.querySelector('.toast-body');
    if (toastBody) {
      toastBody.textContent = message;
      const toast = new bootstrap.Toast(document.getElementById('toastContent'));
      toast.show();
    }
  };
})();
