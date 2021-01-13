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
require_once($CFG->dirroot . "/mod/chat/lib.php");


class get_users_by_course extends external_api {

    public static function get_course_users_parameters() {
        return new external_function_parameters(
            array(
                  'courseid' => new external_value(PARAM_INT, 'courseid,', VALUE_DEFAULT, '0'),
            ),
        );
    }

    public static function get_course_users($courseid='') {
        global $COURSE, $DB;

        $params = self::validate_parameters(self::get_course_users_parameters(),
                array('courseid' => $courseid ));

        $result = (int) $DB->count_records('chat_messages', array('chatid'=>$chat_id, 'userid'=>$user_id));
     
        return array( 'count' => $result );
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_course_users_returns() {

        return new external_single_structure(
            array(
                'count' => new external_value(PARAM_INT, 'Number of messages.'),
            )
        );

    }

}
