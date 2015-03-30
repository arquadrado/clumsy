<?php namespace Clumsy\CMS;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Clumsy\Assets\Facade as Asset;
use Clumsy\CMS\Clumsy;

class CMSServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->register('Cartalyst\Sentry\SentryServiceProvider');
		$this->app->register('Clumsy\Assets\AssetsServiceProvider');
		$this->app->register('Clumsy\Utils\UtilsServiceProvider');
		$this->app->register('Clumsy\Eminem\EminemServiceProvider');

        $this->app['clumsy'] = new Clumsy;

		// Bind view resolver conditionally so we can provide legacy support for Laravel 4.1
		$this->app->bind('clumsy.view-resolver-factory',
			class_exists('Illuminate\View\Environment')
				? 'Illuminate\View\Environment' // Laravel 4.1
				: 'Illuminate\View\Factory'		// Laravel 4.2+
		);
		$this->app['clumsy.view-resolver'] = $this->app->make('Clumsy\CMS\Support\ViewResolver');

        $this->app['command.clumsy.publish'] = $this->app->share(function($app)
            {
                // Make sure the asset publisher is registered.
                $app->register('Illuminate\Foundation\Providers\PublisherServiceProvider');
                return new Console\PublishCommand($app['asset.publisher']);
            });

        $this->commands(array('command.clumsy.publish'));
	}

	/**
	 * Boot the service provider.
	 *
	 * @return void
	 */
	public function boot()
	{
		$path = __DIR__.'/../..';

        $this->package('clumsy/cms', 'clumsy', $path);

        $admin_assets = include($path.'/assets/assets.php');
		Asset::batchRegister($admin_assets);

		require $path.'/macros/html.php';
		require $path.'/macros/form.php';
		require $path.'/filters.php';
		require $path.'/routes.php';
		require $path.'/errors.php';
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array(
			'clumsy',
		);
	}

}
