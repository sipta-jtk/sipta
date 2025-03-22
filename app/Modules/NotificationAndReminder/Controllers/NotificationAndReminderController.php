<?php

namespace App\Modules\NotificationAndReminder\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;

class NotificationAndReminderController extends Controller
{
    public function index(): View
    {
        return view('NotificationAndReminder.views.view');
    }
}