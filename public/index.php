<?php

//require_once dirname(__DIR__).'/Classes/DB.php';
require_once dirname(__DIR__).'/Controllers/Controller.php';

$query = $_SERVER['QUERY_STRING'];

Controller::init($query);
