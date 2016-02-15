<?php

return [

    'app' => [

        'services' => [

        ],

        'providers' => [
            '\Dingo\Api\Provider\LaravelServiceProvider',
            '\Tymon\JWTAuth\Providers\JWTAuthServiceProvider',
            '\LucaDegasperi\OAuth2Server\Storage\FluentStorageServiceProvider',
            '\LucaDegasperi\OAuth2Server\OAuth2ServerServiceProvider',
            '\Barryvdh\Cors\ServiceProvider',
            '\Zizaco\Entrust\EntrustServiceProvider',
            '\Bootstrapper\BootstrapperL5ServiceProvider',
        ],

        'aliases' => [
            'JWTAuth'       => '\Tymon\JWTAuth\Facades\JWTAuth',
            'JWTFactory'    => '\Tymon\JWTAuth\Facades\JWTFactory',
            'Authorizer'    => '\LucaDegasperi\OAuth2Server\Facades\Authorizer',
            'Entrust'       => '\Zizaco\Entrust\EntrustFacade',
            'Html'          => '\Gregoriohc\Beet\Facades\Html',
            'Form'          => '\Gregoriohc\Beet\Facades\Form',
            'Accordion'     => '\Bootstrapper\Facades\Accordion',
            'Alert'         => '\Bootstrapper\Facades\Alert',
            'Badge'         => '\Bootstrapper\Facades\Badge',
            'Breadcrumb'    => '\Bootstrapper\Facades\Breadcrumb',
            'Button'        => '\Bootstrapper\Facades\Button',
            'ButtonGroup'   => '\Bootstrapper\Facades\ButtonGroup',
            'Carousel'      => '\Bootstrapper\Facades\Carousel',
            'ControlGroup'  => '\Bootstrapper\Facades\ControlGroup',
            'DropdownButton'=> '\Bootstrapper\Facades\DropdownButton',
            'Helpers'       => '\Bootstrapper\Facades\Helpers',
            'Icon'          => '\Bootstrapper\Facades\Icon',
            'InputGroup'    => '\Bootstrapper\Facades\InputGroup',
            'Image'         => '\Bootstrapper\Facades\Image',
            'Label'         => '\Bootstrapper\Facades\Label',
            'MediaObject'   => '\Bootstrapper\Facades\MediaObject',
            'Modal'         => '\Bootstrapper\Facades\Modal',
            'Navbar'        => '\Bootstrapper\Facades\Navbar',
            'Navigation'    => '\Bootstrapper\Facades\Navigation',
            'Panel'         => '\Bootstrapper\Facades\Panel',
            'ProgressBar'   => '\Bootstrapper\Facades\ProgressBar',
            'Tabbable'      => '\Bootstrapper\Facades\Tabbable',
            'Table'         => '\Bootstrapper\Facades\Table',
            'Thumbnail'     => '\Bootstrapper\Facades\Thumbnail',
        ],

        'console' => [

            'commands' => [
                '\Gregoriohc\Beet\Console\Commands\Create',
                '\Gregoriohc\Beet\Console\Commands\Update',
                '\Gregoriohc\Beet\Console\Commands\Destroy',
            ],

        ],

    ],

    'auth' => [

        'policies' => [

        ],

    ],

    'events' => [

        'listen' => [

        ],

        'subscribe' => [

        ],

    ],

];