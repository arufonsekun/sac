<?php

@define('DOMAIN_URL', "http://localhost");
@define('PATH_URL', '/sac');
@define('BASE_URL', DOMAIN_URL . PATH_URL);

// Database info
@define('DB_DSN', 'mysql:host=localhost;dbname=sac;port=3307');
@define('DB_USER', 'root');
@define('DB_PASSWORD', 'root');

// Password
@define('PASSWORD_SALT', 'dlaejhdwieugr34712-13fkj3-122045*&@#$)*&Gkdf*%$@I&$fdfd');

// Conference
@define('CONFERENCE_PRICE', 30.0);
@define('CONFERENCE_PRICE_EXTERNAL', 50.0);


// Pagseguro
@define('PAGSEGURO_TOKEN', '46D5FDD7985541D186766294A849B82E');
@define('PAGSEGURO_EMAIL','dovyski@gmail.com');

// System params
@define('DEBUG_MODE', false);
@define('SESSION_NAME', 'saccc');
?>