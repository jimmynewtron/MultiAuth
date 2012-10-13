<?php
namespace MultiAuth\Resource;

use \MultiAuth\OAuth2\Consumer as Consumer;

class Google {
    
    /**
     * List of common scopes
     * More on {@link https://developers.google.com/oauthplayground/}
     */
    const EP_USER_INFO = 'https://www.googleapis.com/oauth2/v1/userinfo';
    const EP_USER_EMAIL = 'https://www.googleapis.com/auth/userinfo.email';
    const EP_USER_PROFILE = 'https://www.googleapis.com/auth/userinfo.profile';
    const EP_CALENDAR = 'https://www.googleapis.com/auth/calendar';
    const EP_ANALYTICS = 'https://www.googleapis.com/auth/analytics.readonly';
    const EP_DRIVE = 'https://www.googleapis.com/auth/drive';
    const EP_DOCS = 'https://www.googleapis.com/auth/docs';
    const EP_YOUTUBE = 'https://gdata.youtube.com';
    
    protected $accessToken;

    protected $data = array();
    protected $profile;

    public function __construct($accessToken) {
        $this->accessToken = $accessToken;
    }

    public function getId() {
        $profile = $this->getProfile();
        return $profile['id'];
    }

    public function getProfile() {
        if ($this->profile === null) {
            $info = (array)json_decode($this->getData('info', self::EP_USER_INFO));
            $email = (array)json_decode($this->getData('email', self::EP_USER_EMAIL));
            $profile = (array)json_decode($this->getData('profile', self::EP_USER_PROFILE));
            $this->profile = array_merge($info, $email, $profile);
        }
        
        return $this->profile;
    }
    
    public function getPicture() {
        $data = $this->getProfile();
        return $data['picture'];
    }

    protected function getData($label, $url, $redirects = true) {
        if (!$this->hasData($label)) {
            $value = Consumer::getData(
                $url,
                $this->accessToken['access_token'],
                $redirects);
            $this->setData($label, $value);
        }
        
        return $this->data[$label];
    }

    protected function setData($label, $value) {
        $this->data[$label] = $value;
    }

    protected function hasData($label) {
        return isset($this->data[$label]) && (NULL !== $this->data[$label]);
    }
}
