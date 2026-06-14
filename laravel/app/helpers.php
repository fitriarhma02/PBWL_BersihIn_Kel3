<?php

if (!function_exists('kirimNotif')) {
    function kirimNotif($userId, $title, $message, $type = 'info', $icon = 'info') {
        \DB::table('notifications')->insert([
            'user_id'    => $userId,
            'title'      => $title,
            'message'    => $message,
            'type'       => $type,
            'icon'       => $icon,
            'is_read'    => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}