# Technology Stack - Academi Moodle Theme

## Programming Languages

### PHP
- **Version Required**: PHP 7.4+ (Moodle 5.0 requirement)
- **Usage**: Server-side rendering, theme logic, settings, database operations
- **Standards**: Moodle coding standards, PSR-4 autoloading for classes
- **Key Features Used**:
  - Object-oriented programming (classes, inheritance)
  - Namespaces for class organization
  - Type declarations and return types
  - Anonymous functions and closures

### JavaScript
- **Module System**: AMD (Asynchronous Module Definition) via RequireJS
- **Version**: ES5+ compatible
- **Libraries**:
  - jQuery (bundled with Moodle)
  - Slick Carousel
  - sudoSlider
- **Usage**: Interactive components, sliders, dynamic UI enhancements

### CSS/SCSS
- **Preprocessor**: SASS/SCSS
- **Version**: SCSS 3.x compatible
- **Framework**: Bootstrap 4 (via Boost parent theme)
- **Architecture**: Modular SCSS with component-based organization
- **Features**: Variables, mixins, nesting, imports, functions

### HTML
- **Template Engine**: Mustache
- **Version**: Mustache 2.x
- **Standards**: HTML5 semantic markup
- **Accessibility**: ARIA labels and semantic structure

## Framework and Dependencies

### Moodle LMS
- **Required Version**: Moodle 5.0+ (2024042200)
- **Parent Theme**: theme_boost (2025041400)
- **APIs Used**:
  - Theme API
  - Output API
  - Renderer API
  - Settings API
  - Privacy API (GDPR compliance)
  - Block API
  - Navigation API

### Bootstrap
- **Version**: Bootstrap 4.x (inherited from Boost)
- **Components Used**:
  - Grid system
  - Utilities
  - Components (cards, navbars, buttons)
  - Responsive breakpoints

### FontAwesome
- **Icon System**: FontAwesome (via Moodle core)
- **Usage**: UI icons throughout the theme
- **Integration**: `\core\output\icon_system::FONTAWESOME`

### Third-Party JavaScript Libraries
- **Slick Carousel**: Modern responsive carousel/slider
- **sudoSlider**: Alternative jQuery slider plugin
- **jQuery**: Core JavaScript library (Moodle-provided)

## Build System

### SCSS Compilation
- **Compiler**: Moodle's built-in SCSS compiler (scssphp)
- **Entry Point**: `$THEME->scss` callback function
- **Process**:
  1. Pre-SCSS variables (`theme_academi_get_pre_scss()`)
  2. Main SCSS content (`theme_academi_get_main_scss_content()`)
  3. Extra SCSS from settings (`theme_academi_get_extra_scss()`)
  4. CSS post-processing (`theme_academi_process_css()`)
- **Output**: Compiled CSS served by Moodle

### JavaScript Build
- **Module Format**: AMD (RequireJS)
- **Location**: `/amd/src/` (source), `/amd/build/` (compiled - auto-generated)
- **Compilation**: Moodle's Grunt-based build system
- **Command**: `grunt amd` (when developing JavaScript)

### No External Build Tools Required
- Theme uses Moodle's built-in compilation
- No npm, webpack, or gulp configuration needed
- SCSS and JS compiled on-demand by Moodle

## Development Environment

### Required Software
- **Web Server**: Apache 2.4+ or Nginx
- **PHP**: 7.4+ with required extensions (see Moodle docs)
- **Database**: MySQL 5.7+, MariaDB 10.2+, or PostgreSQL 10+
- **Moodle**: Version 5.0 or higher

### Optional Development Tools
- **Node.js**: For Grunt tasks (JavaScript minification)
- **Grunt**: For AMD module compilation
- **Git**: Version control
- **IDE**: PhpStorm, VS Code, or similar with PHP support

### Moodle Development Mode
Enable developer mode for theme development:
```php
// In config.php
$CFG->debug = (E_ALL | E_STRICT);
$CFG->debugdisplay = 1;
$CFG->cachejs = false;
$CFG->themedesignermode = true;
```

## Development Commands

### Theme Installation
```bash
# Copy theme to Moodle themes directory
cp -r academi /path/to/moodle/theme/

# Navigate to Moodle admin page to trigger installation
# Visit: http://yourmoodle.com/admin/
```

### Cache Management
```bash
# Purge all caches (via Moodle admin interface)
# Site administration > Development > Purge all caches

# Or via CLI
php admin/cli/purge_caches.php
```

### JavaScript Development
```bash
# Compile AMD modules (if Node.js and Grunt installed)
cd /path/to/moodle
npm install
grunt amd --root=theme/academi
```

### SCSS Development
- SCSS automatically recompiled when theme designer mode enabled
- Manual purge: Site administration > Development > Purge all caches
- Changes to SCSS files require cache purge to see updates

### Testing
```bash
# Run Moodle PHPUnit tests
php admin/tool/phpunit/cli/init.php
vendor/bin/phpunit --testsuite theme_academi_testsuite

# Run Behat tests (if configured)
php admin/tool/behat/cli/init.php
vendor/bin/behat --config /path/to/behatdata/behatrun/behat.yml
```

## File Permissions
```bash
# Recommended permissions for theme files
find theme/academi -type f -exec chmod 644 {} \;
find theme/academi -type d -exec chmod 755 {} \;
```

## Browser Support
- **Modern Browsers**: Latest versions of Chrome, Firefox, Safari, Edge
- **Legacy Support**: IE9+ (limited support)
- **Mobile**: iOS Safari, Chrome Mobile, Samsung Internet
- **Responsive**: All viewport sizes from 320px to 4K displays

## Performance Considerations
- **CSS Minification**: Automatic via Moodle
- **JavaScript Minification**: Via Grunt AMD build
- **Image Optimization**: Manual (use optimized images in `/pix/`)
- **Caching**: Moodle's built-in caching system
- **CDN Support**: Compatible with Moodle CDN configuration

## Version Control
- **Repository Structure**: Standard Moodle plugin structure
- **Branching**: Follow Moodle version branches (MOODLE_50_STABLE, etc.)
- **Releases**: Tagged releases matching Moodle versions
- **CI/CD**: GitHub Actions workflow (`.github/workflows/moodle-ci.yml`)

## Debugging Tools
- **Moodle Debugger**: Built-in PHP error display
- **Browser DevTools**: Chrome/Firefox developer tools
- **Mustache Debugging**: Enable template debugging in Moodle
- **SCSS Source Maps**: Not available (Moodle limitation)

## Configuration Files
- **config.php**: Theme configuration (layouts, regions, callbacks)
- **version.php**: Version and dependency information
- **settings.php**: Admin settings page structure
- **thirdpartylibs.xml**: Third-party library declarations
- **lib.php**: Theme functions and callbacks
