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
require_once($CFG->dirroot . "/mod/lti/lib.php");


class get_lti extends external_api {

    public static function handle_lti_parameters() {
        return new external_function_parameters(
            array(
                  'lti_id' => new external_value(PARAM_INT, 'lti description,', VALUE_DEFAULT, 'Hello world, '),
                  'course_id' => new external_value(PARAM_INT, 'course id ,', VALUE_DEFAULT, 'Hello world, '),
            )
        );
    }

    public static function handle_lti($lti_id='',$course_id=1) {

        global $COURSE, $DB;

        $params = self::validate_parameters(self::handle_lti_parameters(),
                array('lti_id' => $lti_id, 'course_id' => $course_id ));

        $instance = $DB->get_record('lti', array('id'=>$lti_id), '*', MUST_EXIST);

        $result = array();
        $result['id'] = $instance->id;
        $result['name'] = $instance->name;
        $result['description'] = $instance->intro;

        $result['showdescription'] = $instance->debuglaunch;
        $result['showtitlelaunch'] = $instance->showtitlelaunch;
        $result['showdescriptionlaunch'] = $instance->showdescriptionlaunch;
        $result['typeid'] = $instance->typeid;
        $result['launchcontainer'] = $instance->launchcontainer;

        $result['toolurl'] = $instance->toolurl;
        $result['securetoolurl'] = $instance->securetoolurl;
        $result['resourcekey'] = $instance->resourcekey;
        $result['password'] = $instance->password;
        $result['instructorcustomparameters'] = $instance->instructorcustomparameters;

        return $result;
    
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function handle_lti_returns() {

        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'Whether the user can do the quiz or not.'),
                'name' => new external_value(PARAM_TEXT, 'Whether the user can do the quiz or not.'),
                'description' => new external_value(PARAM_TEXT, 'Whether the user can do the quiz or not.'),

                'showdescription' => new external_value(PARAM_BOOL, 'Whether the user can do the quiz or not.'),
                'showtitlelaunch' => new external_value(PARAM_BOOL, 'Whether the user can do the quiz or not.'),
                'showdescriptionlaunch' => new external_value(PARAM_BOOL, 'Whether the user can do the quiz or not.'),
                'typeid' => new external_value(PARAM_INT, 'Whether the user can do the quiz or not.'),
                'launchcontainer' => new external_value(PARAM_INT, 'Whether the user can do the quiz or not.'),

                'toolurl' => new external_value(PARAM_TEXT, 'Whether the user can do the quiz or not.'),
                'securetoolurl' => new external_value(PARAM_TEXT, 'Whether the user can do the quiz or not.'),
                'resourcekey' => new external_value(PARAM_TEXT, 'Whether the user can do the quiz or not.'),
                'password' => new external_value(PARAM_TEXT, 'Whether the user can do the quiz or not.'),
                'instructorcustomparameters' => new external_value(PARAM_TEXT, 'Whether the user can do the quiz or not.'),
                
            )
        );

        return new external_value(PARAM_TEXT, 'The welcome message + user first name');
    }

}
