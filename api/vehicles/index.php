<?php

header("Content-Type: application/json");

$method = $_SERVER["REQUEST_METHOD"];

if ($method === "GET") {

    $vehicles = [
        [
            "id" => 1,
            "name" => "Hyundai Creta",
            "type" => "Car",
            "pricePerDay" => 2500,
            "status" => "Available"
        ],
        [
            "id" => 2,
            "name" => "Honda City",
            "type" => "Car",
            "pricePerDay" => 2200,
            "status" => "Available"
        ],
        [
            "id" => 3,
            "name" => "Royal Enfield Classic 350",
            "type" => "Bike",
            "pricePerDay" => 1200,
            "status" => "Available"
        ]
    ];

    echo json_encode([
        "success" => true,
        "data" => $vehicles
    ]);

} else {

    http_response_code(405);

    echo json_encode([
        "success" => false,
        "message" => "Method not allowed"
    ]);
}

?>