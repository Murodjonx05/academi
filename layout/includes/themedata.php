<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Theme layout data
 * @package    theme_academi
 * @copyright  2015 onwards LMSACE Dev Team (http://www.lmsace.com)
 * @author    LMSACE Dev Team
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();
use theme_academi\output\academi_layout_helper;
require_once($CFG->dirroot.'/theme/academi/classes/output/academi_layout_helper.php');
require_once($CFG->dirroot.'/theme/academi/lib.php');

$logourl = academi_layout_helper::get_logo_url('header');
$phoneno = get_config('theme_academi', 'phoneno');
$emailid = get_config('theme_academi', 'emailid');
$themestyleheader = get_config('theme_academi', 'themestyleheader');
$navstyle = get_config('theme_academi', 'navstyle');

switch ($navstyle) {
    case LOGO:
        $showlogo = true;
        $showsitename = false;
        break;
    case SITENAME:
        $showsitename = true;
        $showlogo = false;
        break;
    case LOGOANDSITENAME:
        $showsitename = true;
        $showlogo = true;
        break;
}

$custommenu = $OUTPUT->custom_menu();
if ($custommenu == "") {
    $navbarclass = "navbar-toggler d-lg-none nocontent-navbar";
} else {
    $navbarclass = "navbar-toggler d-lg-none";
}

$templatecontext = [
    "logourl" => $logourl,
    "navbarclass" => $navbarclass,
    "themestyleheader" => $themestyleheader,
    'showsitename' => $showsitename,
    'showlogo' => $showlogo,
];
$templatecontext += academi_layout_helper::footer();
