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
require_once($CFG->dirroot . "/mod/quiz/lib.php");


class get_quiz extends external_api {

    public static function handle_quiz_parameters() {
        return new external_function_parameters(
            array(
                  'quiz_id' => new external_value(PARAM_INT, 'quiz description,', VALUE_DEFAULT, 'Hello world, '),
                  'course_id' => new external_value(PARAM_INT, 'course id ,', VALUE_DEFAULT, 'Hello world, '),
            )
        );
    }

    public static function getStringDate( $timestamp ){

        $date = date("Y-m-d H:i:s", $timestamp);
        return $date;
    }

    public static function handle_quiz($quiz_id='',$course_id=1) {

        global $COURSE, $DB;

        $params = self::validate_parameters(self::handle_quiz_parameters(),
                array('quiz_id' => $quiz_id, 'course_id' => $course_id ));

        $instance = $DB->get_record('quiz', array('id'=>$quiz_id), '*', MUST_EXIST);

        $result = array();
        $result['id'] = $instance->id;
        $result['name'] = $instance->name;
        $result['description'] = $instance->intro;
        $result['time_limit'] = $instance->timelimit;
        $result['new_page'] = $instance->questionsperpage;
        $result['shuffleanswers'] = $instance->shuffleanswers;

        $result['timeopen'] = self::getStringDate($instance->timeopen);
        $result['timeclose'] = self::getStringDate($instance->timeclose);

        return $result;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function handle_quiz_returns() {

        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'Whether the user can do the quiz or not.'),
                'name' => new external_value(PARAM_TEXT, 'Whether the user can do the quiz or not.'),
                'description' => new external_value(PARAM_TEXT, 'Whether the user can do the quiz or not.'),
                'time_limit' => new external_value(PARAM_INT, 'Whether the user can do the quiz or not.'),
                'new_page' => new external_value(PARAM_INT, 'Whether the user can do the quiz or not.'),
                'shuffleanswers' => new external_value(PARAM_INT, 'Whether the user can do the quiz or not.'),

                'timeopen' => new external_value(PARAM_TEXT, 'Whether the user can do the quiz or not.'),
                'timeclose' => new external_value(PARAM_TEXT, 'Whether the user can do the quiz or not.'),
                
            )
        );

        return new external_value(PARAM_TEXT, 'The welcome message + user first name');
    }

}
