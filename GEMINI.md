# Project Overview: Academi Moodle Theme

The Academi Moodle Theme is a responsive and customizable theme for the Moodle learning management system, developed by LMSACE. Its primary purpose is to provide a modern, professional, and visually appealing interface for Moodle instances, offering extensive configuration options through the Moodle administration panel.

**Version:** 5.1
**Moodle Compatibility:** Moodle 5.0+ (requires Moodle 2024042200 and theme_boost 2025041400)

## Core Technologies

*   **PHP:** Used for backend logic, theme configuration (`config.php`, `lib.php`, `settings.php`), and integration with Moodle's core functionalities.
*   **SCSS (Sass):** For styling the theme, organized into modular files (e.g., `typography.scss`, `header.scss`). It leverages custom variables for color schemes and supports different presets.
*   **JavaScript (AMD):** Client-side interactivity is handled using AMD (Asynchronous Module Definition) with jQuery. The main entry point is `amd/src/theme.js`.
*   **Mustache Templates:** Used for rendering various layout components (e.g., `templates/header.mustache`, `templates/footer.mustache`).
*   **FontAwesome:** Integrated for scalable vector icons.

## Architecture and Key Features

*   **Customizable Layouts:** The `config.php` file defines various page layouts (e.g., `frontpage`, `login`, `course`) with specific regions for blocks.
*   **Extensive Theme Settings:** `settings.php` and its included files (e.g., `settings/general.php`, `settings/homeslider.php`) provide a comprehensive set of options in the Moodle administration interface to customize logos, colors, social media links, jumbotrons, and more.
*   **Dynamic SCSS Generation:** PHP functions in `lib.php` (`theme_academi_process_css`, `theme_academi_get_main_scss_content`, etc.) dynamically inject CSS variables and font paths into the compiled SCSS, allowing for theme-specific customizations.
*   **Responsive Design:** The theme is built with responsiveness in mind, adapting to various screen sizes.
*   **Parent Theme:** Academi is a child theme of Moodle's `boost` theme, inheriting its foundational structure and functionalities.

## Building and Running

As a Moodle theme, it does not typically have a separate build process like a standalone application. Its assets (SCSS, JS) are processed by Moodle itself.

*   **Installation:**
    1.  Unzip the downloaded theme file to get the `academi` folder.
    2.  Copy the `academi` folder into the `theme` directory of your Moodle installation.
    3.  Log in to Moodle as a site administrator.
    4.  Navigate to `Site administration > Notifications`.
    5.  On the 'Plugins check' page, you will see the Academi theme listed. Click "Upgrade Moodle database now".
    6.  After a successful upgrade, you can configure the theme settings.

*   **Setting as Default Theme:**
    1.  Log in as site administrator.
    2.  Go to `Site administration > Appearance > Themes > Theme selector`.
    3.  Click "Change theme" for the "Default" device type.
    4.  Click "Use theme" on "Academi theme".
    5.  Click "Continue".

## Development Conventions

*   **Licensing:** All PHP files include GNU General Public License v3 or later headers.
*   **Modularity:** SCSS files are organized into smaller, imported modules. JavaScript uses AMD for better dependency management.
*   **Configuration:** Theme settings are managed through Moodle's standard `settings.php` and related include files, providing a structured approach to customization.
*   **Templating:** Moodle's standard Mustache templating system is used for rendering HTML components.
*   **Global Functions:** `lib.php` contains a collection of global utility functions and constants used across the theme for dynamic content generation and setting retrieval.
