<?php
class OAuth_Controller extends Base_Controller
{

    public $restful = true;

    public function __construct()
    {
        
        parent::__construct();
    }

    public function get_authorize()
    {
        $header = array('X-Frame-Options' => 'DENY');

        $oauth = new OAuth2Server\Libraries\OAuth2(new OAuth2StorageLaravel());

        $input = Input::all();

        $input['response_type'] = 'code'; // Here you can set default type or optional params

        try {
            $data['oauth_params'] = $oauth->getAuthorizeParams($input);
        } catch (OAuth2Server\Libraries\OAuth2ServerException $oauthError) {
            $oauthError->sendHttpResponse();
        }

        return View::make('oauth.authorize', $data, $header);
    }

    public function post_authorize()
    {
        
        $this->filter('before', 'oauth2');
        $header = array('X-Frame-Options' => 'DENY');
        $oauth = new OAuth2Server\Libraries\OAuth2(new OAuth2StorageLaravel());

        $input = Input::all();
        $input['response_type'] = 'code'; // Here you can set default type or optional params

        try {
            $auth_params = $oauth->getAuthorizeParams($input);
        } catch (OAuth2Server\Libraries\OAuth2ServerException $oauthError) {
            $oauthError->sendHttpResponse();
        }


        //$oauth->finishClientAuthorization(true, 123 , $auth_params);
        $oauth->finishClientAuthorization(true, Auth::User()->id , $auth_params);
    }

    public function post_token()
    {
        $this->filter('before', 'oauth2');
        
        $oauth = new OAuth2Server\Libraries\OAuth2(new OAuth2StorageLaravel());
        try {
            $oauth->grantAccessToken();
        } catch (OAuth2Server\Libraries\OAuth2ServerException $oauthError) {
            $oauthError->sendHttpResponse();
        }
    }
}