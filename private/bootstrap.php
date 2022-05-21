<?php

//bottstrop fails ir pirmais fails no php, kurš ielādējas
//Atkļūdošanas režīms. izvērstā veidā
define('DEBUG_MODE', true);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('DB_SERVER_NAME', 'localhost');
define('DB_NAME', 'contacts'); //DB nosaukums
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'root');

if (DEBUG_MODE) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

//echo "test";

// Izsaucot, tiks mēģināts jebkuru klasi includot. Automātiski caur require includos failus, kad mēģinās piekļūt kādai klasei. Ņemot vērā klases nosaukumu. 
// vispār mēs lādēsim klāt failus, pieliekot klases vārdu
// iekš tekošās mapes - privat, meklējam mapi classes
// ņemot vērā klases nosaukumu $class un tad pieliekam klāt ceļu un noteikto klasi. 
spl_autoload_register(function ($class) {
    //Tiks aizvietoti noteiktie simboli. Tiks noteikts pilnais ceļš uz failu.
    $file = __DIR__ . '/classes/' . str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php'; 
    //echo $file;
    // šeit fails ielādēts
    if (file_exists($file)) { 
        //caur require includos failus, kad mēģināsim piekļūt kādai klasei
        require $file;
        return true;
    }
    return false;
});

//Lai šo notestētu, vajag DB.php failā nodefinēt klasi ->  class DB