<?php
    namespace Application\Core;
    
    /**
    * The SessionHandler handles
    * starting sessions
    */
    class SessionHandler
    {
        /** @var string Name of the session */
        private static $_sessionName = 'secure_session_name';
        /** @var string Initiated session key */
        private static $_initiatedKey = 'secure_initiated_key';
        /** @var string User agent key */
        private static $_userAgentKey = 'secre_user_agent_key';
        /** @var string Salt key */
        private static $_salt = 'secure_salt';
        /** @var string User sessions key */
        private static $_userSessionsKey = 'user_sessions_key';
        /** @var string User cookies key */
        private static $_userCookiesKey = 'user_cookies_key';

        public static function destroy() {
            session_unset(); session_destroy();
        }

        private static function configuration($config) {
            foreach($config as $key => $value) {
                $name='_'.$key;
                self::$$name=$value;
            }
        }

        public static function start() {
            self::configuration(json_decode(App::config()->file('Sessions.Sessions.json')->read(), true));
            if(session_status()==PHP_SESSION_NONE) {
                ini_set('session.cookie_httponly', true);
                //start session
                //set session name
                session_name(self::$_sessionName);
                session_start();
                //generate key
                Session::generate(self::$_userSessionsKey);
                Cookie::generate(self::$_userCookiesKey);
                //check initiated status
                if(intval(Session::get(self::$_initiatedKey))==0) {
                    //regen
                    session_regenerate_id();
                    Session::generate(self::$_userSessionsKey);
                    Session::set(self::$_initiatedKey, 1);
                }
                //check for corresponding user agent on same session
                if(Session::get(self::$_userAgentKey)!==false) {
                    if(Session::get(self::$_userAgentKey)!=hash('sha512', $_SERVER['HTTP_USER_AGENT'].self::$_salt)) {
                        //invalid user agent detected
                        self::destroy(); die();
                    }
                } else { Session::set(self::$_userAgentKey, hash('sha512', $_SERVER['HTTP_USER_AGENT'].self::$_salt)); }
            }
        }
    }
