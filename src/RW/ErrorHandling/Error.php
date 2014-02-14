<?php

namespace RW\ErrorHandling;

class Error
{

    /**
     * Error container
     * @var array
     */
    public static $errors = array();
    
    /**
     * Retrieve error container
     * @var array 
     */
    public static $final_results = array();
    
    /**
     * Retrieve errors
     * @param int $err_code
     * @param string $err_key
     * @return array|exception
     */
    public static function get_error( $err_code, $err_key = '')
    {
        try {
            if (!empty(self::$errors)) {
                $err_code = intval($err_code);

                self::$final_results = self::$errors[$err_code];

                if ($err_key !== '') {
                    self::$final_results = self::$errors[$err_code][$err_key];
                }

                return \array_filter( self::$final_results );
            }
            
            throw new \Exception('Something went wrong');
            
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
        
    }
    
    /**
     * Set error code, key and message
     * @param int $err_code
     * @param string $err_key
     * @param string $err_val
     * @return void
     */
    public static function set_error($err_code, $err_key, $err_val)
    {
        self::$errors[ intval($err_code) ][$err_key] = $err_val;
    }
    
    /**
     * Remove errors by error code.
     * @param int $err_code
     * @return void
     */
    public static function remove_error($err_code)
    {
        if (!empty(self::$final_results) || !empty(self::$errors)) {
            unset(self::$final_results[$err_code], self::$errors[$err_code]);
        }
    }
}
