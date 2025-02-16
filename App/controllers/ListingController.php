<?php
namespace App\Controllers;

use Framework\Database;
use Framework\Validation;

class ListingController
{
    protected $db;
    public function __construct()
    {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }
    public function index()
    {
        $listings = $this->db->query("SELECT * FROM listings")->fetchAll();

        loadView('listings/index',[
                 'listings' => $listings]);
    }
    public function create()
    {
        loadView('listings/create');
    }
    /**
     * Summary of show
     * @param array $params
     * @return void
     */
    public function show($params)
    {
        $id = $params['id'] ?? '';
        $params = ['id' => $id];
        $listing = $this->db->query("SELECT * FROM listings WHERE id = :id", $params)->fetch();
        if(!$listing)
        {
            ErrorController::notFound();
            return;
        }
        loadView('listings/show',['listing' => $listing]);
    }
    /**
     * Store data in database
     * @return void
     */
    public function store()
    {
        $allowFields = ['title', 'description', 'salary', 'city',
         'state', 'tags' , 'requirements', 'company', 'address' , 'email', 'benefits' , 'phone'];
         $newListingData = array_intersect_key($_POST, array_flip($allowFields));
         $newListingData['user_id'] = 1;
         $newListingData = array_map('sanitize', $newListingData);
         $requiredFields = ['title' ,'email' , 'description', 'city' , 'state'];
         $errors = [];
         foreach($requiredFields as $field)
         {
             if(empty($newListingData[$field]) || !Validation::string($newListingData[$field]))
             {
                $errors[$field] = ucfirst($field)." is required.";
             }
         }
         if(!empty($errors))
         {
             // reload view with errors
             loadView('listings/create',[
                 'errors' => $errors,
                 'listing' => $newListingData
             ]);
         }else{
            $fields = [];
            foreach($newListingData as $key => $value)
            {
                $fields [] = $key;
                
            }
            $fields = implode(',', $fields);
            $values = [];
            foreach($newListingData as $key => $value)
            {
                if($value === '')
                {
                    $newListingData[$key] = null;
                }
                $values [] = ':'.$key;
            }
            $values = implode(',', $values);
            $query = "INSERT INTO listings ({$fields}) VALUES ({$values})";
            $this->db->query($query, $newListingData);
            redirect('/listings');
         }
         
    }
    /**
     * delete listing
     * @param $params
     * @return void
     */
    public function destroy($params)
    {
        $id = $params['id'] ?? '';
        $params = ['id' => $id];
        $listing = $this->db->query("SELECT * FROM listings WHERE id = :id", $params)->fetch();
        if(!$listing)
        {
            ErrorController::notFound();
            return;
        }
       
        $this->db->query("DELETE FROM listings WHERE id = :id", $params);
        //set flash message
        $_SESSION['success_message'] = [
            "message" => "Listing deleted successfully"
        ];
        redirect('/listings');
    }
     /**
     * Summary of edit
     * @param array $params
     * @return void
     */
    public function edit($params)
    {
        $id = $params['id'] ?? '';
        $params = ['id' => $id];
        $listing = $this->db->query("SELECT * FROM listings WHERE id = :id", $params)->fetch();
        if(!$listing)
        {
            ErrorController::notFound();
            return;
        }
        
        loadView('listings/edit',['listing' => $listing]);
    }
    /**
     * Summary of update
     * @param array $params
     * @return void
     */
    public function update($params)
    {
        $id = $params['id'] ?? '';
        $params = ['id' => $id];
        $listing = $this->db->query("SELECT * FROM listings WHERE id = :id", $params)->fetch();
        if(!$listing)
        {
            ErrorController::notFound();
            return;
        }
        $allowFields = ['title', 'description', 'salary', 'city',
         'state', 'tags' , 'requirements', 'company', 'address' , 'email', 'benefits' , 'phone'];
        $updatedListingData = array_intersect_key($_POST, array_flip($allowFields));
        $updatedListingData = array_map('sanitize', $updatedListingData);
        $requiredFields = ['title' ,'email' , 'description', 'city' , 'state'];
        $errors = [];
        foreach($requiredFields as $field)
        {
            if(empty($updatedListingData[$field]) || !Validation::string($updatedListingData[$field]))
            {
               $errors[$field] = ucfirst($field)." is required.";
            }
        }
        if(!empty($errors))
        {
            // reload view with errors
            loadView('listings/edit',[
                'errors' => $errors,
                'listing' => $updatedListingData
            ]);
            exit;
        }else{
            $updateFields = [];
            foreach(array_keys($updatedListingData) as $key)
            {
                $updateFields [] = "{$key} = :{$key}";
            }
            $updateFields = implode(', ', $updateFields);
            $updateQuery = "UPDATE listings SET {$updateFields} WHERE id = :id";
            $updatedListingData['id'] = $id;
           $this->db->query($updateQuery, $updatedListingData);
           $_SESSION['success_message'] = [
               "message" => "Listing updated successfully"
           ];
           redirect('/listing/'. $id);
            
        }
    }
    
}