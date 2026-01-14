# Development Guidelines - Academi Moodle Theme

## Code Quality Standards

### File Headers and Documentation
Every PHP file must include:
- GNU GPL v3 license header block
- File purpose description with @package, @copyright, @author, @license tags
- Copyright: "2015 onwards LMSACE Dev Team (http://www.lmsace.com)"
- License: "GNU GPL v3 or later"

Example:
```php
<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// [Additional GPL text...]

/**
 * Brief description
 *
 * @package    theme_academi
 * @copyright  2015 onwards LMSACE Dev Team (http://www.lmsace.com)
 * @author     LMSACE Dev Team
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
```

### Security and Validation
- **ALWAYS** start PHP files with: `defined('MOODLE_INTERNAL') || die;`
- Use global variables with caution: `global $CFG, $DB, $PAGE, $OUTPUT;`
- Validate all user inputs and settings before use
- Use Moodle's database API ($DB) for all database operations
- Never use direct SQL without proper escaping

### Naming Conventions

#### Functions
- Prefix all functions with `theme_academi_`
- Use lowercase with underscores: `theme_academi_get_setting()`
- Be descriptive: `theme_academi_process_css()`, `theme_academi_get_logo_url()`

#### Classes
- Use namespaces: `namespace theme_academi;`
- Class names in lowercase: `class helper {}`
- Method names use camelCase: `loadBgimages()`, `getHexa()`

#### Constants
- Use UPPERCASE with underscores
- Define at top of lib.php
- Examples: `FRONTPAGEPROMOTEDCOURSE`, `THEMEDEFAULT`, `CAROUSEL`

#### Variables
- Use descriptive lowercase names with underscores
- SCSS variables use kebab-case with $ prefix: `$primary`, `$text-primary`
- CSS custom properties use double-dash prefix: `--academi-primary`

### Code Formatting

#### PHP Style
- Use 4 spaces for indentation (no tabs)
- Opening braces on same line for functions/classes
- Array syntax: Use short array syntax `[]` over `array()`
- String concatenation with spaces: `$var . ' text'`
- Single quotes for simple strings, double quotes when interpolating

#### SCSS/CSS Style
- Use 4 spaces for indentation
- Properties on separate lines
- Semicolons after every property
- Organize by: positioning, box model, typography, visual, misc
- Use variables for colors and repeated values

#### JavaScript Style
- AMD module pattern with define()
- Use 'use strict' directive
- 4 spaces indentation
- Semicolons required
- jQuery wrapped in AMD: `define(['jquery'], function($) { ... });`

## Structural Conventions

### Theme Configuration Pattern
In config.php, use global `$THEME` object:
```php
$THEME->name = 'academi';
$THEME->parents = ['boost'];
$THEME->sheets = ['custom','main','root','dev'];
$THEME->scss = function($theme) {
    return theme_academi_get_main_scss_content($theme);
};
$THEME->prescsscallback = 'theme_academi_get_pre_scss';
$THEME->csspostprocess = 'theme_academi_process_css';
```

### Settings Pattern
Settings organized in modular files under `/settings/`:
```php
// In settings.php
if ($ADMIN->fulltree) {
    $settings = new theme_boost_admin_settingspage_tabs('themesettingacademi', 
        get_string('configtitle', 'theme_academi'));
    include(dirname(__FILE__) . '/settings/general.php');
    include(dirname(__FILE__) . '/settings/homeslider.php');
    // ... more includes
}
```

### Helper Class Pattern
Static helper methods in namespaced class:
```php
namespace theme_academi;

class helper {
    public function load_bgimages($theme, $scss) {
        // Implementation
    }
    
    public function get_hexa($hexa, $opacity) {
        // Convert hex to rgba
    }
}
```

### Settings Retrieval Pattern
Use centralized getter function:
```php
function theme_academi_get_setting($setting, $format = true) {
    static $theme;
    if (empty($theme)) {
        $theme = theme_config::load('academi');
    }
    if (empty($theme->settings->$setting)) {
        return false;
    }
    // Format and return
}
```

## Semantic Patterns

### SCSS Compilation Pipeline
Three-stage SCSS processing:
1. **Pre-SCSS** (`theme_academi_get_pre_scss`): Inject variables from settings
2. **Main SCSS** (`theme_academi_get_main_scss_content`): Load preset files
3. **Post-CSS** (`theme_academi_process_css`): Replace placeholders, inject custom CSS

Example:
```php
function theme_academi_get_pre_scss($theme) {
    $scss = '';
    $helperobj = new theme_academi\helper();
    $scss .= $helperobj->load_bgimages($theme, $scss);
    $scss .= $helperobj->load_additional_scss_settings();
    return $scss;
}
```

### File Serving Pattern
Use `theme_academi_pluginfile()` for serving theme assets:
```php
function theme_academi_pluginfile($course, $cm, $context, $filearea, $args, 
    $forcedownload, array $options = []) {
    static $theme;
    if (empty($theme)) {
        $theme = theme_config::load('academi');
    }
    if ($context->contextlevel == CONTEXT_SYSTEM) {
        if ($filearea === 'logo') {
            return $theme->setting_file_serve('logo', $args, $forcedownload, $options);
        }
        // ... handle other file areas
    }
    send_file_not_found();
}
```

### Database Installation Pattern
In db/install.php, use file storage API:
```php
function xmldb_theme_academi_install() {
    global $CFG;
    
    $fs = get_file_storage();
    $filerecord = new stdClass();
    $filerecord->component = 'theme_academi';
    $filerecord->contextid = context_system::instance()->id;
    $filerecord->userid = get_admin()->id;
    $filerecord->filearea = 'logo';
    $filerecord->filepath = '/';
    $filerecord->itemid = 0;
    $filerecord->filename = 'logo.png';
    $fs->create_file_from_pathname($filerecord, 
        $CFG->dirroot . '/theme/academi/pix/home/logo.png');
}
```

