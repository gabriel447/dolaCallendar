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
        $this->client->setRedirectUri(url('/google-calendar/callback'));
        $this->client->setAccessType('offline');
        $this->client->setPrompt('select_account consent');
    }

    public function getClient()
    {
        return $this->client;
    }

    public function getEvents()
    {
        if (!isset($_GET['code'])) {
            abort(404, 'Falha na autenticação com o Google.');
        }else {
            $this->client->authenticate($_GET['code']);
            $accessToken = $this->client->getAccessToken();
            $this->client->setAccessToken($accessToken);
    
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
}