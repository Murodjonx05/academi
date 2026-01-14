<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.

/**
 * Navbar data provider - ADD THIS to classes/output/core_renderer.php
 *
 * @package    theme_academi
 * @copyright  2025 LMSACE Dev Team
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Get navbar data for modern navbar template
 * 
 * ADD THIS METHOD to your core_renderer class
 */
public function navbar_modern_data() {
    global $USER, $PAGE, $CFG;
    
    $data = [
        'logo' => theme_academi_get_logo_url('header'),
        'sitename' => $this->page->heading,
        'userloggedin' => isloggedin() && !isguestuser(),
        'userfullname' => fullname($USER),
        'useravatar' => $this->user_picture($USER, ['size' => 35, 'link' => false]),
    ];
    
    // Navigation items
    $navitems = [];
    
    // Home
    $navitems[] = [
        'text' => get_string('home'),
        'url' => $CFG->wwwroot,
        'isactive' => $PAGE->pagetype === 'site-index',
        'isdropdown' => false,
    ];
    
    // Dashboard (if logged in)
    if ($data['userloggedin']) {
        $navitems[] = [
            'text' => get_string('myhome'),
            'url' => $CFG->wwwroot . '/my/',
            'isactive' => $PAGE->pagetype === 'my-index',
            'isdropdown' => false,
        ];
    }
    
    // Courses dropdown
    $navitems[] = [
        'text' => get_string('courses'),
        'url' => '#',
        'isactive' => false,
        'isdropdown' => true,
        'children' => [
            [
                'text' => get_string('fulllistofcourses'),
                'url' => $CFG->wwwroot . '/course/',
            ],
            [
                'text' => get_string('mycourses'),
                'url' => $CFG->wwwroot . '/my/courses.php',
            ],
        ],
    ];
    
    $data['navitems'] = $navitems;
    
    // User menu items
    if ($data['userloggedin']) {
        $usermenu = [];
        
        $usermenu[] = [
            'text' => get_string('profile'),
            'url' => $CFG->wwwroot . '/user/profile.php',
        ];
        
        $usermenu[] = [
            'text' => get_string('preferences'),
            'url' => $CFG->wwwroot . '/user/preferences.php',
        ];
        
        $usermenu[] = [
            'text' => get_string('logout'),
            'url' => $CFG->wwwroot . '/login/logout.php?sesskey=' . sesskey(),
        ];
        
        $data['usermenu'] = $usermenu;
    }
    
    return $data;
}

/**
 * Render modern navbar
 * 
 * USAGE in layout files:
 * echo $OUTPUT->render_modern_navbar();
 */
public function render_modern_navbar() {
    $data = $this->navbar_modern_data();
    return $this->render_from_template('theme_academi/navbar-modern', $data);
}
