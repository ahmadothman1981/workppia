<?php
namespace App\Controllers;

use Framework\Database;
use Framework\Validation;
use Framework\Session;

class UserController
{
    protected $db;

    public function __construct()
    {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }

    /**
     * show the login page 
     * 
     * @return void
     */
    public function login()
    {
        loadView('users/login');
    }

    /**
     * show the register page 
     * 
     * @return void
     */
    public function create()
    {
        loadView('users/create');
    }

    /**
     * Summary of store
     * @return void
     */
    public function store()
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $password = $_POST['password'];
        $passwordConfimation = $_POST['password_confirmation'];

        $errors = [];
        if(!Validation::string($email))
        {
            $errors['email'] = "Email is required.";
        }
        if(!Validation::string($name , 2 ,55))
        {
            $errors['name'] = "name is required and must be between 2 , 55 charachters.";
        }
        if(!Validation::string($password , 6 ,55))
        {
            $errors['password'] = "password is required.";
        }
        if(!Validation::match($password , $passwordConfimation))
        {
            $errors['password_confirmation'] = "password confirmation must be match the password.";
        }
        if(!empty($errors))
        {
            loadView('users/create', ['errors' => $errors,
           'user' => [
            'name' => $name,
            'email' => $email,
            'city' => $city,
            'state' => $state
           ]
        ]);
        exit;
        }
        $params = [
            'email' => $email,
        ];
        $user = $this->db->query("SELECT * FROM users WHERE email = :email", $params)->fetch();
        if($user)
        {
            $errors['email'] = "Email already exists.";
            loadView('users/create', ['errors' => $errors]);
            exit;
        }
        // store user in database
        $params = [
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password , PASSWORD_DEFAULT),
            'city' => $city,
            'state' => $state
        ];
        $this->db->query("INSERT INTO users (name , email , password , city , state) VALUES (:name , :email , :password , :city , :state)", $params);
        // get the new user id
        $userId = $this->db->conn->lastInsertId();
        Session::set('user' , [
            'id' => $userId,
            'email' => $email,
            'name' => $name,
            'city' => $city,
            'state' => $state
        ]);
        inspectAndDie(Session::get('user'));
        redirect('/auth/login');
    }
}