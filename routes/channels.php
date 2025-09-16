<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('host.appointment.{uuid}', function ($user, $uuid) {
    return (int) $user->uuid === (int) $uuid;
});
