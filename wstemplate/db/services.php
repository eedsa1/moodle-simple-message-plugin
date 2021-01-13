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
 * Web service local plugin template external functions and service definitions.
 *
 * @package    localwstemplate
 * @copyright  2011 Jerome Mouneyrac
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// We defined the web service functions to install.
$functions = array(
        'get_grades_status' => array(
                'classname'     => 'get_grades_status_external',
                'methodname'    => 'get_grades_status',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'classpath'   => 'local/wstemplate/ws/custom/get_grades_status.php',
                'type'          => 'read',
        ),
        'get_status' => array(
                'classname'     => 'get_status_external',
                'methodname'    => 'get_status',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'classpath'   => 'local/wstemplate/ws/custom/get_status.php',
                'type'          => 'read',
        ),
        'group_chat' => array(
                'classname'     => 'group_chat_manager',
                'methodname'    => 'group_chat',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'classpath'   => 'local/wstemplate/ws/group/chat.php',
                'type'          => 'read',
        ),
        'group_choice' => array(
                'classname'     => 'group_choice_manager',
                'methodname'    => 'group_choice',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'classpath'   => 'local/wstemplate/ws/group/choice.php',
                'type'          => 'read',
        ),
        'group_data' => array(
                'classname'     => 'group_data_manager',
                'methodname'    => 'group_data',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'classpath'   => 'local/wstemplate/ws/group/data.php',
                'type'          => 'read',
        ),
        'group_lti' => array(
                'classname'     => 'group_lti_manager',
                'methodname'    => 'group_lti',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'classpath'   => 'local/wstemplate/ws/group/externtool.php',
                'type'          => 'read',
        ),
        'group_forum' => array(
                'classname'     => 'group_forum_manager',
                'methodname'    => 'group_forum',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'classpath'   => 'local/wstemplate/ws/group/forum.php',
                'type'          => 'read',
        ),
        'group_glossary' => array(
                'classname'     => 'group_glossary_manager',
                'methodname'    => 'group_glossary',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'classpath'   => 'local/wstemplate/ws/group/glossary.php',
                'type'          => 'read',
        ),
        'group_quiz' => array(
                'classname'     => 'group_quiz_manager',
                'methodname'    => 'group_quiz',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'classpath'   => 'local/wstemplate/ws/group/quiz.php',
                'type'          => 'read',
        ),
        'group_wiki' => array(
                'classname'     => 'group_wiki_manager',
                'methodname'    => 'group_wiki',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'classpath'   => 'local/wstemplate/ws/group/wiki.php',
                'type'          => 'read',
        ),


        
        'count_messages_by_user' => array(
                'classname'     => 'count_messages_by_user',
                'methodname'    => 'count_messages',
                'description' => 'Return number of chat messages by userid',
                'classpath'   => 'local/wstemplate/ws/custom/count_messages_by_user.php',
                'type'          => 'read',
        ),
        'get_chat' => array(
                'classname'     => 'get_chat',
                'methodname'    => 'handle_chat',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'classpath'   => 'local/wstemplate/ws/get/chat.php',
                'type'          => 'read',
        ),
        'get_data' => array(
                'classname'     => 'get_data',
                'methodname'    => 'handle_data',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'classpath'   => 'local/wstemplate/ws/get/database.php',
                'type'          => 'read',
        ),
        'get_forum' => array(
                'classname'     => 'get_forum',
                'methodname'    => 'handle_forum',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'classpath'   => 'local/wstemplate/ws/get/forum.php',
                'type'          => 'read',
        ),
        'get_lti' => array(
                'classname'     => 'get_lti',
                'methodname'    => 'handle_lti',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'classpath'   => 'local/wstemplate/ws/get/externtool.php',
                'type'          => 'read',
        ),
        'get_quiz' => array(
                'classname'     => 'get_quiz',
                'methodname'    => 'handle_quiz',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'classpath'   => 'local/wstemplate/ws/get/quiz.php',
                'type'          => 'read',
        ),
        'get_wiki' => array(
                'classname'     => 'get_wiki',
                'methodname'    => 'handle_wiki',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'classpath'   => 'local/wstemplate/ws/get/wiki.php',
                'type'          => 'read',
        ),

        'create_chat' => array(
                'classname'     => 'local_wstemplate_external',
                'methodname'    => 'handle_chat',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'classpath'   => 'local/wstemplate/ws/chat.php',
                'type'          => 'read',
        ),
        'update_chat' => array(
                'classname'     => 'update_chat',
                'methodname'    => 'handle_chat',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'classpath'   => 'local/wstemplate/ws/update_chat.php',
                'type'          => 'read',
        ),
        'create_data' => array(
                'classname'     => 'database_wstemplate_external',
                'methodname'    => 'handle_data',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'classpath'   => 'local/wstemplate/ws/database.php',
                'type'          => 'read',
        ),
        'update_database' => array(
                'classname'     => 'update_database',
                'methodname'    => 'handle_data',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'classpath'   => 'local/wstemplate/ws/update_database.php',
                'type'          => 'read',
        ),
        'create_forum' => array(
                'classname'     => 'forum_wstemplate_external',
                'methodname'    => 'handle_forum',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'classpath'   => 'local/wstemplate/ws/forum.php',
                'type'          => 'read',
        ),
        'update_forum' => array(
                'classname'     => 'update_forum',
                'methodname'    => 'handle_forum',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'classpath'   => 'local/wstemplate/ws/update_forum.php',
                'type'          => 'read',
        ),
        'local_wstemplate_handle_lti' => array(
                'classname'     => 'local_wstemplate_handle_lti',
                'methodname'    => 'handle_lti',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'classpath'   => 'local/wstemplate/ws/extern_tool.php',
                'type'          => 'read',
        ),
        'update_extern_tool' => array(
                'classname'     => 'update_extern_tool',
                'methodname'    => 'handle_lti',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'classpath'   => 'local/wstemplate/ws/update_extern_tool.php',
                'type'          => 'read',
        ),
        'create_glossary' => array(
                'classname'     => 'glossario_wstemplate_external',
                'methodname'    => 'handle_glossary',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'classpath'   => 'local/wstemplate/ws/glossario.php',
                'type'          => 'read',
        ),
        'update_glossario' => array(
                'classname'     => 'update_glossario',
                'methodname'    => 'handle_glossary',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'classpath'   => 'local/wstemplate/ws/update_glossario.php',
                'type'          => 'read',
        ),
        'create_wiki' => array(
                'classname'     => 'wiki_wstemplate_external',
                'methodname'    => 'handle_wiki',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'classpath'   => 'local/wstemplate/ws/wiki.php',
                'type'          => 'read',
        ),
        'update_wiki' => array(
                'classname'     => 'update_wiki',
                'methodname'    => 'handle_wiki',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'classpath'   => 'local/wstemplate/ws/update_wiki.php',
                'type'          => 'read',
        ),
        'create_choice' => array(
                'classname'   => 'choice_wstemplate_external',
                'methodname'  => 'handle_choice',
                'classpath'   => 'local/wstemplate/ws/choice.php',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'type'        => 'read',
        ),
        'update_choice' => array(
                'classname'   => 'update_choice',
                'methodname'  => 'handle_choice',
                'classpath'   => 'local/wstemplate/ws/update_choice.php',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'type'        => 'read',
        ),
        'create_choice_option' => array(
                'classname'   => 'choice_option_wstemplate_external',
                'methodname'  => 'handle_choice_options',
                'classpath'   => 'local/wstemplate/ws/choice_option.php',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'type'        => 'read',
        ),
        'update_choice_option' => array(
                'classname'   => 'update_choice_option',
                'methodname'  => 'handle_choice_options',
                'classpath'   => 'local/wstemplate/ws/update_choice_option.php',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'type'        => 'read',
        ),
        'create_quiz' => array(
                'classname'   => 'quiz_wstemplate_external',
                'methodname'  => 'get_quizzes',
                'classpath'   => 'local/wstemplate/ws/quiz.php',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'type'        => 'read',
        ),
        'update_quiz' => array(
                'classname'   => 'update_quiz',
                'methodname'  => 'get_quizzes',
                'classpath'   => 'local/wstemplate/ws/update_quiz.php',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'type'        => 'read',
        ),

        
);

