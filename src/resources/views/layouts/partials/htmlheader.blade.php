{{ Html::tag('head', [
    Html::meta(null, null, ['charset' => 'UTF-8']),
    Html::tag('title', config('xelsaas.app_name')),
    Html::meta('viewport', 'width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no'),
    Html::tag('link', null, ['href' => 'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css', 'rel' => 'stylesheet', 'type' => 'text/css']),
    Html::tag('link', null, ['href' => 'https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css', 'rel' => 'stylesheet', 'type' => 'text/css']),
    Html::tag('link', null, ['href' => asset('/css/bootstrap.css'), 'rel' => 'stylesheet', 'type' => 'text/css']),
    Html::tag('link', null, ['href' => asset('/css/admin.css'), 'rel' => 'stylesheet', 'type' => 'text/css']),
    Html::tag('link', null, ['href' => asset('/plugins/iCheck/square/blue.css'), 'rel' => 'stylesheet', 'type' => 'text/css']),
    Html::commentIf('lt IE 9', [
        Html::script('https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js'),
        Html::script('https://oss.maxcdn.com/respond/1.4.2/respond.min.js'),
    ]),
]) }}
