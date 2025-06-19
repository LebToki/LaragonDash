(function () {
  "use strict";
  
  document.addEventListener("DOMContentLoaded", function () {
    const root = document.documentElement;
    const navbar = document.querySelector(".navbar");
    const sidebar = document.querySelector(".sidebar");
    const themeToggleBtn = document.getElementById("themeToggleBtn");
    const langSelect = document.getElementById("lang-select");
    const searchInput = document.getElementById("projectSearch");
    
    // ========== THEME ==========
    const savedTheme = localStorage.getItem("theme") || "light";
    applyTheme(savedTheme);
    
    if (themeToggleBtn) {
      themeToggleBtn.addEventListener("click", () => {
        const currentTheme = document.body.classList.contains("theme-dark") ? "dark" : "light";
        const newTheme = currentTheme === "dark" ? "light" : "dark";
        localStorage.setItem("theme", newTheme);
        applyTheme(newTheme);
        showToast(`Theme switched to ${newTheme}`);
      });
    }
    
    function applyTheme(theme) {
      root.setAttribute("data-theme", theme);
      const isDark = theme === "dark";
      
      navbar?.classList.toggle("navbar-dark", isDark);
      navbar?.classList.toggle("bg-dark", isDark);
      navbar?.classList.toggle("navbar-light", !isDark);
      navbar?.classList.toggle("bg-white", !isDark);
      
      sidebar?.classList.toggle("bg-dark", isDark);
      sidebar?.classList.toggle("text-white", isDark);
      sidebar?.classList.toggle("bg-light", !isDark);
      
      document.body.classList.toggle("theme-dark", isDark);
      themeToggleBtn.innerText = theme;
      themeToggleBtn.setAttribute("aria-label", theme);
    }
    
    // ========== TOAST ==========
    function showToast(message) {
      const toast = new bootstrap.Toast(document.getElementById("toast"));
      toast.show();
      document.getElementById("toastMessage").innerText = message;
    }
    
    // ========== LANGUAGE ==========
    const savedLang = localStorage.getItem("lang") || "en";
    langSelect && (langSelect.value = savedLang);
    applyLanguageDirection(savedLang);
    loadLanguageFile(savedLang);
    
    const supportedLangs = {
      "en": { label: "English", flag: "🇬🇧" },
      "fr": { label: "Français", flag: "🇫🇷" },
      "es": { label: "Español", flag: "🇪🇸" },
      "de": { label: "Deutsch", flag: "🇩🇪" },
      "pt": { label: "Português", flag: "🇵🇹" },
      "id": { label: "Bahasa Indonesia", flag: "🇮🇩" },
      "tl": { label: "Tagalog", flag: "🇵🇭" },
      "ar": { label: "العربية", flag: "🇸🇦" },
      "hi": { label: "हिन्दी", flag: "🇮🇳" },
      "ur": { label: "اردو", flag: "🇵🇰" }
    };
    
    if (langSelect) {
      langSelect.innerHTML = '';
      Object.entries(supportedLangs).forEach(([code, { label, flag }]) => {
        const opt = document.createElement('option');
        opt.value = code;
        opt.textContent = `${flag} ${label}`;
        if (code === savedLang) opt.selected = true;
        langSelect.appendChild(opt);
      });
    }
    
    langSelect?.addEventListener("change", function () {
      const selected = this.value;
      localStorage.setItem("lang", selected);
      document.cookie = `lang=${selected}; path=/; max-age=31536000`;
      applyLanguageDirection(selected);
      location.reload();
    });
    
    async function loadLanguageFile(lang) {
      try {
        const response = await fetch(`includes/lang/${lang}.json`);
        const translations = await response.json();
        document.querySelectorAll("[data-i18n]").forEach((el) => {
          const keys = el.dataset.i18n.split(".");
          let value = translations;
          keys.forEach(k => value = value?.[k]);
          if (value) el.innerHTML = value;
        });
      } catch (e) {
        console.warn("Could not load translation file", e);
      }
    }
    
    function applyLanguageDirection(lang) {
      const rtlLangs = ["ar", "ur"];
      const dir = rtlLangs.includes(lang) ? "rtl" : "ltr";
      document.documentElement.setAttribute("dir", dir);
      document.body.setAttribute("dir", dir);
      document.body.classList.toggle("rtl", dir === "rtl");
      
      document.body.classList.remove("font-ar", "font-ur", "font-hi");
      
      if (lang === "ar") {
        document.body.classList.add("font-ar");
      } else if (lang === "ur") {
        document.body.classList.add("font-ur");
      } else if (lang === "hi") {
        document.body.classList.add("font-hi");
      }
    }
    
    // ========== SEARCH ==========
    searchInput?.addEventListener("input", function () {
      const keyword = this.value.toLowerCase();
      document.querySelectorAll(".card-footer a").forEach(link => {
        const tile = link.closest(".col-6, .col-sm-4, .col-md-3, .col-xl-2");
        const match = link.textContent.toLowerCase().includes(keyword);
        tile.style.display = match ? "" : "none";
      });
    });
  });
  
  // ========== GLOBAL TOAST ==========
  window.showToast = function (message) {
    const toastBody = document.querySelector(".toast-body");
    const toastElement = document.getElementById("toastContent");
    if (toastBody && toastElement) {
      toastBody.textContent = message;
      bootstrap.Toast.getOrCreateInstance(toastElement).show();
    }
  };
})();