// We define the services to install as pre-build services. A pre-build service is not editable by administrator.
$services = array(
        'My service' => array(
                'functions' => array ('create_chat','create_data', 'create_forum', 
                'create_glossary', 'create_wiki','create_choice','create_choice_option',
                'create_quiz'
        ),
                'restrictedusers' => 0,
                'enabled'=>1,
        )
);

/*
'local_wstemplate_handle_assign',
                'create_choice', 'create_data', 'local_wstemplate_handle_lti','local_wstemplate_handle_feedback',
                'create_forum','create_glossary','local_wstemplate_handle_lesson','local_wstemplate_get_quizzes',
                'local_wstemplate_handle_survey',

   'local_wstemplate_handle_assign' => array(
                'classname'     => 'local_wstemplate_external',
                'methodname'    => 'handle_assign',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'classpath'   => 'local/wstemplate/ws/assign.php',
                'type'          => 'read',
        ),
        'create_choice' => array(
                'classname'   => 'local_wstemplate_external',
                'methodname'  => 'handle_choice',
                'classpath'   => 'local/wstemplate/ws/choice.php',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'type'        => 'read',
        ),

        'create_data' => array(
                'classname'     => 'local_wstemplate_external',
                'methodname'    => 'handle_data',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'classpath'   => 'local/wstemplate/ws/database.php',
                'type'          => 'read',
        ),
        'local_wstemplate_handle_lti' => array(
                'classname'     => 'local_wstemplate_external',
                'methodname'    => 'handle_lti',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'classpath'   => 'local/wstemplate/ws/extern_tool.php',
                'type'          => 'read',
        ),
        'local_wstemplate_handle_feedback' => array(
                'classname'   => 'local_wstemplate_external',
                'methodname'  => 'handle_feedback',
                'classpath'   => 'local/wstemplate/ws/feedback.php',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'type'        => 'read',
        ),

        'create_forum' => array(
                'classname'     => 'local_wstemplate_external',
                'methodname'    => 'handle_forum',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'classpath'   => 'local/wstemplate/ws/forum.php',
                'type'          => 'read',
        ),
        'create_glossary' => array(
                'classname'     => 'local_wstemplate_external',
                'methodname'    => 'handle_glossary',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'classpath'   => 'local/wstemplate/ws/glossario.php',
                'type'          => 'read',
        ),
        'local_wstemplate_handle_lesson' => array(
                'classname'     => 'local_wstemplate_external',
                'methodname'    => 'handle_lesson',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'classpath'   => 'local/wstemplate/ws/lesson.php',
                'type'          => 'read',
        ),
        'local_wstemplate_get_quizzes' => array(
                'classname'     => 'local_wstemplate_external',
                'methodname'    => 'get_quizzes',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'classpath'   => 'local/wstemplate/ws/quiz.php',
                'type'          => 'read',
        ),
        'local_wstemplate_handle_survey' => array(
                'classname'     => 'local_wstemplate_external',
                'methodname'    => 'handle_survey',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'classpath'   => 'local/wstemplate/ws/survey.php',
                'type'          => 'read',
        ),
 */