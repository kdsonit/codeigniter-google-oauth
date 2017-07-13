<?php
use Codebird\Codebird;

/**
 * Created by PhpStorm.
 * User: Kuldeepsinh
 * Date: 11/13/2016
 * Time: 2:00 PM
 */
class TwitterController extends  CI_Controller
{
    public $cb;
    public function __construct()
    {
        parent::__construct();
         Codebird::setConsumerKey($this->config->item('TWITTER_ID'),$this->config->item('TWITTER_SECRET'));
        $this->cb = Codebird::getInstance();
    }

    public function index()
    {
        if (!$this->session->userdata('oauth_token') )
        {
            // get the request token
            $reply = $this->requestToken();

            // store the token
            $this->cb->setToken($reply->oauth_token, $reply->oauth_token_secret);
            $this->session->set_userdata('oauth_token',$reply->oauth_token);
            $this->session->set_userdata('oauth_token_secret',$reply->oauth_token_secret);
            $this->session->set_userdata('oauth_verify',TRUE);

            $this->loginUrl();
        }
    }

    public function allRequestSession()
    {
        $this->cb->setToken($this->session->userdata('oauth_token') ,$this->session->userdata('oauth_token_secret'));
    }

    public function requestToken()
    {
        return $this->cb->oauth_requestToken([
            'oauth_callback' => site_url($this->config->item('TWITTER_CALLBACK_URL'))
        ]);
    }

    public function loginUrl()
    {
        $auth_url = $this->cb->oauth_authorize();
        redirect($auth_url);
    }

    public function twitter_callBack()
    {
        if($this->input->get('oauth_verifier') && $this->session->userdata('oauth_verify'))
        {
            // verify the token
            //$this->cb->setToken($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
            $this->allRequestSession();
            $this->session->unset_userdata('oauth_verify');

            // get the access token
            $reply = $this->cb->oauth_accessToken([
                'oauth_verifier' => $this->input->get('oauth_verifier')
            ]);

            // store the token (which is different from the request token!)
            $this->session->set_userdata('oauth_token',$reply->oauth_token);
            $this->session->set_userdata('oauth_token_secret',$reply->oauth_token_secret);
        }

        redirect(site_url('twitterController/user_profile'));
    }

    public function user_profile()
    {
       $this->allRequestSession();
        $this->cb->user();
    }

    public function logout(){
        $this->cb->logout();
        $user_data = $this->session->all_userdata();
        foreach ($user_data as $key => $value) {
            if ($key != 'session_id' && $key != 'ip_address' && $key != 'user_agent' && $key != 'last_activity') {
                $this->session->unset_userdata($key);
            }
        }
        $this->session->sess_destroy();
    }
}