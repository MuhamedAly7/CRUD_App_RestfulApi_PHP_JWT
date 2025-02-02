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
    // body
    $data = json_decode(file_get_contents("php://input"));

    $headers = getallheaders();

    if(!empty($data->name) && !empty($data->description) && !empty($data->status))
    {
        try
        {
            $jwt = $headers["Authorization"];
            $secret_key = "owt125";
            $decoded_data = JWT::decode($jwt, new Key($secret_key, 'HS256'));

            $user_obj->user_id = $decoded_data->data->id;
            $user_obj->project_name = $data->name;
            $user_obj->description = $data->description;
            $user_obj->status = $data->status;

            if($user_obj->create_project())
            {
                http_response_code(200);
                echo json_encode(array(
                    "status" => 1,
                    "message" => "Project has beed created successfully!"
                ));
            }
            else
            {
                http_response_code(500);
                echo json_encode(array(
                    "status" => 0,
                    "message" => "Failed to create project!"
                ));
            }
            
        }
        catch(Exception $e)
        {
            http_response_code(500);
            echo json_encode(array(
                "status" => 0,
                "message" => $e->getMessage()
            ));
        }
    }
    else
    {
        http_response_code(404);
        echo json_encode(array(
            "status" => 0,
            "message" => "All data needed!"
        ));
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