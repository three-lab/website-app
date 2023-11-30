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

if(!function_exists('attStatus')) {
    function attStatus(string $status) {
        return match($status) {
            'present' => 'Hadir',
            'late' => 'Terlambat',
            'absent' => 'Absen',
            'excused' => 'Izin',
            default => $status,
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

        $days[7] = 'Minggu';
        return $days;
    }
}

if(!function_exists('months')) {
    function months(bool $abbreved = false) {
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        if($abbreved) return array_map(fn($month) => substr($month, 0, 3), $months);
        return $months;
    }
}
