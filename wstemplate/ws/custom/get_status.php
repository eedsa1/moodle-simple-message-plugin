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


class get_status_external extends external_api {

    public static function get_status_parameters() {
        return new external_function_parameters(
            array(
                'user_list' => new external_value(PARAM_RAW, 'user list,', VALUE_DEFAULT, ''),
                'date_list' => new external_value(PARAM_RAW, 'date list,', VALUE_DEFAULT, ''),
                'courseid' => new external_value(PARAM_INT, 'course id,', VALUE_DEFAULT, 0),
            )
        );
    }


    public static function get_status($user_list='',$date_list='', $courseid=0) {
        global $DB;

        $params = self::validate_parameters(self::get_status_parameters(),
                array('user_list' => $user_list , 'date_list' => $date_list, 'courseid' => $courseid ));

        $date_array = explode(',', $date_list);

        $sql = "
        SELECT    
        u.c_date as date,
        cm.chat as chat, 
        qa.quiz as quiz, 
        cw.choice as choice
        
        FROM (SELECT STR_TO_DATE('".$date_array[0]."','%Y-%m-%d') as c_date
            UNION ALL SELECT STR_TO_DATE('".$date_array[1]."','%Y-%m-%d')
            UNION ALL SELECT STR_TO_DATE('".$date_array[2]."','%Y-%m-%d')
            UNION ALL SELECT STR_TO_DATE('".$date_array[3]."','%Y-%m-%d')
            UNION ALL SELECT STR_TO_DATE('".$date_array[4]."','%Y-%m-%d')
            UNION ALL SELECT STR_TO_DATE('".$date_array[5]."','%Y-%m-%d')
            UNION ALL SELECT STR_TO_DATE('".$date_array[6]."','%Y-%m-%d')
            UNION ALL SELECT STR_TO_DATE('".$date_array[7]."','%Y-%m-%d')
            UNION ALL SELECT STR_TO_DATE('".$date_array[8]."','%Y-%m-%d')) as u   

        LEFT JOIN (SELECT mcm.userid, COUNT(mcm.id) as chat, from_unixtime(mcm.timestamp,'%Y-%m-%d') as timestamp FROM mdl_chat_messages as mcm LEFT JOIN mdl_chat as c ON c.id = mcm.chatid WHERE c.course = ".$courseid." GROUP BY MONTH(from_unixtime(mcm.timestamp,'%Y-%m-%d'))) as cm on cm.userid in (".$user_list.") and MONTH(cm.timestamp) = MONTH(u.c_date) and YEAR(cm.timestamp) = YEAR(u.c_date)
        LEFT JOIN (SELECT mqa.userid, COUNT(mqa.timefinish) as quiz, from_unixtime(mqa.timefinish,'%Y-%m-%d') as timefinish FROM mdl_quiz_attempts as mqa LEFT JOIN mdl_quiz as q ON q.id = mqa.quiz WHERE q.course = ".$courseid." GROUP BY MONTH(from_unixtime(mqa.timefinish,'%Y-%m-%d'))) as qa ON qa.userid in (".$user_list.") and MONTH(qa.timefinish) = MONTH(u.c_date) and YEAR(qa.timefinish) = YEAR(u.c_date)
        LEFT JOIN (SELECT mca.userid, COUNT(mca.timemodified) as choice, from_unixtime(mca.timemodified,'%Y-%m-%d') as timemodified FROM mdl_choice_answers as mca LEFT JOIN mdl_choice as ch ON ch.id = mca.choiceid WHERE ch.course = ".$courseid." GROUP BY MONTH(from_unixtime(mca.timemodified,'%Y-%m-%d'))) as cw ON cw.userid in (".$user_list.") and MONTH(cw.timemodified) = MONTH(u.c_date) and YEAR(cw.timemodified) = YEAR(u.c_date)
        
        GROUP BY(u.c_date);
        ";

        $query_result = $DB->get_recordset_sql($sql);
        $result = array();

        $i = 0;
        foreach($query_result as $r) {

            $temp = array(
                'chat' => $r->chat,
                'quiz' => $r->quiz,
                'choice' => $r->choice,
                'date' => $r->date,
            );
            array_push( $result, $temp );
        }
        
        return $result;
    }

    public static function get_status_returns() {

        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'chat' => new external_value(PARAM_INT, 'Number of messages.'),
                    'quiz' => new external_value(PARAM_INT, 'Number of quizes.'),
                    'choice' => new external_value(PARAM_INT, 'Number of choices.'),
                    'date' => new external_value(PARAM_TEXT, 'date.'),
                )
            ) 
        );
    }

}
