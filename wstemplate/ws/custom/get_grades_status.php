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


class get_grades_status_external extends external_api {

    public static function get_grades_status_parameters() {
        return new external_function_parameters(
            array(
                'user_list' => new external_value(PARAM_RAW, 'user list,', VALUE_DEFAULT, '0'),
                'courseid' => new external_value(PARAM_INT, 'course id,', VALUE_DEFAULT, 0),
            )
        );
    }

    public static function get_grades_status($user_list='', $courseid = 0) {
        global $DB;

        $params = self::validate_parameters(self::get_grades_status_parameters(),
                array('user_list' => $user_list, 'courseid' => $courseid ));

        $sql = "
        SELECT u.id as id, u.firstname as name, u.email as email,
        SUM(qa.sumgrades) as current_grade, 
        SUM(qa.grade) as total_grade
        FROM mdl_user as u
        LEFT JOIN (
            SELECT qa.*, q.grade as grade FROM mdl_quiz_attempts as qa
            INNER JOIN mdl_quiz as q ON q.id = qa.quiz and q.course = ".$courseid."
            GROUP BY qa.userid ORDER BY qa.sumgrades DESC
        ) as qa ON qa.userid = u.id
        WHERE u.id in (".$user_list.")
        GROUP BY(u.id);
        ";

        $query_result = $DB->get_recordset_sql($sql);
        $result = array();

        $i = 0;
        foreach($query_result as $r) {

            $temp = array(
                'id' => $r->id,
                'name' => $r->name,
                'email' => $r->email,
                'current_grade' => $r->current_grade ? $r->current_grade : 0,
                'total_grade' => $r->total_grade ? $r->total_grade : 0,
            );
            array_push( $result, $temp );
        }
        
        return $result;
    }

    public static function get_grades_status_returns() {

        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'Number of messages.'),
                    'name' => new external_value(PARAM_RAW, 'Number of quizes.'),
                    'email' => new external_value(PARAM_RAW, 'Number of quizes.'),
                    'current_grade' => new external_value(PARAM_FLOAT, 'Number of records.'),
                    'total_grade' => new external_value(PARAM_FLOAT, 'Number of choices.'),
                )
            ) 
        );
    }

}
