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
require_once($CFG->dirroot . "/mod/feedback/lib.php");


class local_wstemplate_external extends external_api {


    public static function handle_feedback_parameters() {
        return new external_function_parameters(
            array('name' => new external_value(PARAM_TEXT, 'feedback name,', VALUE_DEFAULT, 'Hello world, '),
                  'description' => new external_value(PARAM_TEXT, 'feedback description,', VALUE_DEFAULT, 'Hello world, '),
                  'course_id' => new external_value(PARAM_INT, 'course id ,', VALUE_DEFAULT, 'Hello world, '),

                  'timeopen' => new external_value(PARAM_TEXT, 'course id ,', VALUE_DEFAULT, 'Hello world, '),
                  'timeclose' => new external_value(PARAM_TEXT, 'course id ,', VALUE_DEFAULT, 'Hello world, '),

                  'anonymous' => new external_value(PARAM_INT, 'course id ,', VALUE_DEFAULT, 'Hello world, '),
                  'multiple_submit' => new external_value(PARAM_INT, 'course id ,', VALUE_DEFAULT, 'Hello world, '),
                  'email_notification' => new external_value(PARAM_INT, 'course id ,', VALUE_DEFAULT, 'Hello world, '),
                  'autonumbering' => new external_value(PARAM_INT, 'course id ,', VALUE_DEFAULT, 'Hello world, '),

                  'publish_stats' => new external_value(PARAM_INT, 'course id ,', VALUE_DEFAULT, 'Hello world, '),

                  'page_after_submit' => new external_value(PARAM_TEXT, 'course id ,', VALUE_DEFAULT, 'Hello world, '),
                  'site_after_submit' => new external_value(PARAMPARAM_TEXT_INT, 'course id ,', VALUE_DEFAULT, 'Hello world, '),
                  )
        );
    }


    public static function handle_feedback($name = '',$description='',$course_id=1, $timeopen='', $timeclose='', $anonymous=1,
        $multiple_submit='', $email_notification='', $autonumbering='', $publish_stats='', $page_after_submit='', $site_after_submit='',
    ) {

        global $COURSE, $DB;

        $params = self::validate_parameters(self::handle_feedback_parameters(),
                array(
                'name' => $name, 'description' => $description, 'course_id' => $course_id,'timeopen'=>$timeopen, 'timeclose'=>$timeclose,
                'anonymous'=>$anonymous, 'multiple_submit'=>$multiple_submit, 'email_notification'=>$email_notification,'autonumbering'=>$autonumbering,
                'publish_stats'=>$publish_stats, 'site_after_submit'=>$site_after_submit,
             ));

        $course_id = $course_id;
        $section= 6;
        $course_name= $params["name"];

        // mod generator
        $modulename = 'feedback';
        $cm = new stdClass();
        $cm->course             = $course_id;

        $cm->module             = $DB->get_field('modules', 'id', array('name'=>$modulename));
        $cm->instance           = 0;
        $cm->section            = $section;
        $cm->idnumber           = null;
        $cm->added              = time();
        $cm->id					= $DB->insert_record('course_modules', $cm);
    
        course_add_cm_to_section( $course_id, $cm->id, $section);
    

        $feedback = new stdClass();
        $feedback->modulename = 'feedback';
        $feedback->course = $course_id;

        $feedback->module = $cm->module;
        $feedback->name = $course_name;
        $feedback->intro = $description;

        $feedback->timeopen = $timeopen;
        $feedback->timeclose = $timeclose;
        $feedback->anonymous = $anonymous;
        $feedback->multiple_submit = $multiple_submit;
        $feedback->email_notification = $email_notification;
        $feedback->autonumbering = $autonumbering;
        $feedback->publish_stats = $publish_stats;
        $feedback->page_after_submit = $page_after_submit;
        $feedback->site_after_submit = $site_after_submit;

        $feedback->coursemodule = $cm->id;

        $instance = feedback_add_instance( $feedback );

        $DB->set_field('course_modules', 'instance', $instance, array('id'=> $feedback->coursemodule ));

        $instance = $DB->get_record('feedback', array('id'=>$instance), '*', MUST_EXIST);
        $cm = get_coursemodule_from_id('feedback',  $feedback->coursemodule, $instance->course, true, MUST_EXIST);
        context_module::instance($cm->id);
        rebuild_course_cache($course->id);
        add_to_log($course_id, "course", "add mod",
        "../../mod/$cm->modulename/view.php?id=$cm->id",
        "$cm->modulename $cm->instance");
        add_to_log($course_id, 'feedback', "add",
        "view.php?id=$cm->coursemodule",
        "$cm->instance", $cm->id);

        $warnings = array();

        $result = array();
        $result['id'] = $instance->id;
        $result['hasgrade'] = false;
        return $result;
    
    }


    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function handle_feedback_returns() {

        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'Whether the user can do the quiz or not.'),
                'hasgrade' => new external_value(PARAM_BOOL, 'Whether the user can do the quiz or not.'),
                
            )
        );

        return new external_value(PARAM_TEXT, 'The welcome message + user first name');
    }

}
