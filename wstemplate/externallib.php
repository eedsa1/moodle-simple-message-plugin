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

class local_wstemplate_external extends external_api {

    public static function handle_chat_parameters() {
        return new external_function_parameters(
            array('name' => new external_value(PARAM_TEXT, 'chat name,', VALUE_DEFAULT, 'Hello world, '),
                  'description' => new external_value(PARAM_TEXT, 'chat description,', VALUE_DEFAULT, 'Hello world, '),
                  'course_id' => new external_value(PARAM_INT, 'course id ,', VALUE_DEFAULT, 'Hello world, '))
        );
    }

    public static function handle_chat($name = '',$description='',$course_id=1) {

        global $COURSE, $DB;

        $params = self::validate_parameters(self::handle_chat_parameters(),
                array('name' => $name, 'description' => $description, 'course_id' => $course_id ));

        $course_id = $course_id;
        $section= 6;
        $course_name= $params["name"];

        // mod generator
        $modulename = 'chat';
        $cm = new stdClass();
        $cm->course             = $course_id;

        $cm->module             = $DB->get_field('modules', 'id', array('name'=>$modulename));
        $cm->instance           = 0;
        $cm->section            = $section;
        $cm->idnumber           = null;
        $cm->added              = time();
        $cm->id					= $DB->insert_record('course_modules', $cm);
    
        course_add_cm_to_section( $course_id, $cm->id, $section);

        $chat = new stdClass();
        $chat->modulename = 'chat';
        $chat->course = $course_id;

        $chat->module = $cm->module;
        $chat->name = $course_name;
        $chat->intro = $description;

        $chat->coursemodule = $cm->id;

        $instance = chat_add_instance( $chat );

        $DB->set_field('course_modules', 'instance', $instance, array('id'=> $chat->coursemodule ));

        $instance = $DB->get_record('chat', array('id'=>$instance), '*', MUST_EXIST);
        $cm = get_coursemodule_from_id('chat',  $chat->coursemodule, $instance->course, true, MUST_EXIST);
        context_module::instance($cm->id);
        rebuild_course_cache($course->id);
        add_to_log($course_id, "course", "add mod",
        "../../mod/$cm->modulename/view.php?id=$cm->id",
        "$cm->modulename $cm->instance");
        add_to_log($course_id, 'chat', "add",
        "view.php?id=$cm->coursemodule",
        "$cm->instance", $cm->id);

        $warnings = array();

        $result = array();
        $result['hasgrade'] = false;
        return $result;

    }


    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function handle_chat_returns() {

        return new external_single_structure(
            array(
                'hasgrade' => new external_value(PARAM_BOOL, 'Whether the user can do the quiz or not.'),
                
            )
        );

        return new external_value(PARAM_TEXT, 'The welcome message + user first name');
    }

    
    /**
     * Returns description of method result value
     * @return external_function_parameters
     */
    public static function get_quizzes_parameters() {
        return new external_function_parameters(
            array('course_name' => new external_value(PARAM_TEXT, 'course name,', VALUE_DEFAULT, 'Hello world, '))
        );
    }

    /**
     * Returns welcome message
     * @return array welcome message
     */

