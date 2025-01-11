<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleCalendarService;

class CalendarController extends Controller
{
    protected $calendarService;

    public function __construct(GoogleCalendarService $calendarService)
    {
        $this->calendarService = $calendarService;
    }

    public function index()
    {
        $client = $this->calendarService->getClient();
    
        if (!$this->calendarService->hasToken()) {
            if (!isset($_GET['code'])) {
                $authUrl = $client->createAuthUrl();
                return redirect($authUrl);
            } else {
                $this->calendarService->saveToken($_GET['code']);
            }
        }
    
        $events = $this->calendarService->getEvents();
    
        return view('calendar.index', compact('events'));
    }
}