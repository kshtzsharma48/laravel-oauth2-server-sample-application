<?php
class User_Controller extends Base_Controller
{

    public $restful = true;
    private $oauth; 

    public function __construct()
    {
        $this->filter('before', 'oauth2');
        parent::__construct();
    }

    public function get_users() {
    	return Response::json(array(
    		'response' => 200,
    		'url' => 'http://example.com/users/',
    		'logedInUser' => Auth::user()->email
    		));
    }
}