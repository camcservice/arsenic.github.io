<?php
include_once __DIR__. '/../../../src/AppBundle/config.php';
include_once __DIR__. '/../../../src/AppBundle/version.php';

$container->setParameter('database_port', 3306);     
$container->setParameter('database_host', DBHOST);
$container->setParameter('database_name', DBNAME);
$container->setParameter('database_user', DBUSER);
$password = DBPASS;
$password = str_replace("%", "%%", $password);//escape % character if any in the password
$container->setParameter('database_password', $password);
$container->setParameter('cspro_rest_api_url', API_URL);
$container->setParameter('csweb_api_files_folder', FILES_FOLDER);
$container->setParameter('csweb_api_default_timezone', DEFAULT_TIMEZONE);
$container->setParameter('csweb_max_script_execution_time', MAX_EXECUTION_TIME);
$container->setParameter('enable_oauth', ENABLE_OAUTH);

$container->setParameter('cspro_version', CSPRO_VERSION);
$container->setParameter('csweb_api_schema_version', SCHEMA_VERSION);
$container->setParameter('csweb_api_version', API_VERSION);

// Set default timezone to avoid warnings when using date/time functions
// and timezone not set in php.ini
date_default_timezone_set(DEFAULT_TIMEZONE);


