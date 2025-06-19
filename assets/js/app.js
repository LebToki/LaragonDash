(function () {
  "use strict";
  
  document.addEventListener("DOMContentLoaded", function () {
    const root = document.documentElement;
    const navbar = document.querySelector(".navbar");
    const sidebar = document.querySelector(".sidebar");
    const themeToggleBtn = document.getElementById("themeToggleBtn");
    const langSelect = document.getElementById("lang-select");
    const searchInput = document.getElementById("projectSearch");
    
    // ========== THEME ========== //
    const savedTheme = localStorage.getItem("theme") || "light";
    applyTheme(savedTheme);
    
    if (themeToggleBtn) {
      themeToggleBtn.addEventListener("click", () => {
        const newTheme = document.body.classList.contains("theme-dark") ? "light" : "dark";
        localStorage.setItem("theme", newTheme);
        applyTheme(newTheme);
        showToast(`Theme switched to ${newTheme}`);
      });
    }
    
    function applyTheme(theme) {
      root.setAttribute("data-theme", theme);
      document.body.classList.toggle("theme-dark", theme === "dark");
      
      themeToggleBtn.innerText = theme;
      themeToggleBtn.setAttribute("aria-label", theme);
      
      navbar?.classList.toggle("navbar-dark", theme === "dark");
      navbar?.classList.toggle("bg-dark", theme === "dark");
      navbar?.classList.toggle("navbar-light", theme !== "dark");
      navbar?.classList.toggle("bg-white", theme !== "dark");
      
      sidebar?.classList.toggle("bg-dark", theme === "dark");
      sidebar?.classList.toggle("text-white", theme === "dark");
      sidebar?.classList.toggle("bg-light", theme !== "dark");
    }
    
    // ========== LANGUAGE ========== //
    const savedLang = localStorage.getItem("lang") || "en";
    
    if (langSelect) {
      fetch("includes/get_languages.php")
        .then((res) => res.json())
        .then((codes) => {
          langSelect.innerHTML = "";
          codes.forEach((code) => {
            const cfg = availableLanguages?.[code] || { label: code, flag: code };
            const option = document.createElement("option");
            option.value = code;
            option.textContent = `${cfg.flag} ${cfg.label}`;
            langSelect.appendChild(option);
          });
          langSelect.value = savedLang;
          loadLanguageFile(savedLang);
          applyLanguageDirection(savedLang);
        })
        .catch((err) => console.warn("Failed to load languages", err));
    }
    
    langSelect?.addEventListener("change", function () {
      const selectedLang = this.value;
      localStorage.setItem("lang", selectedLang);
      document.cookie = `lang=${selectedLang}; path=/; max-age=31536000`;
      applyLanguageDirection(selectedLang);
      location.reload(); // to refresh DOM strings
    });
    
    async function loadLanguageFile(lang) {
      try {
        const res = await fetch(`includes/languages/${lang}.json`);
        const translations = await res.json();
        window.i18n = translations;
        
        document.querySelectorAll("[data-i18n]").forEach((el) => {
          const keys = el.dataset.i18n.split(".");
          let value = translations;
          for (const key of keys) value = value?.[key];
          if (value) el.innerHTML = value;
        });
      } catch (err) {
        console.warn("Translation file failed to load:", err);
      }
    }
    
    function applyLanguageDirection(lang) {
      const rtlLangs = ["ar", "ur"];
      const direction = rtlLangs.includes(lang) ? "rtl" : "ltr";
      
      document.documentElement.setAttribute("dir", direction);
      document.body.setAttribute("dir", direction);
      document.body.classList.toggle("rtl", direction === "rtl");
      
      document.body.classList.remove("font-ar", "font-ur", "font-hi");
      
      if (lang === "ar") document.body.classList.add("font-ar");
      if (lang === "ur") document.body.classList.add("font-ur");
      if (lang === "hi") document.body.classList.add("font-hi");
    }
    
    // ========== SEARCH FILTER ========== //
    searchInput?.addEventListener("input", function () {
      const keyword = this.value.toLowerCase();
      document.querySelectorAll(".card-footer a").forEach(link => {
        const tile = link.closest(".col-6, .col-sm-4, .col-md-3, .col-xl-2");
        tile.style.display = link.textContent.toLowerCase().includes(keyword) ? "" : "none";
      });
    });
  });
  
  // ========== GLOBAL TOAST ========== //
  window.showToast = function (message) {
    const toastBody = document.querySelector(".toast-body");
    const toastElement = document.getElementById("toastContent");
    if (toastBody && toastElement) {
      toastBody.textContent = message;
      bootstrap.Toast.getOrCreateInstance(toastElement).show();
    }
  };
})();
