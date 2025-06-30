document.addEventListener("DOMContentLoaded", () => {
  const themeBtn = document.getElementById("themeToggleBtn");
  const toast = document.getElementById("toastContent");
  const status = document.getElementById("themeStatus") || (() => {
    const el = document.createElement("div");
    el.id = "themeStatus";
    el.className = "visually-hidden";
    el.setAttribute("role", "status");
    el.setAttribute("aria-live", "polite");
    document.body.appendChild(el);
    return el;
  })();

  const updateAriaLabel = () => {
    const current = document.body.dataset.theme || "light";
    const next = current === "dark" ? "light" : "dark";
    if (themeBtn) themeBtn.setAttribute("aria-label", `Switch to ${next} theme`);
  };
  updateAriaLabel();

  if (themeBtn) {
    themeBtn.addEventListener("click", () => {
      const currentTheme = document.body.dataset.theme;
      const newTheme = currentTheme === "dark" ? "light" : "dark";
      document.body.dataset.theme = newTheme;
      document.cookie = `theme=${newTheme}; path=/`;

      if (toast) {
        const bsToast = new bootstrap.Toast(toast);
        toast.querySelector(".toast-body").textContent = `Theme changed to ${newTheme}`;
        bsToast.show();
      }
      document.documentElement.classList.remove("theme-dark", "theme-light");
      document.documentElement.classList.add(`theme-${newTheme}`);
      updateAriaLabel();
      if (status) status.textContent = `Theme changed to ${newTheme}`;
    });
  }
  const langData = window.availableLanguages?.[currentLang];
  if (langData) {
    document.getElementById("currentLangFlag").className = `fi fi-${langData.code}`;
    document.getElementById("currentLangLabel").textContent = langData.label;
  }

  // Language Selector
  const langList = document.getElementById("languageList");
  const currentLang = localStorage.getItem("lang") || "en";

  if (window.availableLanguages && langList) {
    Object.entries(window.availableLanguages).forEach(([code, lang]) => {
      const li = document.createElement("li");
      li.innerHTML = `<a class='dropdown-item' href='#' data-lang='${code}'>
        <span class='fi fi-${lang.code.toLowerCase()} me-2'></span>${lang.label}</a>`;
      langList.appendChild(li);
    });

    // Set current
    document.getElementById("currentLangFlag").className = `fi fi-${window.availableLanguages[currentLang].code}`;
    document.getElementById("currentLangLabel").textContent = window.availableLanguages[currentLang].label;

    langList.querySelectorAll("a[data-lang]").forEach(item => {
      item.addEventListener("click", e => {
        const selectedLang = e.target.closest("a").dataset.lang;
        localStorage.setItem("lang", selectedLang);
        location.reload();
      });
    });
  }
});
