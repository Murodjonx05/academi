# Project Structure - Academi Moodle Theme

## Directory Organization

### Root Configuration Files
- **config.php**: Theme configuration defining layouts, regions, parent theme, and rendering settings
- **version.php**: Plugin version, maturity, dependencies, and Moodle requirements
- **settings.php**: Admin settings page configuration with modular settings includes
- **lib.php**: Core theme functions for SCSS processing, CSS manipulation, and helper utilities
- **thirdpartylibs.xml**: Third-party library declarations and licenses

### Core Directories

#### `/classes/` - PHP Classes
Object-oriented PHP components following Moodle's class structure:
- **output/**: Renderer classes extending Moodle core renderers
  - `core_renderer.php`: Main theme renderer overrides
  - `course_card.php`: Custom course card rendering
  - `core/`: Core output component overrides
- **privacy/**: GDPR compliance provider
  - `provider.php`: Privacy API implementation
- **helper.php**: Theme helper utilities and common functions
- **academi_blocks.php**: Custom block handling and rendering

#### `/templates/` - Mustache Templates
HTML templates using Mustache syntax for rendering:
- **Layout Templates**: `frontpage.mustache`, `drawers.mustache`, `columns1.mustache`, `columns2.mustache`, `login.mustache`, `maintenance.mustache`
- **Component Templates**: `header.mustache`, `footer.mustache`, `navbar.mustache`, `slideshow.mustache`
- **Block Templates**: `academi_blocks.mustache`, `course_blocks.mustache`, `course_card.mustache`
- **Menu Templates**: `custom_menu_item.mustache`
- **Override Templates**: `block_myoverview/view-cards.mustache`

#### `/layout/` - PHP Layout Files
Legacy PHP-based layout files (used by config.php):
- **Main Layouts**: `frontpage.php`, `drawers.php`, `columns1.php`, `columns2.php`, `login.php`, `maintenance.php`, `embedded.php`, `secure.php`
- **includes/**: Shared layout components
  - `header.php`: Header rendering logic
  - `footer.php`: Footer rendering logic
  - `homeslider.php`: Slider component logic
  - `layoutdata.php`: Layout data preparation
  - `themedata.php`: Theme-specific data preparation

#### `/scss/` - Stylesheets (SASS/SCSS)
Modular SCSS architecture:
- **Main Files**: `theme.scss` (entry point), `standard.scss`, `includes.scss`
- **Component Styles**: `header.scss`, `footer.scss`, `frontpage.scss`, `carousel.scss`, `blocks.scss`, `course.scss`, `login.scss`
- **Foundation**: `colors.scss`, `typography.scss`, `paths.scss`
- **preset/**: Theme variations
  - `default.scss`, `eguru.scss`, `enlightlite.scss`, `klass.scss`, `plain.scss`
- **components/**: Reusable component styles
  - `_course-card.scss`

#### `/style/` - Compiled CSS
Pre-compiled CSS files for direct inclusion:
- `custom.css`: Custom styling overrides
- `main.css`: Main compiled styles
- `root.css`: CSS custom properties and root variables
- `dev.css`: Development-specific styles
- `slick.css`: Slick carousel library styles
- `animate.css`: Animation library

#### `/amd/src/` - JavaScript Modules (AMD)
RequireJS/AMD JavaScript modules:
- **Slider Modules**: `homeslider.js`, `custom-homeslider.js`, `slick-homeslider.js`, `jquery.sudoSlider.js`, `slick.js`
- **Page Modules**: `frontpage.js`, `theme.js`
- Backup files with `.bak` and `.display-version` extensions

#### `/settings/` - Admin Settings Modules
Modular admin configuration pages:
- `general.php`: General theme settings
- `homeslider.php`: Slider configuration
- `promotedcourse.php`: Featured courses settings
- `sitefeatures.php`: Site features configuration
- `marketingspot.php`: Marketing content settings
- `jumbotron.php`: Hero section settings
- `footer.php`: Footer configuration

#### `/lang/` - Language Strings
Internationalization files:
- `en/theme_academi.php`: English (default)
- `ar/theme_academi.php`: Arabic
- `ru/theme_academi.php`: Russian

#### `/pix/` - Images and Icons
Theme graphics and visual assets:
- **home/**: Homepage images (`logo.png`, `footerlogo.png`, `slide1.jpg`, `mspotmedia.png`)
- **theme_t/**: Theme-specific icons (collapsed/expanded states, RTL variants)
- **svg_grey/t/**: Grayscale SVG icons
- **Root Images**: `favicon.ico`, `screenshot.jpg`, `no-image.jpg`, `no-user.jpg`, carousel controls

#### `/fonts/` - Web Fonts
Custom typography with multiple formats (EOT, SVG, TTF, WOFF, WOFF2):
- **alegreyasans-bold/**: Bold weight variant
- **alegreyasans-italic/**: Italic variant
- **alegreyasans-light/**: Light weight variant
- **alegreyasans-medium/**: Medium weight variant
- **alegreyasans-regular/**: Regular weight variant
- **arvo/**: Arvo font family

#### `/db/` - Database Definitions
Moodle database schema and installation:
- `install.php`: Initial database setup and default settings

#### `/.github/workflows/` - CI/CD
Continuous integration configuration:
- `moodle-ci.yml`: Automated testing workflow for Moodle plugins

## Architectural Patterns

### Theme Inheritance
- **Parent Theme**: Extends `theme_boost` (Moodle's default Bootstrap 4 theme)
- **Override Strategy**: Selective overrides of renderers, templates, and styles
- **Fallback Chain**: academi → boost → core

### Rendering Architecture
- **Renderer Factory**: Uses `theme_overridden_renderer_factory` for custom renderer loading
- **Template System**: Mustache templates with data preparation in PHP classes
- **Output Components**: Structured output classes in `/classes/output/`

### Styling Strategy
- **SCSS Compilation**: Dynamic SCSS compilation with callbacks:
  - `theme_academi_get_main_scss_content()`: Main SCSS content
  - `theme_academi_get_pre_scss()`: Pre-SCSS variables
  - `theme_academi_get_extra_scss()`: Additional SCSS from settings
- **CSS Post-processing**: `theme_academi_process_css()` for dynamic CSS manipulation
- **Preset System**: Multiple visual themes via SCSS presets

### JavaScript Architecture
- **AMD Modules**: RequireJS-based module loading
- **jQuery Integration**: jQuery plugins (sudoSlider, Slick) wrapped as AMD modules
- **Progressive Enhancement**: JavaScript enhances but doesn't require functionality

### Layout System
- **Flexible Layouts**: 10+ layout configurations for different page types
- **Block Regions**: Configurable block regions (primarily `side-pre`)
- **Responsive Breakpoints**: Bootstrap grid system with custom breakpoints
- **RTL Support**: Right-to-left layout manipulation for Arabic and other RTL languages

## Component Relationships

### Data Flow
1. **Configuration** (config.php) → defines layouts and rendering strategy
2. **Settings** (settings.php + /settings/) → admin configures theme options
3. **Renderers** (/classes/output/) → prepare data for templates
4. **Templates** (/templates/) → render HTML with Mustache
5. **Styles** (/scss/) → compiled to CSS and applied
6. **JavaScript** (/amd/src/) → enhances interactivity

### Key Integration Points
- **Moodle Core**: Extends core renderers and output components
- **Boost Theme**: Inherits base functionality and styling
- **Block System**: Custom block rendering and positioning
- **Course System**: Custom course card display and navigation
- **User Interface**: Header, footer, navigation, and content areas

## File Naming Conventions
- **PHP Classes**: PSR-4 autoloading, lowercase with underscores
- **Templates**: Lowercase with hyphens, `.mustache` extension
- **SCSS**: Lowercase with hyphens, partials prefixed with `_`
- **JavaScript**: Lowercase with hyphens, AMD module format
- **Images**: Lowercase with hyphens, descriptive names
