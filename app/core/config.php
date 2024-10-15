<?php
if($_SERVER['SERVER_NAME'] == "localhost" || $_SERVER['SERVER_NAME'] == "127.0.0.1"){ 
    /* Database Config */
    define('DBNAME','minutematedb'); 
    define('DBHOST','localhost');
    define('DBUSER','root');
    define('DBPASS','');
    define('DBDRIVER','');

    define('ROOT','http://localhost/minutemate/public'); //absolute path 
}
else{
    /* Database Config */

    define('DBNAME','minutematedb'); 
    define('DBHOST','localhost');
    define('DBUSER','root');
    define('DBPASS','');
    define('DBDRIVER','');

    define('ROOT','SERVERNAME');
}

define("APP_Name","Minutemate");
define("APP_VERSION","1.0.0");
define("APP_AUTHOR","Minutemate Team");

/*True -> show errors (debug mode on) */
define("DEBUG",true);