<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.

namespace theme_academi\output;

use renderable;
use renderer_base;
use templatable;
use stdClass;
use moodle_url;
use context_course;
use core_course_list_element;

/**
 * Course card renderable.
 *
 * @package    theme_academi
 * @copyright  2024 LMSACE Dev Team
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_card implements renderable, templatable {

    /** @var stdClass Course object */
    protected $course;

    /** @var context_course Course context */
    protected $context;

    /**
     * Constructor.
     *
     * @param stdClass $course Course object
     */
    public function __construct($course) {
        $this->course = $course;
        $this->context = context_course::instance($course->id);
    }

    /**
     * Export data for template.
     *
     * @param renderer_base $output
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        global $USER, $DB;

        $data = new stdClass();
        $course = new core_course_list_element($this->course);

        // Basic course info.
        $data->id = $course->id;
        $data->fullname = format_string($course->fullname, true, ['context' => $this->context]);
        $data->shortname = format_string($course->shortname, true, ['context' => $this->context]);
        $data->courseurl = new moodle_url('/course/view.php', ['id' => $course->id]);

        // Course image.
        $data->imageurl = $this->get_course_image($course);
        $data->hasimage = !empty($data->imageurl);

        // Summary (truncated).
        $summary = strip_tags($course->summary);
        $data->summary = strlen($summary) > 120 ? substr($summary, 0, 120) . '...' : $summary;

        // Enrollment status.
        $data->isenrolled = is_enrolled($this->context, $USER->id);

        // Progress (if enrolled).
        if ($data->isenrolled) {
            $completion = $this->get_course_progress($course->id, $USER->id);
            $data->hasprogress = $completion !== null;
            $data->progress = $completion;
            $data->progresspercentage = round($completion);
        }

        // Status badge.
        $data->status = $this->get_course_status($course, $data->isenrolled, $data->progress ?? 0);

        // Teachers (up to 3).
        $data->teachers = $this->get_course_teachers($course->id);
        $data->hasteachers = !empty($data->teachers);

        // Category.
        if ($course->category) {
            $category = $DB->get_record('course_categories', ['id' => $course->category]);
            if ($category) {
                $data->category = format_string($category->name);
                $data->categoryurl = new moodle_url('/course/index.php', ['categoryid' => $category->id]);
            }
        }

        // Dates.
        if ($course->startdate) {
            $data->startdate = userdate($course->startdate, get_string('strftimedatefullshort'));
        }
        if ($course->enddate) {
            $data->enddate = userdate($course->enddate, get_string('strftimedatefullshort'));
        }

        // Accessibility.
        $data->arialabel = get_string('course') . ': ' . $data->fullname;
        if ($data->hasprogress) {
            $data->progressarialabel = get_string('aria:courseprogress', 'block_myoverview', $data->progresspercentage);
        }

        return $data;
    }

    /**
     * Get course image URL.
     *
     * @param core_course_list_element $course
     * @return string|null
     */
    protected function get_course_image($course) {
        global $OUTPUT;
        
        foreach ($course->get_course_overviewfiles() as $file) {
            if ($file->is_valid_image()) {
                return moodle_url::make_pluginfile_url(
                    $file->get_contextid(),
                    $file->get_component(),
                    $file->get_filearea(),
                    null,
                    $file->get_filepath(),
                    $file->get_filename()
                )->out();
            }
        }
        
        // Fallback placeholder.
        return $OUTPUT->get_generated_image_for_id($course->id);
    }

    /**
     * Get course completion progress.
     *
     * @param int $courseid
     * @param int $userid
     * @return float|null Percentage (0-100) or null
     */
    protected function get_course_progress($courseid, $userid) {
        $completion = new \completion_info(get_course($courseid));
        
        if (!$completion->is_enabled()) {
            return null;
        }

        $percentage = \core_completion\progress::get_course_progress_percentage(get_course($courseid), $userid);
        return $percentage;
    }

    /**
     * Get course status badge.
     *
     * @param stdClass $course
     * @param bool $isenrolled
     * @param float $progress
     * @return stdClass
     */
    protected function get_course_status($course, $isenrolled, $progress) {
        $status = new stdClass();
        
        if (!$isenrolled) {
            $status->label = get_string('new', 'theme_academi');
            $status->class = 'badge-new';
        } else if ($progress >= 100) {
            $status->label = get_string('completed', 'completion');
            $status->class = 'badge-completed';
        } else if ($progress > 0) {
            $status->label = get_string('inprogress', 'completion');
            $status->class = 'badge-inprogress';
        } else {
            $status->label = get_string('notstarted', 'completion');
            $status->class = 'badge-notstarted';
        }
        
        return $status;
    }

    /**
     * Get course teachers (up to 3).
     *
     * @param int $courseid
     * @return array
     */
    protected function get_course_teachers($courseid) {
        global $OUTPUT;
        
        $context = context_course::instance($courseid);
        $teachers = get_enrolled_users($context, 'moodle/course:update', 0, 'u.*', null, 0, 3);
        
        $result = [];
        foreach ($teachers as $teacher) {
            $result[] = [
                'fullname' => fullname($teacher),
                'profileurl' => new moodle_url('/user/profile.php', ['id' => $teacher->id]),
                'pictureurl' => $OUTPUT->user_picture($teacher, ['size' => 35, 'link' => false, 'visibletoscreenreaders' => false]),
            ];
        }
        
        return $result;
    }
}