    public function getNumItem( $type ) {
        global $DB;
        
        return (int) $DB->get_field_sql(
        "SELECT COUNT(id) FROM mdl_grade_items
          WHERE itemmodule  like '{$type}'") + 1;
    }


    public static function get_quizzes($course_name = '') {

        global $COURSE, $DB;

        $params = self::validate_parameters(self::get_quizzes_parameters(),
                array('course_name' => $course_name));

        $course_id = 37;
        $section= 6;
        $course_name= $params["course_name"];

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
        $quiz->intro = '12sddfdf544543____2323324';
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

        $warnings = array();

        $result = array();
        $result['hasgrade'] = false;
        return $result;
    
/*

        $data = new stdClass;
        $data->id = $course_module->id;
        $data->instance = $quiz->id;

        $DB->update_record('course_modules', $data);*/

   
    
    }


    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_quizzes_returns() {

        return new external_single_structure(
            array(
                'hasgrade' => new external_value(PARAM_BOOL, 'Whether the user can do the quiz or not.'),
                
            )
        );

        return new external_value(PARAM_TEXT, 'The welcome message + user first name');
    }


    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function hello_world_parameters() {
        return new external_function_parameters(
                array('welcomemessage' => new external_value(PARAM_TEXT, 'The welcome message. By default it is "Hello world,"', VALUE_DEFAULT, 'Hello world, '))
        );
    }

    /**
     * Returns welcome message
     * @return string welcome message
     */
    public static function hello_world($welcomemessage = 'Hello world, ') {
        global $USER, $DB;

        
        
        return $DB->get_records('quiz', array());
        

        //Parameter validation
        //REQUIRED
        $params = self::validate_parameters(self::hello_world_parameters(),
                array('welcomemessage' => $welcomemessage));

        //Context validation
        //OPTIONAL but in most web service it should present
        $context = get_context_instance(CONTEXT_USER, $USER->id);
        self::validate_context($context);

        //Capability checking
        //OPTIONAL but in most web service it should present
        if (!has_capability('moodle/user:viewdetails', $context)) {
            throw new moodle_exception('cannotviewprofile');
        }

        
        return $DB->get_record('quiz',array());

        return $params['welcomemessage'] . $USER->firstname ;;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function hello_world_returns() {
        return new external_single_structure(
            array(

                'id' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                'course' => new external_value(PARAM_INT, 'Foreign key reference to the course this quiz is part of.'),
                'coursemodule' => new external_value(PARAM_INT, 'Course module id.'),
                'name' => new external_value(PARAM_RAW, 'Quiz name.'),
                'intro' => new external_value(PARAM_RAW, 'Quiz introduction text.', VALUE_OPTIONAL),
                'introformat' => new external_format_value('intro', VALUE_OPTIONAL),
                'timeopen' => new external_value(PARAM_INT, 'The time when this quiz opens. (0 = no restriction.)',
                                                    VALUE_OPTIONAL),
                'timeclose' => new external_value(PARAM_INT, 'The time when this quiz closes. (0 = no restriction.)',
                                                    VALUE_OPTIONAL),
                'timelimit' => new external_value(PARAM_INT, 'The time limit for quiz attempts, in seconds.',
                                                    VALUE_OPTIONAL),
                'overduehandling' => new external_value(PARAM_ALPHA, 'The method used to handle overdue attempts.
                                                        \'autosubmit\', \'graceperiod\' or \'autoabandon\'.',
                                                        VALUE_OPTIONAL),
                'graceperiod' => new external_value(PARAM_INT, 'The amount of time (in seconds) after the time limit
                                                    runs out during which attempts can still be submitted,
                                                    if overduehandling is set to allow it.', VALUE_OPTIONAL),
                'preferredbehaviour' => new external_value(PARAM_ALPHANUMEXT, 'The behaviour to ask questions to use.',
                                                            VALUE_OPTIONAL),
                'canredoquestions' => new external_value(PARAM_INT, 'Allows students to redo any completed question
                                                            within a quiz attempt.', VALUE_OPTIONAL),
                'attempts' => new external_value(PARAM_INT, 'The maximum number of attempts a student is allowed.',
                                                    VALUE_OPTIONAL),
                'attemptonlast' => new external_value(PARAM_INT, 'Whether subsequent attempts start from teh answer
                                                        to the previous attempt (1) or start blank (0).',
                                                        VALUE_OPTIONAL),
                'grademethod' => new external_value(PARAM_INT, 'One of the values QUIZ_GRADEHIGHEST, QUIZ_GRADEAVERAGE,
                                                        QUIZ_ATTEMPTFIRST or QUIZ_ATTEMPTLAST.', VALUE_OPTIONAL),
                'decimalpoints' => new external_value(PARAM_INT, 'Number of decimal points to use when displaying
                                                        grades.', VALUE_OPTIONAL),
                'questiondecimalpoints' => new external_value(PARAM_INT, 'Number of decimal points to use when
                                                                displaying question grades.
                                                                (-1 means use decimalpoints.)', VALUE_OPTIONAL),
                'reviewattempt' => new external_value(PARAM_INT, 'Whether users are allowed to review their quiz
                                                        attempts at various times. This is a bit field, decoded by the
                                                        mod_quiz_display_options class. It is formed by ORing together
                                                        the constants defined there.', VALUE_OPTIONAL),
                'reviewcorrectness' => new external_value(PARAM_INT, 'Whether users are allowed to review their quiz
                                                            attempts at various times.
                                                            A bit field, like reviewattempt.', VALUE_OPTIONAL),
                'reviewmarks' => new external_value(PARAM_INT, 'Whether users are allowed to review their quiz attempts
                                                    at various times. A bit field, like reviewattempt.',
                                                    VALUE_OPTIONAL),
                'reviewspecificfeedback' => new external_value(PARAM_INT, 'Whether users are allowed to review their
                                                                quiz attempts at various times. A bit field, like
                                                                reviewattempt.', VALUE_OPTIONAL),
                'reviewgeneralfeedback' => new external_value(PARAM_INT, 'Whether users are allowed to review their
                                                                quiz attempts at various times. A bit field, like
                                                                reviewattempt.', VALUE_OPTIONAL),
                'reviewrightanswer' => new external_value(PARAM_INT, 'Whether users are allowed to review their quiz
                                                            attempts at various times. A bit field, like
                                                            reviewattempt.', VALUE_OPTIONAL),
                'reviewoverallfeedback' => new external_value(PARAM_INT, 'Whether users are allowed to review their quiz
                                                                attempts at various times. A bit field, like
                                                                reviewattempt.', VALUE_OPTIONAL),
                'questionsperpage' => new external_value(PARAM_INT, 'How often to insert a page break when editing
                                                            the quiz, or when shuffling the question order.',
                                                            VALUE_OPTIONAL),
                'navmethod' => new external_value(PARAM_ALPHA, 'Any constraints on how the user is allowed to navigate
                                                    around the quiz. Currently recognised values are
                                                    \'free\' and \'seq\'.', VALUE_OPTIONAL),
                'shuffleanswers' => new external_value(PARAM_INT, 'Whether the parts of the question should be shuffled,
                                                        in those question types that support it.', VALUE_OPTIONAL),
                'sumgrades' => new external_value(PARAM_FLOAT, 'The total of all the question instance maxmarks.',
                                                    VALUE_OPTIONAL),
                'grade' => new external_value(PARAM_FLOAT, 'The total that the quiz overall grade is scaled to be
                                                out of.', VALUE_OPTIONAL),
                'timecreated' => new external_value(PARAM_INT, 'The time when the quiz was added to the course.',
                                                    VALUE_OPTIONAL),
                'timemodified' => new external_value(PARAM_INT, 'Last modified time.',
                                                        VALUE_OPTIONAL),
                'password' => new external_value(PARAM_RAW, 'A password that the student must enter before starting or
                                                    continuing a quiz attempt.', VALUE_OPTIONAL),
                'subnet' => new external_value(PARAM_RAW, 'Used to restrict the IP addresses from which this quiz can
                                                be attempted. The format is as requried by the address_in_subnet
                                                function.', VALUE_OPTIONAL),
                'browsersecurity' => new external_value(PARAM_ALPHANUMEXT, 'Restriciton on the browser the student must
                                                        use. E.g. \'securewindow\'.', VALUE_OPTIONAL),
                'delay1' => new external_value(PARAM_INT, 'Delay that must be left between the first and second attempt,
                                                in seconds.', VALUE_OPTIONAL),
                'delay2' => new external_value(PARAM_INT, 'Delay that must be left between the second and subsequent
                                                attempt, in seconds.', VALUE_OPTIONAL),
                'showuserpicture' => new external_value(PARAM_INT, 'Option to show the user\'s picture during the
                                                        attempt and on the review page.', VALUE_OPTIONAL),
                'showblocks' => new external_value(PARAM_INT, 'Whether blocks should be shown on the attempt.php and
                                                    review.php pages.', VALUE_OPTIONAL),
                'completionattemptsexhausted' => new external_value(PARAM_INT, 'Mark quiz complete when the student has
                                                                    exhausted the maximum number of attempts',
                                                                    VALUE_OPTIONAL),
                'completionpass' => new external_value(PARAM_INT, 'Wheter to require passing grade', VALUE_OPTIONAL),
                'autosaveperiod' => new external_value(PARAM_INT, 'Auto-save delay', VALUE_OPTIONAL),
                'hasfeedback' => new external_value(PARAM_INT, 'Whether the quiz has any non-blank feedback text',
                                                    VALUE_OPTIONAL),
                'hasquestions' => new external_value(PARAM_INT, 'Whether the quiz has questions', VALUE_OPTIONAL),
                'section' => new external_value(PARAM_INT, 'Course section id', VALUE_OPTIONAL),
                'visible' => new external_value(PARAM_INT, 'Module visibility', VALUE_OPTIONAL),
                'groupmode' => new external_value(PARAM_INT, 'Group mode', VALUE_OPTIONAL),
                'groupingid' => new external_value(PARAM_INT, 'Grouping id', VALUE_OPTIONAL),

            )
        );
    }

    



}
