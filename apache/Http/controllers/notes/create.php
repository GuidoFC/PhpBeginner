<?php
// Coger una traza para saber como ha llegado aqui!!

echo '<pre>'; // Formatea la salida para mejor legibilidad en un navegador
print_r(debug_backtrace());
echo '</pre>';


view("notes/create.view.php", [
    'heading' => 'Create a Note',
    'errors' =>[]
]);
