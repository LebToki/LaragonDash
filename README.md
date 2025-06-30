# ![LaragonDash Logo](assets/favicon/favicon-32x32.png) **LaragonDash**

> 🚀 A modern, modular, multilingual dashboard for Laragon 6+ - 7+ and 8+  
> Built for developers who want more clarity, control, and customization.

---

## ✨ What is LaragonDash?

**LaragonDash** is a powerful evolution of the original [Laragon Dashboard](https://github.com/LebToki/Laragon-Dashboard) — redesigned for the future. It keeps everything developers loved, but adds:

- ✅ **Modular system** for plug-and-play tools
- ✅ **Live language switching** (RTL/LTR support)
- ✅ **Modern UI/UX with theming**
- ✅ **No database required**

```txt
Compatible with Laragon 6+ - 7+ and 8+ • PHP 7+ - 8+ • Apache/Nginx • Lightweight & open source
```

---

> ⚙️ LaragonDash is the new primary branch for updates.  
> The original dashboard will remain available for legacy users until end of 2025.

---
# ![LaragonDash Projects](assets/images/LaragonDash-Screenshot-Projects-Dark.png)

# ![LaragonDash Projects](assets/images/LaragonDash-Screenshot-Projects-Light.png)

# ![LaragonDash SendMail Client](assets/images/LaragonDash-Screenshot-MailReader-Light.png)

# ![LaragonDash Languages Editor](assets/images/LaragonDash-Screenshot-LanguageEditor-details-Light.png)

# ![LaragonDash Bcrypt Generator](assets/images/LaragonDash-Screenshot-Bcrypt-Dark.png)


---

## 🧩 Modular Architecture

All modules are located in `/modules/`. Drop in your own tools or clone from the growing library:

| Module         | Description                         |
|----------------|-------------------------------------|
| 🔍 **Search**    | Live filter across project tiles    |
| 📬 **Email**     | Read/send/delete HTML & TXT mails   |
| 🧪 **Bcrypt**    | Encrypt + verify hashes securely    |
| ❤️ **Vitals**    | CPU, memory, disk & uptime monitor  |
| ⚙️ **Settings**  | Customize ignored dirs, default lang |
| 🗂 **Projects**  | Auto-detect WordPress, Laravel, etc |

```php
LaragonDash/
├── assets/
├── includes/
├── modules/
│   ├── bcrypt/
│   ├── email/
│   ├── search/
│   ├── settings/
│   └── vitals/
├── index.php
└── README.md
```

**Quick Setup Guide**

Replace the Root Folder
Simply overwrite your existing root directory with the one from the repo.
or even create a project (directory too) depends on your preference as I have introduced a variable that reads the root from a config file ProjectPath you simply need to update it to match the location of your www folder

**Configure settings.php**

Locate /Includes/config/settings.php and adjust the following to match your setup:

```
├── Includes/config/settings.php

```

```
<?php
    return [
        'SSLEnabled' => 1,                        // Enable or disable SSL
        'Port'       => 443,                      // Your local server port
        // Update to your Laragon www path
        'ProjectPath' => 'D:/laragon8/www/',   
        // Exclude unwanted files and directories
        'IgnoreDirs' => ['.', '..', 'templates', 'app', 'includes', 'modules', '.idea', 'logs', 'vendor', 'assets'], 
        
    ];
?>
```

## That’s it! You’re ready to go. 🚀

---

## 🌐 Language Support

> All translations reside in `/includes/languages/`  
> Add your own `xx.json` to contribute!

| Code   | Language             | Flag |
|--------|----------------------|------|
| `en`   | English              | 🇬🇧   |
| `fr`   | French               | 🇫🇷   |
| `es`   | Spanish              | 🇪🇸   |
| `de`   | German               | 🇩🇪   |
| `pt`   | Portuguese           | 🇵🇹   |
| `pt-BR`| Brazilian Portuguese | 🇧🇷   |
| `ar`   | Arabic (RTL)         | 🇸🇦   |
| `ur`   | Urdu (RTL)           | 🇵🇰   |
| `hi`   | Hindi                | 🇮🇳   |
| `tl`   | Tagalog              | 🇵🇭   |
| `id`   | Indonesian           | 🇮🇩   |
| `tr`   | Turkish              | 🇹🇷   |
| `ru`   | Russian              | 🇷🇺   |
| `ja`   | Japanese             | 🇯🇵   |
| `ko`   | Korean               | 🇰🇷   |
| `vi`   | Vietnamese           | 🇻🇳   |
| `zh-CN`| Simplified Chinese   | 🇨🇳   |

---

## 🗓️ Changelog Summary


<details open> <summary><strong>v1.2 – June 19, 2025</strong></summary>
🔄 Language auto-detection + RTL direction

🌍 Flag dropdown with live translations

🛠 Server vitals (CPU/RAM/Disk) module

⏳ PHP + Apache version inspection

⚙️ Modular bootstrap with dynamic includes

🧪 WordPress core version + update checker

✍️ Language Editor improvements:

Preserves route on language file selection

Live JSON validation with grouped nesting

Dynamic add/edit for keys & groups

Secure file access with path validation

💥 Fix: t() function type safety for fallback strings

🌓 Dark Mode fixes for email viewer and editor contrast

</details>

<details>
<summary><strong>v1.1 – June 2025</strong></summary>

- ✨ Mail reader UI with toggle/delete
- 🔒 Bcrypt hasher with dual verify mode
- 🌓 Light/Dark theme toggle with memory
- 🔍 Search tile filtering
- ⚙️ Settings with directory ignore control
</details>

<details>
<summary><strong>v1.0 – Initial Release</strong></summary>

- 📦 Modular MVC structure
- 🎨 Modern dashboard layout
- 📂 Auto-detect WordPress/Laravel/etc.
- 🌐 Multilingual foundation
</details>

---

## 💻 How to Add a New Language

Just drop a new JSON file inside `/includes/languages/` named `xx.json`.
Here’s a quick example for `xx.json`:

```json
{
  "navigation": {
    "dashboard": "Dashboard",
    "search": "Search",
    "projects": "Projects"
  },
  "buttons": {
    "logout": "Logout",
    "save": "Save"
  }
}
```

✅ It will be picked up automatically in the language dropdown!  
✅ Arabic/Urdu will trigger RTL mode with `Tajawal` or `Noto Nastaliq Urdu`.

## 📝 Language Editor
The language-editor.php tool inside the settings module allows real-time editing of translation JSON files.

✅ Add/remove keys and groups
✅ Safe JSON validation
✅ Supports all loaded languages in /includes/languages/
✅ RTL-aware input and dynamic reloading

---

## 🛠 Developers

Want to contribute your own module?

Just create:

```bash
/modules/yourtool/
├── index.php
├── style.css
├── script.js

```

Optional: include a `lang.json` file inside your module to localize text.  
Use `data-i18n="module.key"` for inline translations.

---

## 🌍 Get Involved

⭐ Star the repo  
🛠 Submit your own module  
🧠 Join discussions  
🗣 Help translate

---
**Found a Bug? Let Me Know!**
If you run into any issues, please report them on the repo. 
Your feedback helps improve LaragonDash for everyone in the community.

Happy coding, and thanks for being part of this! 💻✨
---

## 📄 License

This project is licensed under the **Creative Commons Attribution 4.0** license (CC BY 4.0).  
Made with 💙 by [Tarek Tarabichi](https://2tinteractive.com)

Part of the toolset: **LaragonDash**, **CRMHub**, **bMessenger**, **SignOS**
