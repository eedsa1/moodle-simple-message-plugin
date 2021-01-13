<?php

require_once($CFG->dirroot.'/config.php');
require_once($CFG->libdir . "/externallib.php");

class atid_observer  {

    public static function quiz_submitted(\core\event\base $event) {
        global $DB,$CFG;
        
        $attempt = $DB->get_record('quiz_attempts', array('id'=>$event->objectid), '*', MUST_EXIST);
        
        $data_field = array(
            'id_quiz' => $attempt->quiz,
            'id_course' => $event->courseid,
            'id_user' => $event->relateduserid, 
            'url_item' => $CFG->wwwroot,
        );
        
        $paramsArr = [];

        foreach($data_field  as $param => $value) {
            $paramsArr[] = "$param=$value";
        }
        
        $joined = implode('&', $paramsArr);
    
        $url = "http://localhost:5000/moodle/events/quiz/";
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $joined);

        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13");
        $data = curl_exec($ch);
        curl_close($ch);

    }

    public static function user_enrolled(\core\event\base $event) {
        global $CFG;

        $data_field = array(
            'id_course' => $event->courseid,
            'id_user' => $event->relateduserid,
            'url_item' => $CFG->wwwroot,
        );

        $paramsArr = [];

        foreach($data_field  as $param => $value) {
            $paramsArr[] = "$param=$value";
        }
        
        $joined = implode('&', $paramsArr);
    
        $url = "http://localhost:5000/moodle/events/enrolment/";
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $joined);

        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13");
        $data = curl_exec($ch);
        curl_close($ch);
    }

    public static function message_sent(\core\event\base $event) {

        global $USER;
        global $PAGE;
        global $CFG;

        global $DB;

        $chat_messages = $DB->get_record('chat_messages', array('id'=>$event->objectid), '*', MUST_EXIST);

        $data_field = array(
            'id_chat' => $chat_messages->chatid,
            'id_course' => $event->courseid,
            'id_user' => $event->relateduserid,  
            'url_item' => $CFG->wwwroot,
        );

        $paramsArr = [];
        foreach($data_field  as $param => $value) {
            $paramsArr[] = "$param=$value";
        }
        
        $joined = implode('&', $paramsArr);
    
        $url = "http://localhost:5000/moodle/events/chat/";

        self::send_request( $joined , $url);;

    }

    public static function mod_updated(\core\event\base $event) {
    
        global $PAGE;

        $data_field = array(
            'id_course' => $event->courseid,
            'type_item' => $event->other['modulename'], 
            'id_item' => $event->other['instanceid'],
            'url_item' => $PAGE->url,
        );

        $paramsArr = [];

        foreach($data_field  as $param => $value) {
            $paramsArr[] = "$param=$value";
        }
        
        $joined = implode('&', $paramsArr);
    
        $url = "http://localhost:5000/moodle/update/";

        self::send_request( $joined , $url);
    
    }

    public static function send_request( $joined, $url ){
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $joined);

        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13");
        $data = curl_exec($ch);
        curl_close($ch);
    }

}
