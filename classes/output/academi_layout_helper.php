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
 * Helper class for theme Academi layout data.
 *
 * @package    theme_academi
 * @copyright  2015 onwards LMSACE Dev Team (http://www.lmsace.com)
 * @author     LMSACE Dev Team
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_academi\output;

defined('MOODLE_INTERNAL') || die();

class academi_layout_helper {

    public static function get_logo_url($type = 'header') {
        global $OUTPUT;
        static $theme;
        if (empty($theme)) {
            $theme = \theme_config::load('academi');
        }
        if ($type == 'header') {
            $logo = $theme->setting_file_url('logo', 'logo');
            $logo = empty($logo) ? $OUTPUT->get_compact_logo_url() : $logo;
        } else if ($type == 'footer') {
            $logo = $theme->setting_file_url('footerlogo', 'footerlogo');
            $logo = empty($logo) ? '' : $logo;
        }
        return $logo;
    }

    /**
     * Return the social media content for the theme academi footer.
     *
     * @return array $social.
     */
    public static function socialmedia() {
        global $OUTPUT, $CFG, $PAGE; // Added global $PAGE for consistency, although not strictly needed here.
        $numofsocialmedia = get_config('theme_academi', 'numofsocialmedia');
        $social = [];
        for ($sm = 1; $sm <= $numofsocialmedia; $sm++) {
            $status = get_config('theme_academi', 'socialmedia'.$sm.'_status');
            $icon = get_config('theme_academi', 'socialmedia'.$sm.'_icon');
            $sicon = (!empty($icon)) ? $icon : '';
            $url = get_config('theme_academi', 'socialmedia'.$sm.'_url');
            $iconcolorval = get_config('theme_academi', 'socialmedia'.$sm.'_iconcolor');
            $iconcolor = (!empty($iconcolorval)) ? $iconcolorval : '';
            $socialmedia[] = [
                'socialstatus' => $status,
                'sicon' => $sicon,
                'surl' => $url,
                'siconcolor' => $iconcolor,
                'sno' => $sm,
            ];
            $social['socialmedia'] = $socialmedia;
        }
        return $social;
    }

    /**
     * Manage the footer content for the theme academi footer.
     *
     * @return array $templatecontext footer template contents.
     */
    public static function footer() {
        global $OUTPUT, $CFG, $USER, $PAGE;
        $footerlogourl = self::get_logo_url('footer'); // Keeping original for now, will replace with class method if moved.
        $footlogostatus = get_config('theme_academi', 'footlogostatus');
        $footerbgimg = $PAGE->theme->setting_file_url('footerbgimg', 'footerbgimg');
        $footerbgimgclass = (!empty($footerbgimg)) ? 'footer-image' : '';
        $footnote = format_text(get_config('theme_academi', 'footnote'), FORMAT_HTML, ['trusted' => true, 'noclean' => true]);
        $infolink = $OUTPUT->footer_infolinks();
        $address = get_config('theme_academi', 'address');
        $emailid = get_config('theme_academi', 'emailid');
        $phoneno = get_config('theme_academi', 'phoneno');
        $copyrightfooter = format_text(get_config('theme_academi', 'copyright_footer'), FORMAT_HTML, ['trusted' => true, 'noclean' => true]);
        $fstatus1 = get_config('theme_academi', 'footerb1_status');
        $fstatus2 = get_config('theme_academi', 'footerb2_status');
        $fstatus3 = get_config('theme_academi', 'footerb3_status');
        $fstatus4 = get_config('theme_academi', 'footerb4_status');

        $ftitle1 = get_config('theme_academi', 'footerbtitle1');
        $ftitle2 = get_config('theme_academi', 'footerbtitle2');
        $ftitle3 = get_config('theme_academi', 'footerbtitle3');
        $ftitle4 = get_config('theme_academi', 'footerbtitle4');

        $phone = get_string('phone', 'theme_academi');
        $email = get_string('emailid', 'theme_academi');

        $backtotopbtn = get_config('theme_academi', 'backToTop_status');

        $totalstatus = $fstatus1 + $fstatus2 + $fstatus3 + $fstatus4;

        switch ($totalstatus) {
            case 4:
                $colclass = 'col-lg-3 col-md-6';
                break;
            case 3:
                $colclass = 'col-md-4';
                break;
            case 2:
                $colclass = 'col-md-6';
                break;
            case 1:
                $colclass = 'col-md-12';
                break;
            case 0:
                $colclass = '';
                break;
            default:
                $colclass = 'col-md-4';
                break;
        }

        $footerstatus = ($totalstatus == 0) ? false : true;
        $footerbottomstatus = ((empty($copyrightfooter)) && (!is_siteadmin($USER->id) || $CFG->debug == 0)) ? false : true;
        $templatecontext = [
            "footerlogourl" => $footerlogourl,
            "footlogostatus" => $footlogostatus,
            "footnote" => $footnote,
            "infolink" => $infolink,
            "address" => $address,
            "emailid" => $emailid,
            "phoneno" => $phoneno,
            "phone" => $phone,
            "email" => $email,
            "copyrightfooter" => $copyrightfooter,
            "fstatus1" => $fstatus1,
            "fstatus2" => $fstatus2,
            "fstatus3" => $fstatus3,
            "fstatus4" => $fstatus4,
            "ftitle1" => $ftitle1,
            "ftitle2" => $ftitle2,
            "ftitle3" => $ftitle3,
            "ftitle4" => $ftitle4,
            "colclass" => $colclass,
            'footerbgimgclass' => $footerbgimgclass,
            'backtotopbtn' => $backtotopbtn,
            'footerstatus' => $footerstatus,
            'footerbottomstatus' => $footerbottomstatus,
        ];
        $templatecontext += self::socialmedia(); // Call the static method.

        // Determine if the custom content sections of the footer are empty.
        // Moodle's default output (like infolink, debug footer, etc.) are not considered custom content here.
        $isemptycontentfooter = true;

        // Check custom content blocks (fstatus1-4)
        if ($footerstatus) { // if any custom block is active
            $isemptycontentfooter = false;
        }

        // Check footnote content
        if (!empty($footnote)) {
            $isemptycontentfooter = false;
        }

        // Check contact info
        if (!empty($address) || !empty($emailid) || !empty($phoneno)) {
            $isemptycontentfooter = false;
        }

        // Check social media.
        if (!empty($templatecontext['socialmedia'])) {
            foreach ($templatecontext['socialmedia'] as $sm) {
                if (!empty($sm['socialstatus'])) {
                    $isemptycontentfooter = false;
                    break;
                }
            }
        }

        // Check copyright footer (if it's not empty and is meant to be displayed).
        if (!empty($copyrightfooter) && $footerbottomstatus) {
            $isemptycontentfooter = false;
        }

        $templatecontext['isemptycontentfooter'] = $isemptycontentfooter;

        return $templatecontext;
    }
}
