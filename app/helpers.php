<?php

if(!function_exists('hide_email')) {
    function hide_email(string $email) {
        $mailpos = strpos($email, '@');
        return substr($email, $mailpos - 3);
    }
}

if(!function_exists('days')) {
    function days() {
        return [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
        ];
    }
}
