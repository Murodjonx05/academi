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
 * Extra additional blocks for the theme_academi.
 *
 * @package   theme_academi
 * @copyright 2023 onwards LMSACE Dev Team (http://www.lmsace.com)
 * @author    LMSACE Dev Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_academi;

/**
 * Extra additional blocks content class for the theme academi.
 */
class academi_blocks {

    /**
     * Return the html contents for the  Site features block.
     * @return type|string
     */
    public function sitefeatures() {
        global $OUTPUT, $PAGE;
        $status = get_config('theme_academi', 'sitefblockstatus');
        $blocktitle = get_string(get_config('theme_academi', 'sitefeaturetitle'), 'theme_academi');
        $blockdesc = get_string(get_config('theme_academi', 'sitefeaturedesc'), 'theme_academi');
        $items = [];
        (int) $cs = 0;
        if ($status == 1) {
            $features = get_config('theme_academi', 'numberofsitefeature');
            $block['class'] = 'icon-block';
            for ($i = 1; $i <= $features; $i++) {
                $sfbstatus = get_config('theme_academi', 'sitefblock'.$i.'status');
                $sfbicon = get_config('theme_academi', 'sitefblock'.$i.'icon');
                $sfbtitle = get_config('theme_academi', 'sitefblock'.$i.'title');
                $sfbcontent = get_config('theme_academi', 'sitefblock'.$i.'content');
                if ((!empty($sfbstatus)) && (!empty($sfbtitle) || !empty($sfbcontent) || !empty($sfbicon))) {
                    $cs = $cs + 1;
                }
            }
            switch ($cs) {
                case 4:
                    $colclass = 'col-md-6 col-lg-3';
                    break;
                case 3:
                    $colclass = 'col-md-6 col-lg-4';
                    break;
                case 2:
                    $colclass = 'col-md-6';
                    break;
                case 1:
                    $colclass = 'col-md-12';
                    break;
                default:
                    $colclass = 'col-md-6 col-lg-3';
                    break;
            }
            for ($i = 1; $i <= $features; $i++) {
                $sfbtitle = get_config('theme_academi', 'sitefblock'.$i.'title');
                $sfbtitle = get_string($sfbtitle, 'theme_academi');
                $sfbcontent = trim(get_config('theme_academi', 'sitefblock'.$i.'content'));
                $sfbcontent = get_string($sfbcontent, 'theme_academi');
                $sfbstatus = get_config('theme_academi', 'sitefblock'.$i.'status');
                $sfbicon = get_config('theme_academi', 'sitefblock'.$i.'icon');
                $sfbicon = get_string($sfbicon, 'theme_academi');
                $sfbbody = (!empty($sfbtitle) || (!empty($sfbcontent)) || (!empty($sfbicon))) ? true : false;
                $sfurl = get_config('theme_academi', 'sitefblock'.$i.'url');

                $items[] = [
                    'status' => !$sfbbody ? false : $sfbstatus,
                    'title' => $sfbtitle,
                    'content' => $sfbcontent,
                    'icon' => $sfbicon,
                    'sfbbody' => $sfbbody,
                    'url' => $sfurl,
                ];
            }
            $blockstatus = (empty($blocktitle) && empty($blockdesc)) ? false : $status;
            $blockisempty = (empty($blockstatus) && ($cs == 0)) ? false : $status;
            $block['sitefeatures'] = $status;
            $block['blockstatus'] = $blockstatus;
            $block['colclass'] = $colclass;
            $block['feature'] = $items;
            $block['blocktitle'] = $blocktitle;
            $block['blockdesc'] = $blockdesc;
            $block['blockisempty'] = $blockisempty;
            if (!$blockisempty) {
                $block['isblockempty'] = is_siteadmin() || $PAGE->user_is_editing() ? true : false;
            }
            return $OUTPUT->render_from_template('theme_academi/academi_blocks', $block);
        }
    }

    /**
     * Return the html contents for the marketingspot block.
     * @return type|string
     */
    public function marketingspot() {
        global $OUTPUT, $PAGE;
        $status = get_config('theme_academi', 'mspotstatus');
        if ($status == 1) {
            $mspot['title'] = get_string(get_config('theme_academi', 'mspottitle'), 'theme_academi');
            $mspot['desc'] = get_string(get_config('theme_academi', 'mspotdesc'), 'theme_academi');
            $mspot['content'] = format_text(get_config('theme_academi', 'mspotcontent'), FORMAT_HTML, ['trusted' => true, 'noclean' => true]);
            $mspot['media'] = $PAGE->theme->setting_file_url('mspotmedia', 'mspotmedia');
            $mspot['colclass'] = (empty($mspot['content']) || (empty($mspot['media']))) ? 'col-md-12' : 'col-lg-6';
            $mspot['mspot'] = $status;
            $mspot['mspotheadcontent'] = (empty($mspot['title']) && empty($mspot['desc'])) ? false : true;
            $blockisempty = empty($mspot['media']) && empty($mspot['content'])
                            && (empty($mspot['title'])) && (empty($mspot['desc'])) ? false : $status;
            $mspot['blockisempty'] = $blockisempty;
            if (!$blockisempty) {
                $mspot['isblockempty'] = is_siteadmin() || $PAGE->user_is_editing() ? true : false;
            }
            return $OUTPUT->render_from_template('theme_academi/academi_blocks', $mspot);
        }
    }

    /**
     * Return the html contents for the Jumbotron block.
     * @return type|string
     */
    public function jumbotron() {
        global $OUTPUT, $PAGE;
        $status = get_config('theme_academi', 'jumbotronstatus');
        if ($status == 1 ) {
            $jumbotron['title'] = get_string(get_config('theme_academi', 'jumbotrontitle'), 'theme_academi');
            $jumbotron['desc'] = get_string(get_config('theme_academi', 'jumbotrondesc'), 'theme_academi');
            $jumbotron['btntext'] = get_string(get_config('theme_academi', 'jumbotronbtntext'), 'theme_academi');
            $jumbotron['buttonlink'] = get_config('theme_academi', 'jumbotronbtnlink');
            $btntarget = get_config('theme_academi', 'jumbotronbtntarget');
            $jumbotron['btntarget'] = ($btntarget == '1') ? '_blank' : '_self';
            $jumbotron['jumbotron'] = $status;
            $jumbotron['jumbotroncontent'] = empty($jumbotron['title']) && empty($jumbotron['desc']) ? false : true;
            $blockisempty = empty($jumbotron['title']) && empty($jumbotron['desc'])
                                && empty($jumbotron['btntext']) ? false : $status;
            $jumbotron['blockisempty'] = $blockisempty;
            $jumbotron['btnclass'] = empty($jumbotron['btntext']) ? 'jumbotron-text-block' : '';
            if (!$blockisempty) {
                $jumbotron['isblockempty'] = is_siteadmin() || $PAGE->user_is_editing() ? true : false;
            }
            return $OUTPUT->render_from_template('theme_academi/academi_blocks', $jumbotron);
        }
    }
}
