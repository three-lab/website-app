<?php

if(!function_exists('hide_email')) {
    function hide_email(string $email) {
        $mailpos = strpos($email, '@');
        return substr($email, $mailpos - 3);
    }
}
