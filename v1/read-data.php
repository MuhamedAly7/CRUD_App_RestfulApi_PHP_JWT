<?php

// Include vendor
require '../vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

// Including headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-type: application/json; charset=UTF-8");


// Including files
include_once("../config/database.php");
include_once("../classes/Users.php");

// Objects
$db = new Database();
$user_obj = new Users($db);

if($_SERVER['REQUEST_METHOD'] === "POST")
{
    // $data = json_decode(file_get_contents("php://input"));

    $all_headers = getallheaders();
    $jwt = $all_headers['Authorization'];

    if(!empty($jwt))
    {
        try
        {

            $secret_key = "owt125";
            $decoded_data = JWT::decode($jwt, new Key($secret_key, 'HS256'));
            http_response_code(200);
            echo json_encode(array(
                "status" => 1,
                "message" => "We got JWT token",
                "user_data" => $decoded_data->data
            ));
        }
        catch(Exception $e)
        {
            http_response_code(500); // server error
            echo json_encode(array(
                "status" => 0,
                "message" => $e->getMessage()
            ));
        }

        
    }
}
else
{
    http_response_code(500);
    echo json_encode(array(
        "status" => 0,
        "message" => "Access Denied!"
    ));
}

?>