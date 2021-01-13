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
require_once($CFG->dirroot . "/mod/chat/lib.php");




class update_quiz extends external_api {
    
    public static function getStringDate( $dateString ){
        $timestamp = strtotime( $dateString ); 
        $date = date("Y-m-d H:i:s", $timestamp);

        return strtotime($date);
    }

    /**
     * Returns description of method result value
     * @return external_function_parameters
     */
    public static function get_quizzes_parameters() {
        return new external_function_parameters(
            array(
                'name' => new external_value(PARAM_TEXT, 'course name,', VALUE_DEFAULT, 'Hello world, '),
                'description' => new external_value(PARAM_TEXT, 'wiki description,', VALUE_DEFAULT, 'Hello world, '),
                'quiz_id' => new external_value(PARAM_INT, 'course id ,', VALUE_DEFAULT, 'Hello world, '),
                'timeopen' => new external_value(PARAM_TEXT, 'wiki description,', VALUE_DEFAULT, 'Hello world, '),
                'timeclose' => new external_value(PARAM_TEXT, 'wiki description,', VALUE_DEFAULT, 'Hello world, '),
            )
        );
    }

    public static function get_quizzes( $name = '',$description='', $quiz_id=1, $timeopen = '',$timeclose='') {

        global $COURSE, $DB;

        $params = self::validate_parameters(self::get_quizzes_parameters(),
                array('name' => $name, 'description' => $description, 'quiz_id'=>$quiz_id,
                'timeopen' => $timeopen, 'timeclose' => $timeclose
            ));

        $quiz = new stdClass();
        $quiz->quizpassword = "";
        $quiz->modulename = 'quiz';
        $quiz->id = $quiz_id;

        $quiz->module = $cm->module;

        $quiz->name = $name;
        $quiz->intro = $description;
        $quiz->timeopen = self::getStringDate( $timeopen );
        $quiz->timeclose = self::getStringDate( $timeclose );

        $DB->update_record('quiz', $quiz);
        
        $instance = $DB->get_record('quiz', array('id'=> $quiz->id), '*', MUST_EXIST);
        $cm = get_coursemodule_from_instance('quiz', $quiz->id, $instance->course);

        context_module::instance($cm->id);
        rebuild_course_cache($course->id);
        add_to_log($course_id, "course", "add mod",
        "../../mod/$cm->modulename/view.php?id=$cm->id",
        "$cm->modulename $cm->instance");
        add_to_log($course_id, 'quiz', "add",
        "view.php?id=$cm->coursemodule",
        "$cm->instance", $cm->id);

        $warnings = array();

        $result = array();
        $result['sucess'] = true;
        return $result;
    
    }

    public static function get_quizzes_returns() {

        return new external_single_structure(
            array(
                'sucess' => new external_value(PARAM_BOOL, 'Whether the user can do the quiz or not.'),
                
            )
        );

        return new external_value(PARAM_TEXT, 'The welcome message + user first name');
    }

}
