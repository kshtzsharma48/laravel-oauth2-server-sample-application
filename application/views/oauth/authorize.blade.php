<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Authorize</title>
    <meta name="viewport" content="width=device-width">
    {{ HTML::style('laravel/css/style.css') }}
</head>
<body>
    <div class="wrapper">
        <div role="main" class="main">
            <div class="home">
                {{ Form::open('oauth/authorize', 'POST') }}
                {{ Form::hidden('client_id', $oauth_params['client_id']) }}
                {{ Form::hidden('redirect_uri', $oauth_params['redirect_uri']) }}
                {{ Form::hidden('response_type', $oauth_params['response_type']) }}
                {{ Form::hidden('state', $oauth_params['state']) }}
                {{ Form::hidden('scope', $oauth_params['scope']) }}
                {{ Form::submit() }}
                {{ HTML::link('/' , 'Cancel') }}
                {{ Form::close() }}
            </div>
        </div>
    </div>
</body>
</html>