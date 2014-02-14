<?php

namespace RW\ErrorHandling;

class DefaultErrors extends Error
{

    /**
     * Message if allow is denied
     */
    final public static function notAllow()
    {
        wp_die( __('You are not allowed to access this page.') );
    }
}
