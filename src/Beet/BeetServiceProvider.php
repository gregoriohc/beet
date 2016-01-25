<?php

namespace Gregoriohc\Beet;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Gregoriohc\Beet\View\Html\FormBuilder;
use Gregoriohc\Beet\View\Html\HtmlBuilder;

class BeetServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $providers = [
            'Dingo\Api\Provider\LaravelServiceProvider',
            'Tymon\JWTAuth\Providers\JWTAuthServiceProvider',
            'LucaDegasperi\OAuth2Server\Storage\FluentStorageServiceProvider',
            'LucaDegasperi\OAuth2Server\OAuth2ServerServiceProvider',
            'Barryvdh\Cors\ServiceProvider',
            'Zizaco\Entrust\EntrustServiceProvider',
            'Bootstrapper\BootstrapperL5ServiceProvider',
        ];
        foreach ($providers as $provider) {
            $this->app->register($provider);
        }

        $aliases = [
            'JWTAuth'       => 'Tymon\JWTAuth\Facades\JWTAuth',
            'JWTFactory'    => 'Tymon\JWTAuth\Facades\JWTFactory',
            'Authorizer'    => 'LucaDegasperi\OAuth2Server\Facades\Authorizer',
            'Entrust'       => 'Zizaco\Entrust\EntrustFacade',
            'Html'          => 'Gregoriohc\Beet\Facades\Html',
            'Form'          => 'Gregoriohc\Beet\Facades\Form',
            'Table'         => 'Bootstrapper\Facades\Table',
        ];
        foreach ($aliases as $name => $class) {
            AliasLoader::getInstance()->alias($name, $class);
        }

        $this->registerHtmlBuilder();
        $this->registerFormBuilder();

        $this->app->alias('html', 'Gregoriohc\Beet\View\Html\HtmlBuilder');
        $this->app->alias('form', 'Gregoriohc\Beet\View\Html\FormBuilder');
    }

    /**
     * Register the HTML builder instance.
     *
     * @return void
     */
    protected function registerHtmlBuilder()
    {
        $this->app->singleton('html', function ($app) {
            return new HtmlBuilder($app['url'], $app['view']);
        });
    }

    /**
     * Register the form builder instance.
     *
     * @return void
     */
    protected function registerFormBuilder()
    {
        $this->app->singleton('form', function ($app) {
            $form = new FormBuilder($app['html'], $app['url'], $app['view'], $app['session.store']->getToken());

            return $form->setSessionStore($app['session.store']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['html', 'form', 'Gregoriohc\Beet\View\Html\HtmlBuilder', 'Gregoriohc\Beet\View\Html\FormBuilder'];
    }
}