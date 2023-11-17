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

namespace tool_lcmovecoursestep\lifecycle;

global $CFG;
require_once($CFG->dirroot . '/admin/tool/lifecycle/step/lib.php');

use tool_lifecycle\local\manager\settings_manager;
use tool_lifecycle\local\response\step_response;
use tool_lifecycle\settings_type;
use tool_lifecycle\step\libbase;
use tool_lifecycle\step\instance_setting;

defined('MOODLE_INTERNAL') || die();

class step extends libbase {
    public function get_subpluginname()
    {
        return 'tool_lcmovecoursestep';
    }

    public function get_plugin_description() {
        return "Move course step plugin";
    }

    public function process_course($processid, $instanceid, $course)
    {
        $category = settings_manager::get_settings($instanceid, settings_type::STEP)['category'];
        move_courses([$course->id], $category);
        return step_response::proceed();
    }

    public function instance_settings() {
        return [
            new instance_setting('category', PARAM_SEQUENCE, true),
        ];
    }

    public function extend_add_instance_form_definition($mform) {
        // Category selection.
        $displaylist = \core_course_category::make_categories_list();
        $options = [
            'multiple' => false,
            'noselectionstring' => get_string('categories_noselection', 'tool_lcmovecoursestep'),
        ];
        $mform->addElement('autocomplete', 'category',
            get_string('category'),
            $displaylist, $options);
        $mform->setType('category', PARAM_SEQUENCE);
        $mform->addRule('category', get_string('emptycategory', 'tool_lcmovecoursestep'), 'required', null, 'client');
    }

}
