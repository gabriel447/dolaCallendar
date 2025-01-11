<?php

namespace App\Services;

use Google_Client;
use Google_Service_Calendar;

class GoogleCalendarService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setAuthConfig(storage_path('app/client_secret.json'));
        $this->client->addScope(Google_Service_Calendar::CALENDAR_READONLY);
    }

    public function getClient()
    {
        return $this->client;
    }

    public function hasToken()
    {
        return session()->has('google_access_token');
    }

    public function saveToken($code)
    {
        $this->client->authenticate($code);
        $accessToken = $this->client->getAccessToken();

        session(['google_access_token' => $accessToken]);

        $this->client->setAccessToken($accessToken);
    }

    public function getEvents()
    {

        if (!session('google_access_token')) {
            return redirect()->route('google.auth');
        }

        $this->client->setAccessToken(session('google_access_token'));

        if ($this->client->isAccessTokenExpired()) {
            $refreshToken = $this->client->getRefreshToken();
            $this->client->fetchAccessTokenWithRefreshToken($refreshToken);
    
            $newAccessToken = $this->client->getAccessToken();
            session(['google_access_token' => $newAccessToken]);
        }

        $service = new Google_Service_Calendar($this->client);

        $calendarId = 'primary';
        $optParams = [
            'maxResults' => 10,
            'orderBy' => 'startTime',
            'singleEvents' => true,
            'timeMin' => date('c'),
        ];

        $results = $service->events->listEvents($calendarId, $optParams);

        return $results->getItems();
    }    
}