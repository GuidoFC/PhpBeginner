<?php
use Core\Authenticator;

$authenticator = new Authenticator();
$authenticator->logout();

redirect('/');