### CSS Variable Injection Pattern
Inject CSS custom properties for runtime theming:
```php
function theme_academi_inject_css_variables($css, $theme) {
    $primary = theme_academi_get_setting('primarycolor') ?: '#2563eb';
    $css_variables = ":root {\n";
    $css_variables .= "  --academi-primary: {$primary};\n";
    $css_variables .= "}\n";
    return $css_variables . $css;
}
```

### Language String Pattern
Support dynamic language strings with `lang:` prefix:
```php
function theme_academi_lang($key='') {
    $pos = strpos($key, 'lang:');
    if ($pos !== false) {
        list($l, $k) = explode(":", $key);
        if (get_string_manager()->string_exists($k, 'theme_academi')) {
            return get_string($k, 'theme_academi');
        }
    }
    return $key;
}
```

## Internal API Usage

### Moodle Theme API
```php
// Load theme configuration
$theme = theme_config::load('academi');

// Get setting file URL
$url = $theme->setting_file_url('logo', 'logo');

// Serve theme files
$theme->setting_file_serve($filearea, $args, $forcedownload, $options);
```

### File Storage API
```php
// Get file storage instance
$fs = get_file_storage();

// Create file from path
$fs->create_file_from_pathname($filerecord, $filepath);

// Get file
$file = $fs->get_file($contextid, $component, $filearea, $itemid, $filepath, $filename);
```

### Database API
```php
global $DB;

// Get records with SQL
$records = $DB->get_records_sql("SELECT id FROM {course} WHERE visible='0'");

// Iterate results
foreach ($records as $row) {
    $ids[] = $row->id;
}
```

### Context API
```php
// Get system context
$context = context_system::instance();
$contextid = $context->id;

// Check context level
if ($context->contextlevel == CONTEXT_SYSTEM) {
    // System-level operations
}
```

### Output API
```php
global $OUTPUT;

// Get logo URL
$logo = $OUTPUT->get_compact_logo_url();

// Render from template
$output = $OUTPUT->render_from_template('theme_academi/header', $data);
```

### Page API
```php
global $PAGE;

// Require JavaScript
$PAGE->requires->js_call_amd('theme_academi/theme', 'init');

// Get theme setting file URL
$url = $PAGE->theme->setting_file_url('logo', 'logo');
```

## Frequently Used Code Idioms

### Static Theme Loading
```php
static $theme;
if (empty($theme)) {
    $theme = theme_config::load('academi');
}
```

### Safe Setting Retrieval with Fallback
```php
$primary = theme_academi_get_setting('primarycolor') ?: '#2563eb';
```

### Ternary for Optional Values
```php
$value = (!empty($url)) ? "url('".$url."')" : '';
```

### Array Map for Bulk Operations
```php
array_map(function($target) use (&$scss, $value) {
    $scss .= '$' . $target . ': ' . $value . ";\n";
}, (array) $targets);
```

### String Replacement Pattern
```php
$tag = '[[setting:customcss]]';
$css = str_replace($tag, $customcss, $css);
```

### RGBA Color Conversion
```php
public function get_hexa($hexa, $opacity) {
    $hexa = trim($hexa, "#");
    if (strlen($hexa) == 6) {
        $r = hexdec(substr($hexa, 0, 2));
        $g = hexdec(substr($hexa, 2, 2));
        $b = hexdec(substr($hexa, 4, 2));
        $a = (!empty($opacity)) ? $opacity : 0;
        return "rgba(".$r.", ".$g.", ".$b.", ".$a.")";
    }
    return "";
}
```

## Popular Annotations

### PHPDoc Function Documentation
```php
/**
 * Brief description of function purpose.
 *
 * @param string $setting Setting name to retrieve
 * @param bool $format Whether to format the output
 * @return string|bool Setting value or false if not found
 */
function theme_academi_get_setting($setting, $format = true) {
    // Implementation
}
```

### PHPDoc Class Documentation
```php
/**
 * Helper class for additional function on the academi theme.
 */
class helper {
    /**
     * Load all configurable image url into the scss content.
     *
     * @param string $theme theme data.
     * @param string $scss scss data.
     * @return string $scss return the scss value.
     */
    public function load_bgimages($theme, $scss) {
        // Implementation
    }
}
```

### Inline Comments
```php
// Get slide image or fallback to default.
$slideimage = '';

// Prepend variables first.
foreach ($configurable as $configkey => $targets) {
    // Process each target
}
```

## Best Practices

### Performance
- Use static variables to cache theme config: `static $theme;`
- Minimize database queries in loops
- Leverage Moodle's caching system
- Optimize SCSS compilation with modular structure

### Maintainability
- Keep functions focused and single-purpose
- Use helper classes for reusable logic
- Modularize settings into separate files
- Document complex logic with comments

### Compatibility
- Always extend parent theme (Boost)
- Use Moodle APIs instead of direct access
- Test with theme designer mode enabled
- Support RTL languages with proper CSS

### Security
- Validate and sanitize all inputs
- Use format_text() for user content
- Escape output appropriately
- Follow Moodle security guidelines

### Accessibility
- Use semantic HTML5 elements
- Include ARIA labels where needed
- Ensure keyboard navigation works
- Test with screen readers

### Internationalization
- Use get_string() for all user-facing text
- Support lang: prefix for dynamic strings
- Provide translations in /lang/ directory
- Test with multiple languages including RTL
