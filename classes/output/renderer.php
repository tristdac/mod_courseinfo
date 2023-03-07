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
 * Renderer.
 *
 * @package    mod_courseinfo
 * @copyright  2023 Edinburgh College
 * @author     Tristan daCosta <tristan.dacosta@edinburghcollege.ac.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_courseinfo\output;
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/courseinfo/lib.php');

use moodle_url;
use mod_courseinfo\admin_setting_resourcesstyles;

/**
 * Renderer.
 *
 * @package    mod_courseinfo
 * @copyright  2023 Edinburgh College
 * @author     Tristan daCosta <tristan.dacosta@edinburghcollege.ac.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class renderer extends \plugin_renderer_base {

    /**
     * Render the content.
     *
     * @param stdClass $data The data to use when rendering.
     * @return string
     */
    public function display_content(\cm_info $cm) {
        global $COURSE, $CFG;
        
        $title = $cm->name;
        // $resources = $this->style_resources($cm->customdata->resources);

        $url_timetable = get_config('mod_courseinfo', 'timetableurl');

        $url_library = get_config('mod_courseinfo', 'libraryurl');

        $filetypes = array('handbook');
        $files = array();
        // print_r($cm->customdata);
        foreach($filetypes as $filetype) {
            ${"url_$filetype"} = null;
        
            if (!empty($cm->customdata->$filetype)) {
                ${"timemodified_$filetype"} = (int) $cm->customdata->$filetype->timemodified;
                ${"url_$filetype"} = moodle_url::make_pluginfile_url(
                    $cm->context->id,
                    'mod_courseinfo',
                    $filetype,
                    0,
                    "/",
                    $cm->customdata->$filetype->filename);              
            } else {
                ${"url_$filetype"} = get_config('mod_courseinfo', 'handbookurl');
            }
        }

        $context = \context_course::instance($COURSE->id);
        if (has_capability('mod/courseinfo:addinstance', $context)) {
            $canedit = true;
        } else {
            $canedit = false;
        }

        if ($cm->customdata->subguide) {
            $sgselected = true;
            $url_sg = $cm->customdata->subguide;
        } else {
            $sgselected = false;
            $url_sg = null;
        }

        // if (str_contains($COURSE->shortname, '/')) {
        //     $isunit = true;
        // } else if (str_contains($COURSE->shortname, '-')) {
        //     $iscourse = true;
        // }

        // $meta = get_course_metadata($COURSE->id);
        // print_object($meta);
        // echo $meta['framework'];
        // if (!empty($framework_units)) {
        //     $hasunits = true;
        // } else {
        //     $hasunits = false;
        // }
        $framework_units = get_available_framework_units($COURSE->fullname);

        $data = [
            'title' => $title,
            'content' => format_module_intro('courseinfo', $cm->customdata, $cm->id),
            'course' => strtoupper($COURSE->fullname),
            // 'hasresources' => !empty($resources),
            // 'resources' => $resources,
            'timetableurl' => $url_timetable,
            'libraryurl' => $url_library,
            'handbookurl' => $url_handbook,
            'sgselected' => $sgselected,
            'sgurl' => $url_sg,
            'cmid'    => $cm->id,
            'canedit' => $canedit,
            // 'isunit' => $isunit,
            // 'iscourse' => $iscourse,
            'wwwroot' => $CFG->wwwroot,
            'hasunits' => !empty($framework_units),
            'framework_units' => $framework_units
        ];

        return $this->render_from_template('mod_courseinfo/content', $data);
    }

}
