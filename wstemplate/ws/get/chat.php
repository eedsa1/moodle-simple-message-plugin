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


class get_chat extends external_api {

    public static function handle_chat_parameters() {
        return new external_function_parameters(
            array(
                  'chat_id' => new external_value(PARAM_INT, 'chat description,', VALUE_DEFAULT, 'Hello world, '),
                  'course_id' => new external_value(PARAM_INT, 'course id ,', VALUE_DEFAULT, 'Hello world, '),
            )
        );
    }

    public static function handle_chat($chat_id='',$course_id=1) {

        global $COURSE, $DB;

        $params = self::validate_parameters(self::handle_chat_parameters(),
                array('chat_id' => $chat_id, 'course_id' => $course_id ));

        $instance = $DB->get_record('chat', array('id'=>$chat_id), '*', MUST_EXIST);

        $result = array();
        $result['id'] = $instance->id;
        $result['name'] = $instance->name;
        $result['description'] = $instance->intro;
     
        return $result;
    
    }

    public static function handle_chat_returns() {

        return new external_single_structure(
            array(
                array(
                    'id' => new external_value(PARAM_INT, 'Whether the user can do the quiz or not.'),
                    'name' => new external_value(PARAM_TEXT, 'Whether the user can do the quiz or not.'),
                    'description' => new external_value(PARAM_TEXT, 'Whether the user can do the quiz or not.'),
                    
                )
            )
        );
    }

}
