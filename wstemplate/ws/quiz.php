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




class quiz_wstemplate_external extends external_api {
    
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
                'course_id' => new external_value(PARAM_INT, 'course id ,', VALUE_DEFAULT, 'Hello world, '),
                'timeopen' => new external_value(PARAM_TEXT, 'wiki description,', VALUE_DEFAULT, 'Hello world, '),
                'timeclose' => new external_value(PARAM_TEXT, 'wiki description,', VALUE_DEFAULT, 'Hello world, '),

                'group_id' => new external_value(PARAM_INT, 'course id ,', VALUE_DEFAULT, 'Hello world, '),
            )
        );
    }

    public static function get_quizzes( $name = '',$description='', $course_id=1, $timeopen = '',$timeclose='',$group_id=1) {

        global $COURSE, $DB;

        $params = self::validate_parameters(self::get_quizzes_parameters(),
                array('name' => $name, 'description' => $description, 'course_id'=>$course_id,
                'timeopen' => $timeopen, 'timeclose' => $timeclose, 'group_id' => $group_id
            ));

        $section= 6;
        $course_name= $params["name"];

        // mod generator
        $modulename = 'quiz';
        $cm = new stdClass();
        $cm->course             = $course_id;

        $cm->module             = $DB->get_field('modules', 'id', array('name'=>$modulename));
        $cm->instance           = 0;
        $cm->section            = $section;
        $cm->idnumber           = null;
        $cm->added              = time();
        $cm->id					= $DB->insert_record('course_modules', $cm);
    
        course_add_cm_to_section( $course_id, $cm->id, $section);
    

        $quiz = new stdClass();
        $quiz->quizpassword = "";
        $quiz->modulename = 'quiz';
        $quiz->course = $course_id;

        $quiz->module = $cm->module;

        $quiz->name = $course_name;
        $quiz->preferredbehaviour = 'deferredfeedback';

        
        $quiz->intro = $description;
        $quiz->timeopen = self::getStringDate( $timeopen );
        $quiz->timeclose = self::getStringDate( $timeclose );

        $quiz->section = $section;
        $quiz->coursemodule = $cm->id;

        $instance = quiz_add_instance( $quiz );

        $DB->set_field('course_modules', 'instance', $instance, array('id'=> $quiz->coursemodule ));

        $instance = $DB->get_record('quiz', array('id'=>$instance), '*', MUST_EXIST);
        $cm = get_coursemodule_from_id('quiz',  $quiz->coursemodule, $instance->course, true, MUST_EXIST);
        context_module::instance($cm->id);
        rebuild_course_cache($course->id);
        add_to_log($course_id, "course", "add mod",
        "../../mod/$cm->modulename/view.php?id=$cm->id",
        "$cm->modulename $cm->instance");
        add_to_log($course_id, 'quiz', "add",
        "view.php?id=$cm->coursemodule",
        "$cm->instance", $cm->id);

        $restriction = \core_availability\tree::get_root_json(
            [\availability_group\condition::get_json($group_id)]);
        $DB->set_field('course_modules', 'availability',
        json_encode($restriction), ['id' => $cm->id]);
        rebuild_course_cache($course_id, true);
        
        $warnings = array();

        $result = array();
        $result['id'] = $instance->id;
        $result['hasgrade'] = false;
        return $result;
    
    }

    public static function get_quizzes_returns() {

        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'Whether the user can do the quiz or not.'),
                'hasgrade' => new external_value(PARAM_BOOL, 'Whether the user can do the quiz or not.'),
                
            )
        );

        return new external_value(PARAM_TEXT, 'The welcome message + user first name');
    }

}
