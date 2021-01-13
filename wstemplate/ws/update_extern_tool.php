
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

class update_extern_tool extends external_api {


    public static function handle_lti_parameters() {
        return new external_function_parameters(
            array('name' => new external_value(PARAM_TEXT, 'lti name,', VALUE_DEFAULT, 'Hello world, '),
                  'description' => new external_value(PARAM_TEXT, 'lti description,', VALUE_DEFAULT, 'Hello world, '),
                  'lti_id' => new external_value(PARAM_INT, 'lti id ,', VALUE_DEFAULT, 'Hello world, '),
                 
                  'typeid' => new external_value(PARAM_INT, 'course id ,', VALUE_DEFAULT, 'Hello world, '),
                  'toolurl' => new external_value(PARAM_TEXT, 'course id ,', VALUE_DEFAULT, 'Hello world, '),
                  'securetoolurl' => new external_value(PARAM_TEXT, 'course id ,', VALUE_DEFAULT, 'Hello world, '),

                  'showdescription' => new external_value(PARAM_INT, 'course id ,', VALUE_DEFAULT, 0),
                  'showtitlelaunch' => new external_value(PARAM_TEXT, 'course id ,', VALUE_DEFAULT, 0),
                  'showdescriptionlaunch' => new external_value(PARAM_TEXT, 'course id ,', VALUE_DEFAULT, 0),

                  'launchcontainer' => new external_value(PARAM_TEXT, 'course id ,', VALUE_DEFAULT, 'Hello world, '),
                  'resourcekey' => new external_value(PARAM_TEXT, 'lti name,', VALUE_DEFAULT, 'Hello world, '),
                  'password' => new external_value(PARAM_TEXT, 'lti name,', VALUE_DEFAULT, 'Hello world, '),
                  'instructorcustomparameters' => new external_value(PARAM_TEXT, 'lti name,', VALUE_DEFAULT, 'Hello world, '),
                  )
        );
    }

    public function get_string_value( $value ) {
        if( is_null( $value) )
            return "";
        else{
            return $value;
        }
    }

    public static function handle_lti($name = '',$description='',$lti_id=1,
        $typeid='', $toolurl='', $securetoolurl='',$launchcontainer=0,$resourcekey=0,
        $showdescription=0, $showtitlelaunch=0,$showdescriptionlaunch=0,
        $password='',$instructorcustomparameters=''
        ) {

        global $COURSE, $DB;

        $params = self::validate_parameters(self::handle_lti_parameters(),
                array(
                    'name' => $name, 'description' => $description, 'lti_id' => $lti_id,
                    'showdescription' => $showdescription, 'showtitlelaunch' => $showtitlelaunch, 'showdescriptionlaunch' => $showdescriptionlaunch,
                'typeid'=>$typeid,'toolurl'=>$toolurl,'securetoolurl'=>$securetoolurl,'launchcontainer'=>$launchcontainer,
                'resourcekey'=>$resourcekey, 'password'=>$password, 'instructorcustomparameters'=>$instructorcustomparameters,
             ));

        $lti = new stdClass();
        $lti->modulename = 'lti';
        $lti->id = $lti_id;

        $lti->name = $name;
        $lti->intro = $description;

        $lti->showdescription = $showdescription;
        $lti->showtitlelaunch = $showtitlelaunch;
        $lti->showdescriptionlaunch = $showdescriptionlaunch;

        $lti->typeid = $typeid;
        $lti->launchcontainer = $launchcontainer;

        $lti->toolurl = update_extern_tool::get_string_value($toolurl);
        $lti->securetoolurl = update_extern_tool::get_string_value($securetoolurl);

        $lti->resourcekey = update_extern_tool::get_string_value($resourcekey);
        $lti->password = update_extern_tool::get_string_value($password);
        $lti->instructorcustomparameters = update_extern_tool::get_string_value($instructorcustomparameters);

        $DB->update_record('lti', $lti);
        
        $instance = $DB->get_record('lti', array('id'=> $lti->id), '*', MUST_EXIST);
        $cm = get_coursemodule_from_instance('lti', $lti->id, $instance->course);

        context_module::instance($cm->id);
        rebuild_course_cache($course->id);
        add_to_log($course_id, "course", "add mod",
        "../../mod/$cm->modulename/view.php?id=$cm->id",
        "$cm->modulename $cm->instance");
        add_to_log($course_id, 'lti', "add",
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
    public static function handle_lti_returns() {

        return new external_single_structure(
            array(
                'sucess' => new external_value(PARAM_BOOL, 'Whether the user can do the quiz or not.'),
                
            )
        );

        return new external_value(PARAM_TEXT, 'The welcome message + user first name');
    }

}
