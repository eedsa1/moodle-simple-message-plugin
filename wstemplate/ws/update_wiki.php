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
require_once($CFG->dirroot . "/mod/wiki/lib.php");


class update_wiki extends external_api {

    public static function handle_wiki_parameters() {
        return new external_function_parameters(
            array('name' => new external_value(PARAM_TEXT, 'wiki name,', VALUE_DEFAULT, 'Hello world, '),
                  'description' => new external_value(PARAM_TEXT, 'wiki description,', VALUE_DEFAULT, 'Hello world, '),
                  'wiki_id' => new external_value(PARAM_INT, 'course id ,', VALUE_DEFAULT, 'Hello world, '),

                  'wikimode' => new external_value(PARAM_TEXT, 'course id ,', VALUE_DEFAULT, 'Hello world, '),
                  'firstpagetitle' => new external_value(PARAM_TEXT, 'course id ,', VALUE_DEFAULT, 'Hello world, '),
                  'defaultformat' => new external_value(PARAM_TEXT, 'course id ,', VALUE_DEFAULT, 'Hello world, '),
                 
            )
        );
    }


    public static function handle_wiki($name = '',$description='',$wiki_id=1,$wikimode="",$firstpagetitle="",$defaultformat="") {

        global $COURSE, $DB;

        $params = self::validate_parameters(self::handle_wiki_parameters(),
                array('name' => $name, 'description' => $description, 'wiki_id' => $wiki_id,
                'wikimode'=>$wikimode, 'firstpagetitle'=>$firstpagetitle, 'defaultformat'=>$defaultformat
            ));

        $wiki = new stdClass();
        $wiki->modulename = 'wiki';
        $wiki->id = $wiki_id;

        $wiki->name = $name;
        $wiki->intro = $description;
        $wiki->wikimode = $wikimode;
        $wiki->firstpagetitle = $firstpagetitle;
        $wiki->defaultformat = $defaultformat;

        $DB->update_record('wiki', $wiki);
        
        $instance = $DB->get_record('wiki', array('id'=> $wiki->id), '*', MUST_EXIST);
        $cm = get_coursemodule_from_instance('wiki', $wiki->id, $instance->course);

        context_module::instance($cm->id);
        rebuild_course_cache($course->id);
        add_to_log($course_id, "course", "add mod",
        "../../mod/$cm->modulename/view.php?id=$cm->id",
        "$cm->modulename $cm->instance");
        add_to_log($course_id, 'wiki', "add",
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
    public static function handle_wiki_returns() {

        return new external_single_structure(
            array(
                'sucess' => new external_value(PARAM_BOOL, 'Whether the user can do the quiz or not.'),
                
            )
        );

        return new external_value(PARAM_TEXT, 'The welcome message + user first name');
    }

}
