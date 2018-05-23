<?php

class GoogleService {
    private $clientId = "197545335436-bcgreidis97jhai4c741nfv7qetuf4uh.apps.googleusercontent.com";
    private $projectId = "calendar-202601";
    private $authUri = "https://accounts.google.com/o/oauth2/auth";
    private $tokenUri = "https://accounts.google.com/o/oauth2/token";
    private $clientSecret = "FtmUc0eoTxgVX3--gOualFbD";
    private $redirectUris = ["http://localhost:8888/calendar/callback.php",
                                "http://emreji.com/calendar/callback.php"];

    private $scopes = ["https://www.googleapis.com/auth/userinfo.profile",
                        "https://www.googleapis.com/auth/calendar.readonly"];
    private $accessType = "offline";

    private $userInfoUri = "https://www.googleapis.com/oauth2/v1/userinfo?alt=json&access_token=";
    private $calendarEventsUri = "https://www.googleapis.com/calendar/v3/calendars/primary/events?alt=json&orderBy=startTime&singleEvents=true&maxResults=10&access_token=";
    private $googleMapAPIKey = "AIzaSyDqIPBsjIUSx-zFZ4l081vfLd8KrMKLnu0";
    private $googleMapsUri = "https://www.google.com/maps/embed/v1/place";
    private $revokeUri = "https://accounts.google.com/o/oauth2/revoke";

    public function getRedirectURL() {

        $redirectURL = $this->authUri;
        $redirectURL = $redirectURL . "?scope=" . implode(' ', $this->scopes);
        $redirectURL = $redirectURL . "&access_type=" . $this->accessType;
        $redirectURL = $redirectURL . "&include_granted_scopes=true";
        $redirectURL = $redirectURL . "&redirect_uri=" . $this->getRedirectionURI();
        $redirectURL = $redirectURL . "&response_type=code";
        $redirectURL = $redirectURL . "&client_id=" . $this->clientId;

        return $redirectURL;
    }

    function getTokenFromAuthorizationCode($authorizationCode) {
        $data = [
            'code' => $authorizationCode,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $this->getRedirectionURI(),
            'grant_type' => 'authorization_code'
        ];

        $ch = curl_init($this->tokenUri);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    function getLoggedInUserInfo($token) {
        $ch = curl_init($this->userInfoUri . $token);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    function logout($token) {
        $revokeURL = $this->revokeUri . "?token=" . $token;

        $ch = curl_init($revokeURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    function getCalendarEvents($token) {
        $url = $this->calendarEventsUri . $token;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    function getMapURL($selectedLocation) {
        return $this->googleMapsUri . "?key=" . $this->googleMapAPIKey ."&q=" . $selectedLocation;
    }

    private function getRedirectionURI() {
        if ($_SERVER['SERVER_NAME'] == 'localhost') {
            return $this->redirectUris[0];
        } else {
            return $this->redirectUris[1];
        }
    }
}