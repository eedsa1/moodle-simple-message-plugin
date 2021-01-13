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
require_once($CFG->dirroot . "/mod/data/lib.php");

class database_wstemplate_external extends external_api {

    public static function handle_data_parameters() {
        return new external_function_parameters(
            array('name' => new external_value(PARAM_TEXT, 'database name,', VALUE_DEFAULT, 'Hello world, '),
                  'description' => new external_value(PARAM_TEXT, 'database description,', VALUE_DEFAULT, 'Hello world, '),
                  'course_id' => new external_value(PARAM_INT, 'course id ,', VALUE_DEFAULT, 'Hello world, '),

                  'approval' => new external_value(PARAM_INT, 'course id ,', VALUE_DEFAULT, 0),
                  'manageapproved' => new external_value(PARAM_INT, 'course id ,', VALUE_DEFAULT, 0),
                  'comments' => new external_value(PARAM_INT, 'course id ,', VALUE_DEFAULT, 0),
                  'requiredentries' => new external_value(PARAM_INT, 'course id ,', VALUE_DEFAULT, 0),
                  'requiredentriestoview' => new external_value(PARAM_INT, 'course id ,', VALUE_DEFAULT, 0),
                  'maxentries' => new external_value(PARAM_INT, 'course id ,', VALUE_DEFAULT, 0),
                  'timeavailablefrom' => new external_value(PARAM_TEXT, 'course id ,', VALUE_DEFAULT, 0),
                  'timeavailableto' => new external_value(PARAM_TEXT, 'course id ,', VALUE_DEFAULT, 0),
                  'timeviewfrom' => new external_value(PARAM_TEXT, 'course id ,', VALUE_DEFAULT, 0),
                  'timeviewto' => new external_value(PARAM_TEXT, 'course id ,', VALUE_DEFAULT, 0),

                  'group_id' => new external_value(PARAM_INT, 'course id ,', VALUE_DEFAULT, 'Hello world, '),
                )
        );
    }

    public static function getStringDate( $dateString ){
        $timestamp = strtotime( $dateString ); 
        $date = date("Y-m-d H:i:s", $timestamp);

        return strtotime($date);
    }

    public static function handle_data($name = '',$description='',$course_id=1,
        $approval=0, $manageapproved=0, $comments=0, $requiredentries=0, $requiredentriestoview = 0, $maxentries=0,
        $timeavailablefrom=0, $timeavailableto=0, $timeviewfrom=0, $timeviewto=0, $group_id=1) {

        global $COURSE, $DB;

        $params = self::validate_parameters(self::handle_data_parameters(),
                array('name' => $name, 'description' => $description, 'course_id' => $course_id,
                    'approval' => $approval, 'manageapproved' => $manageapproved, 'comments' => $comments, 'requiredentries' => $requiredentries,
                    'requiredentriestoview' => $requiredentriestoview, 'maxentries' => $maxentries,
                    'timeavailablefrom' => $timeavailablefrom, 'timeavailableto' => $timeavailableto,
                    'timeviewto' => $timeviewto, 'timeviewto' => $timeviewto, 'group_id' => $group_id
                    
                ));

        $section= 6;

        // mod generator
        $modulename = 'data';
        $cm = new stdClass();
        $cm->course             = $course_id;

        $cm->module             = $DB->get_field('modules', 'id', array('name'=>$modulename));
        $cm->instance           = 0;
        $cm->section            = $section;
        $cm->idnumber           = null;
        $cm->added              = time();
        $cm->id					= $DB->insert_record('course_modules', $cm);
    
        course_add_cm_to_section( $course_id, $cm->id, $section);
    
        $data = new stdClass();
        $data->modulename = 'data';
        $data->course = $course_id;

        $data->module = $cm->module;
        $data->name = $name;
        $data->intro = $description;

        $data->approval = $approval;
        $data->manageapproved = $manageapproved;
        $data->comments = $comments;
        $data->requiredentries = $requiredentries;
        $data->maxentries = $maxentries;

        $data->timeavailablefrom = self::getStringDate( $timeavailablefrom );
        $data->timeavailableto = self::getStringDate( $timeavailableto );
        $data->timeviewfrom = self::getStringDate( $timeviewfrom );
        $data->timeviewto = self::getStringDate( $timeviewto );

        $data->coursemodule = $cm->id;

        $instance = data_add_instance( $data );

        $DB->set_field('course_modules', 'instance', $instance, array('id'=> $data->coursemodule ));

        $instance = $DB->get_record('data', array('id'=>$instance), '*', MUST_EXIST);
        $cm = get_coursemodule_from_id('data',  $data->coursemodule, $instance->course, true, MUST_EXIST);
        context_module::instance($cm->id);
        rebuild_course_cache($course->id);
        add_to_log($course_id, "course", "add mod",
        "../../mod/$cm->modulename/view.php?id=$cm->id",
        "$cm->modulename $cm->instance");
        add_to_log($course_id, 'data', "add",
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


    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function handle_data_returns() {

        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'Whether the user can do the quiz or not.'),
                'hasgrade' => new external_value(PARAM_BOOL, 'Whether the user can do the quiz or not.'),
            )
        );

        return new external_value(PARAM_TEXT, 'The welcome message + user first name');
    }
}
