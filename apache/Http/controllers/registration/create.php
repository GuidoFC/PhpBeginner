<?php

use Core\Session;

PathGoview('registration/create.view.php', [
    'errors' => Session::get('errors')
]);