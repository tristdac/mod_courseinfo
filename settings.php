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
 * Settings.
 *
 * @package    mod_courseinfo
 * @copyright  2023 Edinburgh College
 * @author     Tristan daCosta <tristan.dacosta@edinburghcollege.ac.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

use mod_courseinfo\admin_setting_resourcesstyles;

if ($ADMIN->fulltree) {
    $settings->add(new admin_setting_configtextarea('mod_courseinfo/subguides',
                new lang_string('subguide_setting', 'mod_courseinfo'),
                new lang_string('subguide_setting_desc', 'mod_courseinfo'), '', PARAM_RAW, '50', '10'));

    $settings->add(new admin_setting_configtext('mod_courseinfo/libraryurl',
                new lang_string('libraryurl_setting', 'mod_courseinfo'),
                new lang_string('libraryurl_setting_desc', 'mod_courseinfo'), '', PARAM_RAW, '50', '10'));

    $settings->add(new admin_setting_configtext('mod_courseinfo/timetableurl',
                new lang_string('timetableurl_setting', 'mod_courseinfo'),
                new lang_string('timetableurl_setting_desc', 'mod_courseinfo'), '', PARAM_RAW, '50', '10'));

    $settings->add(new admin_setting_configtext('mod_courseinfo/handbookurl',
                new lang_string('handbookurl_setting', 'mod_courseinfo'),
                new lang_string('handbookurl_setting_desc', 'mod_courseinfo'), '', PARAM_RAW, '50', '10'));

    
}
