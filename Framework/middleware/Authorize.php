<?php 
namespace Framework\Middleware;

use Framework\Session;

class Authorize
{
    /**
     * check if user authenticated
     * @return bool
     */
    public function isAuthenticated()
    {
        return Session::has('user');
    }
    /**
     * Hmandle users request
     * @param string $role
     * 
     */
    public function handle($role)
    {
        if($role === 'guest' && $this->isAuthenticated())
        {
            return redirect('/');
        }elseif($role ==='auth' && !$this->isAuthenticated())
        {
            return redirect('/auth/login');
        }

    }
}