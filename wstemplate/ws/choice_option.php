<?php

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
 * External Web Service Template
 *
 * @package    localwstemplate
 * @copyright  2011 Moodle Pty Ltd (http://moodle.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once($CFG->libdir . "/externallib.php");

require_once($CFG->dirroot.'/course/lib.php');

class choice_option_wstemplate_external extends external_api {

    public static function handle_choice_options_parameters() {
        return new external_function_parameters(
            array('choiceid' => new external_value(PARAM_TEXT, 'choice_optionsbase name,', VALUE_DEFAULT, 'Hello world, '),
                  'text' => new external_value(PARAM_TEXT, 'choice_optionsbase description,', VALUE_DEFAULT, 'Hello world, '),
                  'maxanswers' => new external_value(PARAM_INT, 'course id ,', VALUE_DEFAULT, 'Hello world, '),
                )
        );
    }

    public static function handle_choice_options($choiceid="",$text="",$maxanswers=1) {

        global $COURSE, $DB;

        $params = self::validate_parameters(self::handle_choice_options_parameters(),
                array('choiceid' => $choiceid, 'text' => $text, 'maxanswers' => $maxanswers   ));

    
        $choice_options = new stdClass();
        $choice_options->modulename = 'choice_options';
        $choice_options->course = $course_id;

        $choice_options->module = $cm->module;

        $choice_options->choiceid = $choiceid;
        $choice_options->text = $text;
        $choice_options->maxanswers = $maxanswers;
        $choice_options->timemodified = time();

        $choice_options->coursemodule = $cm->id;

        $DB->insert_record("choice_options", $choice_options);

        $warnings = array();

        $result = array();
        $result['hasgrade'] = false;
        return $result;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function handle_choice_options_returns() {

        return new external_single_structure(
            array(
                'hasgrade' => new external_value(PARAM_BOOL, 'Whether the user can do the quiz or not.'),
            )
        );

        return new external_value(PARAM_TEXT, 'The welcome message + user first name');
    }
}
