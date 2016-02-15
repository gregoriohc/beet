<?php

namespace Gregoriohc\Beet;

use Event;
use Gate;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Gregoriohc\Beet\View\Html\FormBuilder;
use Gregoriohc\Beet\View\Html\HtmlBuilder;
use Route;

class BeetServiceProvider extends ServiceProvider
{
    private $appServices;
    private $appProviders;
    private $appAliases;
    private $appConsoleCommands;
    private $authPolicies;
    private $eventsListen;
    private $eventsSubscribe;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->config();
        $this->registerProviders();
        $this->registerAliases();
        $this->registerBuilders();
        $this->registerCommands();
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootRoutes();
        $this->bootEvents();
        $this->bootListeners();
        $this->bootPolicies();
        $this->loadViewsFrom(realpath(__DIR__.'/../resources/views'), 'beet');
    }

    /**
     * Initialize the package and base config.
     *
     * @return void
     */
    private function config()
    {
        $this->mergeRecursiveConfigFrom(__DIR__.'/../config/beet_base.php', 'beet_base');

        $this->appServices = config('beet_base.app.services');
        $this->appProviders = config('beet_base.app.providers');
        $this->appAliases = config('beet_base.app.aliases');
        $this->appConsoleCommands = config('beet_base.app.console.commands');
        $this->authPolicies = config('beet_base.auth.policies');
        $this->eventsListen = config('beet_base.events.listen');
        $this->eventsSubscribe = config('beet_base.events.subscribe');
    }

    /**
     * Boot the package and base routes.
     *
     * @return void
     */
    private function bootRoutes()
    {
        if (! $this->app->routesAreCached()) {
            Route::group(['namespace' => 'App\Http\Controllers'], function ($router) {
                require base_path('App/Base/Http/routes.php');
            });
        }
    }

    /**
     * Boot the package and base events.
     *
     * @return void
     */
    private function bootEvents()
    {
        foreach ($this->eventsListen as $event => $listeners) {
            foreach ($listeners as $listener) {
                Event::listen($event, $listener);
            }
        }
    }

    /**
     * Boot the package and base listeners.
     *
     * @return void
     */
    private function bootListeners()
    {
        foreach ($this->eventsSubscribe as $subscriber) {
            Event::subscribe($subscriber);
        }
    }

    /**
     * Boot the package and base policies.
     *
     * @return void
     */
    private function bootPolicies()
    {
        foreach ($this->authPolicies as $key => $value) {
            Gate::policy($key, $value);
        }
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
        $this->app->bind(
            'illuminate::html',
            function ($app) {
                return new \Illuminate\Html\HtmlBuilder($app['url']);
            }
        );

        $this->app->singleton('form', function ($app) {
            $form = new FormBuilder($app['illuminate::html'], $app['url'], $app['session.store']->getToken());

            return $form->setSessionStore($app['session.store']);
        });
    }

    /**
     * Register the builders.
     *
     * @return void
     */
    public function registerBuilders()
    {
        $this->registerHtmlBuilder();
        $this->registerFormBuilder();
        $this->app->alias('html', 'Gregoriohc\Beet\View\Html\HtmlBuilder');
        $this->app->alias('form', 'Gregoriohc\Beet\View\Html\FormBuilder');
    }

    /**
     * Register the required vendors providers.
     *
     * @return void
     */
    private function registerProviders()
    {
        foreach ($this->appProviders as $provider) {
            $this->app->register($provider);
        }
    }

    /**
     * Register the required vendors aliases.
     *
     * @return void
     */
    private function registerAliases()
    {
        foreach ($this->appAliases as $name => $class) {
            AliasLoader::getInstance()->alias($name, $class);
        }
    }

    /**
     * Register the commands.
     *
     * @return void
     */
    private function registerCommands()
    {
        $this->commands($this->appConsoleCommands);
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

    /**
     * Merge the given configuration with the existing configuration.
     *
     * @param  string  $path
     * @param  string  $key
     * @return void
     */
    protected function mergeRecursiveConfigFrom($path, $key)
    {
        $config = $this->app['config']->get($key, []);

        $this->app['config']->set($key, array_merge_recursive(require $path, $config));
    }
}