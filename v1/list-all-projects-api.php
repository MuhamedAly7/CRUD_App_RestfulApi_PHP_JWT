<?php

// Icluding headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

// Including files
include_once("../config/database.php");
include_once("../classes/Users.php");

// Objects
$db = new Database();
$user_obj = new Users($db);

if($_SERVER['REQUEST_METHOD'] === "GET")
{
    $projects = $user_obj->get_all_projects();

    if($projects->num_rows > 0)
    {
        $projects_arr = array();
        while($row = $projects->fetch_assoc())
        {
            $projects_arr[] = array(
                "id" => $row['id'],
                "name" => $row['name'],
                "description" => $row['description'],
                "user_id" => $row['user_id'],
                "status" => $row['status'],
                "created_at" => $row['created_at']
            );
        }
        http_response_code(200);
        echo json_encode(array(
            "status" => 1,
            "projects" => $projects_arr
        ));
    }
    else
    {
        http_response_code(404);
        echo json_encode(array(
            "status" => 0,
            "message" => "No Projects found!"
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