<?php

namespace Gregoriohc\Beet;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Gregoriohc\Beet\View\Html\FormBuilder;
use Gregoriohc\Beet\View\Html\HtmlBuilder;

class BeetServiceProvider extends ServiceProvider
{
    private $providers = [
        \Dingo\Api\Provider\LaravelServiceProvider::class,
        \Tymon\JWTAuth\Providers\JWTAuthServiceProvider::class,
        \LucaDegasperi\OAuth2Server\Storage\FluentStorageServiceProvider::class,
        \LucaDegasperi\OAuth2Server\OAuth2ServerServiceProvider::class,
        \Barryvdh\Cors\ServiceProvider::class,
        \Zizaco\Entrust\EntrustServiceProvider::class,
        \Bootstrapper\BootstrapperL5ServiceProvider::class,
    ];

    private $aliases = [
        'JWTAuth'       => \Tymon\JWTAuth\Facades\JWTAuth::class,
        'JWTFactory'    => \Tymon\JWTAuth\Facades\JWTFactory::class,
        'Authorizer'    => \LucaDegasperi\OAuth2Server\Facades\Authorizer::class,
        'Entrust'       => \Zizaco\Entrust\EntrustFacade::class,
        'Html'          => \Gregoriohc\Beet\Facades\Html::class,
        'Form'          => \Gregoriohc\Beet\Facades\Form::class,
        'Accordion'     => \Bootstrapper\Facades\Accordion::class,
        'Alert'         => \Bootstrapper\Facades\Alert::class,
        'Badge'         => \Bootstrapper\Facades\Badge::class,
        'Breadcrumb'    => \Bootstrapper\Facades\Breadcrumb::class,
        'Button'        => \Bootstrapper\Facades\Button::class,
        'ButtonGroup'   => \Bootstrapper\Facades\ButtonGroup::class,
        'Carousel'      => \Bootstrapper\Facades\Carousel::class,
        'ControlGroup'  => \Bootstrapper\Facades\ControlGroup::class,
        'DropdownButton'=> \Bootstrapper\Facades\DropdownButton::class,
        'Helpers'       => \Bootstrapper\Facades\Helpers::class,
        'Icon'          => \Bootstrapper\Facades\Icon::class,
        'InputGroup'    => \Bootstrapper\Facades\InputGroup::class,
        'Image'         => \Bootstrapper\Facades\Image::class,
        'Label'         => \Bootstrapper\Facades\Label::class,
        'MediaObject'   => \Bootstrapper\Facades\MediaObject::class,
        'Modal'         => \Bootstrapper\Facades\Modal::class,
        'Navbar'        => \Bootstrapper\Facades\Navbar::class,
        'Navigation'    => \Bootstrapper\Facades\Navigation::class,
        'Panel'         => \Bootstrapper\Facades\Panel::class,
        'ProgressBar'   => \Bootstrapper\Facades\ProgressBar::class,
        'Tabbable'      => \Bootstrapper\Facades\Tabbable::class,
        'Table'         => \Bootstrapper\Facades\Table::class,
        'Thumbnail'     => \Bootstrapper\Facades\Thumbnail::class,
    ];

    private $commands = [
        \Gregoriohc\Beet\Console\Commands\Create::class,
        \Gregoriohc\Beet\Console\Commands\Update::class,
        \Gregoriohc\Beet\Console\Commands\Destroy::class,
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->providers as $provider) {
            $this->app->register($provider);
        }

        foreach ($this->aliases as $name => $class) {
            AliasLoader::getInstance()->alias($name, $class);
        }

        $this->registerHtmlBuilder();
        $this->registerFormBuilder();
        $this->registerCommands();

        $this->app->alias('html', 'Gregoriohc\Beet\View\Html\HtmlBuilder');
        $this->app->alias('form', 'Gregoriohc\Beet\View\Html\FormBuilder');
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(realpath(__DIR__.'/../resources/views'), 'beet');
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
     * Register the package commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        $this->commands($this->commands);
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