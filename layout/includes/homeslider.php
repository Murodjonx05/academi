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
 * Slideshow layout
 * @package    theme_academi
 * @copyright  2015 onwards LMSACE Dev Team (http://www.lmsace.com)
 * @author    LMSACE Dev Team
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot."/theme/academi/classes/helper.php");

/**
 * General config setting for the home page slider.
 *
 * @return $general general data settings.
 */
function general() {
    $general = [];
    $general['status'] = get_config('theme_academi', 'toggleslideshow');
    $interval = intval(get_config('theme_academi', 'slideinterval'));
    $autoslideshow = get_config('theme_academi', 'autoslideshow');
    $general['interval'] = (!empty($interval)) ? $interval : 3000;
    $general['overlay'] = get_config('theme_academi', 'slideOverlay');
    if ($autoslideshow == 1) {
        $general["autoplay"] = 'true';
    } else {
        $general["autoplay"] = 'false';
    }
    return $general;
}

/**
 * Home page slider data.
 *
 * @return array $data data for home pageslider.
 */
function homeslider() {
    global $PAGE;
    $data = [];
    $data['numofslide'] = get_config('theme_academi', 'numberofslides');
    $helperobj = new theme_academi\helper();
    (int) $slider = 0;
    for ($s = 1; $s <= $data['numofslide']; $s++) {
        $slide = [];
        $slide['slidestatus'] = get_config('theme_academi', 'slide' . $s .'status');
        $slide['slideimg'] = $helperobj->render_slideimg($s, 'slide' . $s . 'image');
        $slide['slidecontentstatus'] = get_config('theme_academi', 'slide' . $s .'contentstatus');
        $slide['caption'] = get_string('slide' . $s . 'caption', 'theme_academi');
        $slide['desc'] = format_text(get_config('theme_academi', 'slide' . $s . 'desc'), FORMAT_HTML, ['trusted' => true, 'noclean' => true]);
        $slide['btntxt'] = get_string('slide' . $s . 'btntext', 'theme_academi');
        $slide['btnlink'] = get_config('theme_academi', 'slide' . $s . 'btnurl');
        $btntarget = get_config('theme_academi', 'slide' . $s . 'btntarget');
        $slide['btntarget'] = ($btntarget == 1) ? '_blank' : '_self';
        $contwidth = get_config('theme_academi', 'slide' . $s . 'contFullwidth');

        if ((!empty($slide['slidestatus'])) && (!empty($slide['slideimg']))) {
            $slider = $slider + 1;
        }

        if ((empty($slide['caption'])) && (empty($slide['desc'])) && (empty($slide['btntxt']))) {
            $slide['slidecontentstatus'] = false;
        }

        if ($contwidth == "auto") {
            $contwidth = "auto";
        } else {
            $contwidth = intval($contwidth);
            if ($contwidth > '100' ) {
                $contwidth = '100%';
            } else if ($contwidth <= 0) {
                $contwidth = "auto";
            } else {
                $contwidth = $contwidth.'%';
            }
        }
        $slide['contentwidth'] = $contwidth;
        $slide['contentAnimation'] = "ScrollRight";
        $slide['contentAclass'] = "animated ". $slide['contentAnimation'];
        $postition = get_config('theme_academi', 'slide' . $s . 'contentPosition');
        $slide['contentpostion'] = $postition;
        $slide['contentClass'] = (!empty($postition)) ? 'content-'.$postition : 'content-centerRight';
        if ($slide['slideimg']) {
            $data['slides'][] = $slide;
        }
    }
    $status = get_config('theme_academi', 'toggleslideshow');
    $data['sliderblockstatus'] = ($slider == 0) ? false : $status;
    if (!$data['sliderblockstatus']) {
        $data['isblockempty'] = is_siteadmin() || $PAGE->user_is_editing() ? true : false;
    }
    return $data;
}

$sliderconfig = [];
$slidergeneral = general();
$sliderconfig += $slidergeneral;
$sliderconfig += homeslider();
// Determine which slider to use based on theme settings
$usesimplecarousel = get_config('theme_academi', 'usesimplecarousel');
$useslickslider = get_config('theme_academi', 'useslickslider');

if ($useslickslider) {
    $PAGE->requires->js_call_amd('theme_academi/slick-homeslider', 'init', ['selector' => '#homepage-carousel', 'options' => $slidergeneral]);
} else if ($usesimplecarousel) {
    $PAGE->requires->js_call_amd('theme_academi/custom-homeslider', 'init', ['selector' => '#homepage-carousel', 'options' => $slidergeneral]);
} else {
    $PAGE->requires->js_call_amd('theme_academi/homeslider', 'init', ['selector' => '#homepage-carousel', 'options' => $slidergeneral]);
}
$PAGE->requires->css("/theme/academi/style/animate.css");
