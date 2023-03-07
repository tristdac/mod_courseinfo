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
 * Module form.
 *
 * @package    mod_courseinfo
 * @copyright  2023 Edinburgh College
 * @author     Tristan daCosta <tristan.dacosta@edinburghcollege.ac.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/course/moodleform_mod.php');
require_once($CFG->dirroot . '/mod/courseinfo/lib.php');

/**
 * Module form.
 *
 * @package    mod_courseinfo
 * @copyright  2023 Edinburgh College
 * @author     Tristan daCosta <tristan.dacosta@edinburghcollege.ac.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_courseinfo_mod_form extends moodleform_mod {

    /**
     * Definition.
     *
     * @return void
     */
    public function definition() {
        global $PAGE;
        $mform = $this->_form;
        $PAGE->force_settings_menu();

        $mform->addElement('header', 'generalhdr', get_string('general'));

        // $mform->addElement('text', 'name', get_string('title', 'mod_courseinfo'), ['maxlength' => 255]);
        // $mform->setType('name', PARAM_TEXT);
        // $mform->addRule('name', null, 'required', null, 'client');
        // $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->addElement('hidden', 'name', 'Courseinfo');
        $mform->setType('name', PARAM_RAW);

        // $this->standard_intro_elements(get_string('content', 'mod_courseinfo'));
        // $mform->addRule('introeditor', null, 'required', null, 'client');
        $mform->addElement('hidden', 'introeditor', '');
        $mform->setType('introeditor', PARAM_RAW);

        $subguides = preg_split("/\r\n|\n|\r/", get_config('mod_courseinfo', 'subguides'));
        foreach ($subguides as $subguide) {
            $arr = explode("|", $subguide, 2);
            $sg = $arr[0];
            $url = $arr[1];

            $subguide_options[$url] = $sg;
        }
        asort($subguide_options);
        array_unshift($subguide_options , 'Choose...');
        $mform->addElement('select', 'subguide', get_string('subguide', 'mod_courseinfo'), $subguide_options);

        $mform->addElement(
            'filepicker',
            'handbook',
            get_string('handbook', 'mod_courseinfo'),
            null,
            [
                'accepted_types' => 'application/pdf',
            ]
        );


        
        $this->standard_coursemodule_elements();
        $this->add_action_buttons(true, false, null);
    }

    /**
     * Data pre-processing.
     *
     * @param array &$defaultvalues The default values.
     * @return void
     */
    public function data_preprocessing(&$defaultvalues) {
        global $DB;

        if ($this->current && !empty($this->current->instance)) {

            // Iniialise file draft areas.
            $hb_draftitemid = file_get_submitted_draft_itemid('handbook');
            file_prepare_draft_area($hb_draftitemid, $this->context->id, 'mod_courseinfo', 'handbook', 0,
                mod_courseinfo_filemanager_options());
            $defaultvalues['handbook'] = $hb_draftitemid;

            // // Restore the resources.
            // $defaultvalues['resourceurl'] = [];
            // $defaultvalues['resourcetext'] = [];
            // $defaultvalues['resourceicon'] = [];
            // $resources = $DB->get_records('courseinfo_resources', ['courseinfoid' => $this->current->instance], 'id');
            // foreach ($resources as $resource) {
            //     $defaultvalues['resourceurl'][] = $resource->url;
            //     $defaultvalues['resourcetext'][] = $resource->text;
            //     $defaultvalues['resourceicon'][] = $resource->icon;
            // }
        }
    }

    /**
     * Data post processing.
     *
     * @param stdClass $data The data.
     * @return void
     */
    // public function data_postprocessing($data) {
    //     // Normalise the resources.
    //     $data->resources = array_filter(array_map(function($idx) use ($data) {
    //         $url = !empty($data->resourceurl[$idx]) ? $data->resourceurl[$idx] : '';
    //         $text = !empty($data->resourcetext[$idx]) ? $data->resourcetext[$idx] : '';
    //         $icon = !empty($data->resourceicon[$idx]) ? $data->resourceicon[$idx] : '';
    //         if (!$url || !$text) {
    //             return null;
    //         }
    //         return (object) ['url' => $url, 'text' => $text, 'icon' => $icon];
    //     }, array_keys($data->resourceurl)));
    //     unset($data->resourceurl, $data->resourcetext, $data->resourceicon);

    //     // Always mark as showing description on frontpage, this is mainly to return
    //     // the content to the mobile app hidden as a description. For the web, we do
    //     // observe this setting as we always display content on the course page.
    //     $data->showdescription = 1;
    // }

    // /**
    //  * Init icon picker.
    //  *
    //  * @return void
    //  */
    // protected function init_iconpicker() {
    //     global $PAGE;
    //     $PAGE->requires->css(new moodle_url('/mod/courseinfo/lib/fontawesome-iconpicker/fontawesome-iconpicker.min.css'));
    //     $PAGE->requires->js_call_amd('mod_courseinfo/iconpicker', 'init', ['[id^=id_resourceicon_]']);
    // }

    /**
     * Validation.
     *
     * @param array $data Array of data.
     * @param array $files The files.
     * @return array List of errors.
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        // if (isset($data['resourceurl'])) {
        //     foreach ($data['resourceurl'] as $key => $url) {
        //         $hasurl = !empty($url);
        //         $hastext = !empty($data['resourcetext'][$key]);
        //         if ($hasurl xor $hastext) {
        //             $errors["resourceurl[$key]"] = get_string('urlandtextrequired', 'mod_courseinfo');

        //         } else if ($hasurl && !preg_match('@^https?://.+@', $url)) {
        //             $errors["resourceurl[$key]"] = get_string('invalidurl', 'core_error');

        //         }
        //     }
        // }

        return $errors;
    }

}
