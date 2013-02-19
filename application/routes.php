<?php

Route::get('/addClient', function() {
	$client_id = 123;                     // min-length is 3 chars!
	$client_secret = 'test';
	$redirect_uri = 'https://developers.google.com/oauthplayground';

	$oauth_strg = new OAuth2StorageLaravel();
	$oauth_strg->addClient($client_id, $client_secret, $redirect_uri);

	return Response::json(array('client_id'=>$client_id,'client_secret'=>$client_secret,'redirect_uri'=>$redirect_uri,'oauth_strg'=>$oauth_strg));
});

Route::get('/addUser', function() {
	DB::table('users')->insert(array(
	    'email'  => 'testuser',
	    'password'  => Hash::make('password')
	));
});

Route::get('login', function() {
    return View::make('login');
});

Route::post('login', function() {
    // get POST data
    $username = Input::get('username');
    $password = Input::get('password');
    if ( Auth::attempt(array('username'=>$username, 'password' => $password)) )
    {
       if (Input::has('backlink')) {
			return Redirect::to(Input::get('backlink'));
		}
    }
    else
    {
        // auth failure! lets go back to the login
        return Redirect::to('login')
            ->with('login_errors', true);
        // pass any error notification you want
        // i like to do it this way  
    }
});

Route::get('/logout', function() {
	Auth::logout();
});

Route::get('/oauth/authorize',array('before' => 'auth', 'uses'=>'oauth@authorize'));
Route::post('/oauth/authorize',array('uses'=>'oauth@authorize'));
Route::post('/oauth/token',array('uses'=>'oauth@token'));

Route::get('/users', array('uses'=>'user@users'));



Route::get('/', function()
{
	return View::make('home.index');
});

/*
|--------------------------------------------------------------------------
| Application 404 & 500 Error Handlers
|--------------------------------------------------------------------------
*/

Event::listen('404', function()
{
	return Response::error('404');
});

Event::listen('500', function()
{
	return Response::error('500');
});

/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
*/

Route::filter('before', function()
{
	// Do stuff before every request to your application...
});

Route::filter('after', function($response)
{
	// Do stuff after every request to your application...
});

Route::filter('csrf', function()
{
	if (Request::forged()) return Response::error('500');
});

Route::filter('auth', function()
{
	if (Auth::guest()) { 
		return Redirect::to('login')
							->with('backlink', URL::full());
	}
});

Route::filter('oauth2', function()
{

    try {
        $oauth = new OAuth2Server\Libraries\OAuth2(new OAuth2StorageLaravel());

        $token = $oauth->getBearerToken();

        $verify = $oauth->verifyAccessToken($token);

       Auth::login($verify['user_id']);

    } catch (OAuth2Server\Libraries\OAuth2ServerException $oauthError) {
        $oauthError->sendHttpResponse();
    }
});