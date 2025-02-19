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
        //set user session
        Session::set('user' , [
            'id' => $userId,
            'email' => $email,
            'name' => $name,
            'city' => $city,
            'state' => $state
        ]);
       
        redirect('/auth/login');
    }
    /**
     * Summary of logout
     * @return void 
     */
    public function logout()
    {
        Session::clearAll();
        //delete cookie
        $params = session_get_cookie_params();
        setcookie('PHPSESSID', '', time() - 3600, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        redirect('/');
    }

    /**
     * Summary of authenticate
     * @param array $params
     * @return void 
     */
    public function authenticate()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $errors = [];
        //validation 
        if(!Validation::email($email))
        {
            $errors['email'] = "Please Enter Valid Email";    
        }
        if(!Validation::string($password , 6 , 55))
        {
            $errors['password'] = 'Please Enter at least 6 charchters';
        }
        
        if(!empty($errors))
        {
            loadView('users/login', [
                'errors' => $errors
            ]);
            exit;
        }
        //check for email 
        $params = [
            'email' => $email
        ];
        $user = $this->db->query('SELECT * FROM users WHERE email = :email', $params)->fetch();
        if(!$user)
        {
            $errors['email'] = "incorrect verified credentials";
            loadView('users/login',[
                'errors'=>$errors
            ]);
            exit;
        }
        //check for password is correct
        if(!password_verify($password , $user->password))
        {
            $errors['password'] = "incorrect verified credentials";
            loadView('users/login',[
                'errors'=>$errors
            ]);
            exit;
        }
        Session::set('user' , [
            'id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'city' => $user->city,
            'state' => $user->state
        ]);
       
        redirect('/');
    }
}