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

class update_database extends external_api {

    public static function handle_data_parameters() {
        return new external_function_parameters(
            array('name' => new external_value(PARAM_TEXT, 'database name,', VALUE_DEFAULT, 'Hello world, '),
                  'description' => new external_value(PARAM_TEXT, 'database description,', VALUE_DEFAULT, 'Hello world, '),
                  'database_id' => new external_value(PARAM_INT, 'database id ,', VALUE_DEFAULT, 'Hello world, '),

                  'approval' => new external_value(PARAM_INT, 'database id ,', VALUE_DEFAULT, 0),
                  'manageapproved' => new external_value(PARAM_INT, 'database id ,', VALUE_DEFAULT, 0),
                  'comments' => new external_value(PARAM_INT, 'database id ,', VALUE_DEFAULT, 0),
                  'requiredentries' => new external_value(PARAM_INT, 'database id ,', VALUE_DEFAULT, 0),
                  'requiredentriestoview' => new external_value(PARAM_INT, 'database id ,', VALUE_DEFAULT, 0),
                  'maxentries' => new external_value(PARAM_INT, 'database id ,', VALUE_DEFAULT, 0),
                  'timeavailablefrom' => new external_value(PARAM_TEXT, 'database id ,', VALUE_DEFAULT, 0),
                  'timeavailableto' => new external_value(PARAM_TEXT, 'database id ,', VALUE_DEFAULT, 0),
                  'timeviewfrom' => new external_value(PARAM_TEXT, 'database id ,', VALUE_DEFAULT, 0),
                  'timeviewto' => new external_value(PARAM_TEXT, 'database id ,', VALUE_DEFAULT, 0),
                )
        );
    }

    public static function getStringDate( $dateString ){
        $timestamp = strtotime( $dateString ); 
        $date = date("Y-m-d H:i:s", $timestamp);

        return strtotime($date);
    }

    public static function handle_data($name = '',$description='',$database_id=1,
        $approval=0, $manageapproved=0, $comments=0, $requiredentries=0, $requiredentriestoview = 0, $maxentries=0,
        $timeavailablefrom=0, $timeavailableto=0, $timeviewfrom=0, $timeviewto=0) {

        global $COURSE, $DB;

        $params = self::validate_parameters(self::handle_data_parameters(),
                array('name' => $name, 'description' => $description, 'database_id' => $database_id,
                    'approval' => $approval, 'manageapproved' => $manageapproved, 'comments' => $comments, 'requiredentries' => $requiredentries,
                    'requiredentriestoview' => $requiredentriestoview, 'maxentries' => $maxentries,
                    'timeavailablefrom' => $timeavailablefrom, 'timeavailableto' => $timeavailableto,
                    'timeviewto' => $timeviewto, 'timeviewto' => $timeviewto,
                
                ));

       
    
        $data = new stdClass();
        $data->modulename = 'data';
        $data->id = $database_id;

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

        $DB->update_record('data', $data);
        
        $instance = $DB->get_record('data', array('id'=> $data->id), '*', MUST_EXIST);
        $cm = get_coursemodule_from_instance('data', $data->id, $instance->course);

        context_module::instance($cm->id);
        rebuild_course_cache($course->id);
        add_to_log($course_id, "course", "add mod",
        "../../mod/$cm->modulename/view.php?id=$cm->id",
        "$cm->modulename $cm->instance");
        add_to_log($course_id, 'data', "add",
        "view.php?id=$cm->coursemodule",
        "$cm->instance", $cm->id);

        $warnings = array();

        $result = array();
        $result['sucess'] = true;
        return $result;
    }


    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function handle_data_returns() {

        return new external_single_structure(
            array(
                'sucess' => new external_value(PARAM_BOOL, 'Whether the user can do the quiz or not.'),
            )
        );

        return new external_value(PARAM_TEXT, 'The welcome message + user first name');
    }
}
