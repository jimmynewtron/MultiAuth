<?php
namespace MultiAuth\Auth\Adapter;
use \MultiAuth\Auth\Identity\Twitter as Identity;
use \Zend_Oauth_Consumer as Consumer;
use \Zend_Auth_Result as Result;
use \Zend_Session_Namespace as SessionNameSpace;

/**
 * Twitter adapter to OAuth authentication
 *
 * @package     MultiAuth
 * @subpackage  Adapter
 * @author      Roel Obdam
 * @author      Darlan Alves
 */
class Twitter implements AdapterInterface {
    
    protected $accessToken;
    protected $requestToken;
    protected $params;
    protected $options;
    protected $consumer;

    /**
     * Constructor.
     *
     * @param array $token
     * @param array $options
     */
    public function __construct($requestParams, $options) {
        $this->options = $options;
        $this->consumer = new Consumer($options);
        $this->setRequestToken($requestParams);
    }

    /**
     * Perform the authentication
     *
     * @return \Zend_Auth_Result
     */
    public function authenticate() {
        $result = array();
        $result['code'] = Result::FAILURE;
        $result['identity'] = NULL;
        $result['messages'] = array();

        $data = array('tokens' => array('access_token' => $this->accessToken));

        $identity = new Identity($this->accessToken, $this->options);
        $result['code'] = Result::SUCCESS;
        $result['identity'] = $identity;

        return new Result($result['code'], $result['identity'], $result['messages']);
    }

    /**
     * Get a valid authorization URL from API
     *
     * @param array adapter options
     * @return string URL
     */
    public static function getAuthorizationUrl($options) {
        $consumer = new Consumer($options);
        $token = $consumer->getRequestToken();
        $twitterToken = new SessionNamespace('twitterToken');
        $twitterToken->rt = serialize($token);
        return $consumer->getRedirectUrl(null, $token);
    }

    /**
     * Set the requestToken
     *
     * @param string $requestToken
     * @return $this
     */
    protected function setRequestToken($params) {
        $twitterToken = new SessionNameSpace('twitterToken');
        $token = unserialize($twitterToken->rt);
        
        if ($token) {
            try {
                $accesstoken = $this->consumer->getAccessToken($params, $token);
                $this->accessToken = $accesstoken;
                unset($twitterToken->rt);
            } catch (\Exception $e) { }
        }
        
    }
    
    /**
     * Set adapter parameters
     *
     * @param array $options
     * @return $this
     */
    protected function setOptions($options) {
        $this->options = $options;
        $this->consumer->setOptions($options);
    }
}
