<?php

if(!function_exists('hide_email')) {
    function hide_email(string $email) {
        $mailpos = strpos($email, '@');
        return substr($email, $mailpos - 3);
    }
}

if(!function_exists('group_array')) {
    function group_array(array $array, string $prop) {
        return array_reduce($array, function($grouped, $item) use($prop) {
            $grouper = $item->$prop;
            $grouped[$grouper][] = $item;
            return $grouped;
        }, []);
    }
}

if(!function_exists('gender')) {
    function gender(string $gender) {
        return match ($gender) {
            'male' => 'Pria',
            'female' => 'Wanita',
            'default' => '-',
        };
    }
}

if(!function_exists('days')) {
    function days(bool $activeDay = false) {
        $days = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        ];

        if($activeDay) return $days;

        return array_merge(
            $days,
            [7 => 'Minggu'],
        );
    }
}
