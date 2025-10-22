<?php
function jsonResponse($success, $message, $data = null) {
    header('Content-Type: application/json');
    echo json_encode([
        "success" => $success,
        "message" => $message,
        "data" => $data
    ]);
    exit;
}

function cleanInput($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}
?>
