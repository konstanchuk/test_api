<?php

define('BASE_DIR', dirname(__DIR__));
chdir(BASE_DIR);

require 'init_autoloader.php';

Zend\Mvc\Application::init(require 'config/application.config.php')->run();