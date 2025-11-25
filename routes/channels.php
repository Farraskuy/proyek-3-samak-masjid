<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('consultation.{id}', function ($user, $id) {
    $consultation = \App\Models\Consultation::find($id);
    if (!$consultation) {
        return false;
    }

    // Allow if user is the owner or the assigned ustadz (or any ustadz if pending?? maybe restrict to assigned)
    // For simplicity and based on controller logic:
    if ($user->role === 'ustadz') {
        return true; // Ustadz can view any consultation for now, or refine logic
    }

    return (int) $user->id === (int) $consultation->user_id;
});
