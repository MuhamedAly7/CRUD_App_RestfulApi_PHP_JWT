<?php

class Users
{
    // define properties
    public $name;
    public $email;
    public $password;
    public $user_id;
    public $project_name;
    public $description;
    public $status;


    private $conn;
    private $users_table;
    private $projects_table;


    public function __construct($db)
    {
        $this->conn = $db->connect();
        $this->users_table = "tbl_users";
        $this->projects_table = "tbl_projects";
    }

    public function create_user()
    {
        $user_query = "INSERT INTO " . $this->users_table . " SET name = ?, email = ?, password = ?";
        $user_obj = $this->conn->prepare($user_query);
        $user_obj->bind_param("sss", $this->name, $this->email, $this->password);
        if($user_obj->execute())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function check_email()
    {
        $email_query = "SELECT * FROM " . $this->users_table . " WHERE email = ?";
        $user_obj = $this->conn->prepare($email_query);
        $user_obj->bind_param("s", $this->email);
        if($user_obj->execute())
        {
            $data = $user_obj->get_result();
            return $data->fetch_assoc();
        }
        else
        {
            return array();
        }
    }

    public function check_login()
    {
        $email_query = "SELECT * FROM " . $this->users_table . " WHERE email = ?";
        $user_obj = $this->conn->prepare($email_query);
        $user_obj->bind_param("s", $this->email);
        if($user_obj->execute())
        {
            $data = $user_obj->get_result();
            return $data->fetch_assoc();
        }
        else
        {
            return array();
        }
    }

    public function create_project()
    {
        $project_query = "INSERT INTO " . $this->projects_table . " SET user_id = ?, name = ?, description = ?, status = ?";
        $project_obj = $this->conn->prepare($project_query);

        $project_name = htmlspecialchars(strip_tags($this->project_name));
        $project_name = htmlspecialchars(strip_tags($this->description));
        $project_name = htmlspecialchars(strip_tags($this->status));

        $project_obj->bind_param("isss", $this->user_id, $this->project_name, $this->description, $this->status);

        if($project_obj->execute())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function get_all_projects()
    {
        $projects_query = "SELECT * FROM " . $this->projects_table . " ORDER BY id DESC";
        $projects_obj = $this->conn->prepare($projects_query);
        $projects_obj->execute();
        return $projects_obj->get_result();
    }
}


?>