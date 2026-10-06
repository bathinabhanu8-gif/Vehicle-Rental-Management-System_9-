<?php

define('APP_NAME', 'VS Travells');

$renderUrl = getenv('RENDER_EXTERNAL_URL');

if ($renderUrl) {
    define('BASE_URL', rtrim($renderUrl, '/'));
} else {
    define('BASE_URL', 'http://localhost/VehicleRentalSystem');
}
