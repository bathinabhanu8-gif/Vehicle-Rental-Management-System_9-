<?php

require_once '../middleware/jsonMiddleware.php';
require_once '../middleware/requestLogger.php';
require_once '../utils/response.php';

setJsonResponse();
logRequest();

sendResponse(
    false,
    'API endpoint not found',
    null,
    404
);