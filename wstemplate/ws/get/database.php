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


class get_data extends external_api {

    public static function getStringDate( $timestamp ){

        $date = date("Y-m-d H:i:s", $timestamp);
        return $date;
    }

    public static function handle_data_parameters() {
        return new external_function_parameters(
            array(
                  'data_id' => new external_value(PARAM_INT, 'data description,', VALUE_DEFAULT, 'Hello world, '),
                  'course_id' => new external_value(PARAM_INT, 'course id ,', VALUE_DEFAULT, 'Hello world, '),
            )
        );
    }

    public static function handle_data($data_id='',$course_id=1) {

        global $COURSE, $DB;

        $params = self::validate_parameters(self::handle_data_parameters(),
                array('data_id' => $data_id, 'course_id' => $course_id ));

        $instance = $DB->get_record('data', array('id'=>$data_id), '*', MUST_EXIST);

        $result = array();
        $result['id'] = $instance->id;
        $result['name'] = $instance->name;
        $result['description'] = $instance->intro;
        
        $result['approval'] = $instance->approval;
        $result['manageapproved'] = $instance->manageapproved;
        $result['comments'] = $instance->comments;
        $result['requiredentries'] = $instance->requiredentries;
        $result['maxentries'] = $instance->maxentries;

        $result['timeavailablefrom'] = self::getStringDate( $instance->timeavailablefrom );
        $result['timeavailableto'] = self::getStringDate( $instance->timeavailableto );
        $result['timeviewfrom'] = self::getStringDate( $instance->timeviewfrom );
        $result['timeviewto'] = self::getStringDate( $instance->timeviewto );
     
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
                'name' => new external_value(PARAM_TEXT, 'Whether the user can do the quiz or not.'),
                'description' => new external_value(PARAM_TEXT, 'Whether the user can do the quiz or not.'),

                'approval' => new external_value(PARAM_INT, 'Whether the user can do the quiz or not.'),
                'manageapproved' => new external_value(PARAM_INT, 'Whether the user can do the quiz or not.'),
                'comments' => new external_value(PARAM_TEXT, 'Whether the user can do the quiz or not.'),
                'requiredentries' => new external_value(PARAM_INT, 'Whether the user can do the quiz or not.'),
                'maxentries' => new external_value(PARAM_INT, 'Whether the user can do the quiz or not.'),

                'timeavailablefrom' => new external_value(PARAM_TEXT, 'Whether the user can do the quiz or not.'),
                'timeavailableto' => new external_value(PARAM_TEXT, 'Whether the user can do the quiz or not.'),
                'timeviewfrom' => new external_value(PARAM_TEXT, 'Whether the user can do the quiz or not.'),
                'timeviewto' => new external_value(PARAM_TEXT, 'Whether the user can do the quiz or not.'),
                
            )
        );

        return new external_value(PARAM_TEXT, 'The welcome message + user first name');
    }

}
