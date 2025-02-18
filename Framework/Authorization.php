<?php 
namespace Framework;

class Authorization
{
    /**
     * ceck if the current user owns the post job listing
     * @param int  $resourceId
     * return bool
     */
    public static function isOwns($resourceId)
    {
        $sessionUser = Session::get('user');
        if($sessionUser !==null && isset($sessionUser['id']))
        {
            $sessionUser = (int) $sessionUser['id'];
            return $sessionUser === $resourceId;
        }
        return false;
    }
}