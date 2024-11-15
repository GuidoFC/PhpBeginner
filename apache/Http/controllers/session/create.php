<?php

use Core\Session;

PathGoview('/session/create.view.php', [
    'errors' => Session::get('errors')
]);