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


class wiki_wstemplate_external extends external_api {


    public static function handle_wiki_parameters() {
        return new external_function_parameters(
            array('name' => new external_value(PARAM_TEXT, 'wiki name,', VALUE_DEFAULT, 'Hello world, '),
                  'description' => new external_value(PARAM_TEXT, 'wiki description,', VALUE_DEFAULT, 'Hello world, '),
                  'course_id' => new external_value(PARAM_INT, 'course id ,', VALUE_DEFAULT, 'Hello world, '),

                  'wikimode' => new external_value(PARAM_TEXT, 'course id ,', VALUE_DEFAULT, 'Hello world, '),
                  'firstpagetitle' => new external_value(PARAM_TEXT, 'course id ,', VALUE_DEFAULT, 'Hello world, '),
                  'defaultformat' => new external_value(PARAM_TEXT, 'course id ,', VALUE_DEFAULT, 'Hello world, '),
                  'group_id' => new external_value(PARAM_INT, 'course id ,', VALUE_DEFAULT, 'Hello world, '),
            )
        );
    }


    public static function handle_wiki($name = '',$description='',$course_id=1,$wikimode="",$firstpagetitle="",$defaultformat="",$group_id=1) {

        global $COURSE, $DB;

        $params = self::validate_parameters(self::handle_wiki_parameters(),
                array('name' => $name, 'description' => $description, 'course_id' => $course_id,
                'wikimode'=>$wikimode, 'firstpagetitle'=>$firstpagetitle, 'defaultformat'=>$defaultformat,
                'group_id' => $group_id
            ));

        $course_id = $course_id;
        $section= 6;
        $course_name= $params["name"];

        // mod generator
        $modulename = 'wiki';
        $cm = new stdClass();
        $cm->course             = $course_id;

        $cm->module             = $DB->get_field('modules', 'id', array('name'=>$modulename));
        $cm->instance           = 0;
        $cm->section            = $section;
        $cm->idnumber           = null;
        $cm->added              = time();
        $cm->id					= $DB->insert_record('course_modules', $cm);
    
        course_add_cm_to_section( $course_id, $cm->id, $section);
    

        $wiki = new stdClass();
        $wiki->modulename = 'wiki';
        $wiki->course = $course_id;

        $wiki->module = $cm->module;
        $wiki->name = $course_name;
        $wiki->intro = $description;
        $wiki->wikimode = $wikimode;
        $wiki->firstpagetitle = $firstpagetitle;
        $wiki->defaultformat = $defaultformat;

        $wiki->coursemodule = $cm->id;

        $instance = wiki_add_instance( $wiki );

        $DB->set_field('course_modules', 'instance', $instance, array('id'=> $wiki->coursemodule ));

        $instance = $DB->get_record('wiki', array('id'=>$instance), '*', MUST_EXIST);
        $cm = get_coursemodule_from_id('wiki',  $wiki->coursemodule, $instance->course, true, MUST_EXIST);
        context_module::instance($cm->id);
        rebuild_course_cache($course->id);
        add_to_log($course_id, "course", "add mod",
        "../../mod/$cm->modulename/view.php?id=$cm->id",
        "$cm->modulename $cm->instance");
        add_to_log($course_id, 'wiki', "add",
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
    public static function handle_wiki_returns() {

        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'Whether the user can do the quiz or not.'),
                'hasgrade' => new external_value(PARAM_BOOL, 'Whether the user can do the quiz or not.'),
                
            )
        );

        return new external_value(PARAM_TEXT, 'The welcome message + user first name');
    }

}
