<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('notifications.managers', function ($user) {
    return in_array($user->manager_number, [1, 2, 3, 4]); 
});
