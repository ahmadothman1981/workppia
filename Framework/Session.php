<?php
namespace Framework;

class Session
{
    /**
     * Start the session
     * 
     * @return void
     */
    public static function start()
    {
        if(session_status() === PHP_SESSION_NONE)
        {
            session_start();
        }
    }
    /**
     * set a session key
     * 
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }
    /**
     * get a session value by key
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }
    /**
     * check if a session key exists
     * 
     * @param string $key
     * @return boolean
     */
    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }
    /**
     * clear a session key
     * 
     * @param string $key
     * @return void
     */
    public static function clear($key)
    {
        if(isset($_SESSION[$key]))
        {
            unset($_SESSION[$key]);
        }
    }
        /**
         * clear all session keys
         * 
         * @return void
         */
    public static function clearAll()
    {
            session_unset();
            session_destroy();
    }
    /**
     * set flash message
     * @param string $key
     * return void
     */
    public static function setFlashMessage($key , $mesage)
    {
        self::set('flash_' . $key, $mesage);
    }
    /**
     * get flash message
     * @param string $key
     * @param mixed $default
     * return string
     */
    public static function getFlashMessage($key, $default = null)
    {
        $message = self::get('flash_' . $key, $default);
        self::clear('flash_' . $key);
        return $message;
    }
}