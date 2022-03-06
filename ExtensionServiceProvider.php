<?php

/**
 * @license MIT, http://opensource.org/licenses/MIT
 */


namespace Aimeos\bidsystem;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;


class ExtensionServiceProvider extends ServiceProvider
{
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;


	public function boot()
	{
		$this->loadViewsFrom( __DIR__ . DIRECTORY_SEPARATOR . 'views', 'bidsystem' );

		if( file_exists( $basepath = base_path( 'ext' ) ) )
		{
			foreach( new \DirectoryIterator( $basepath ) as $entry )
			{
				if( $entry->isDir() && !$entry->isDot() && file_exists( $entry->getPathName() . '/client/html/themes' ) ) {
					$this->publishes( [$entry->getPathName() . '/client/html/themes' => public_path( 'vendor/shop/themes' )], 'public' );
				}
			}
		}

		$class = '\Composer\InstalledVersions';

		if( class_exists( $class ) && method_exists( $class, 'getInstalledPackagesByType' ) )
		{
			$extdir = base_path( 'ext' );
			$packages = \Composer\InstalledVersions::getInstalledPackagesByType( 'aimeos-extension' );

			foreach( $packages as $package )
			{
				$path = realpath( \Composer\InstalledVersions::getInstallPath( $package ) );

				if( strncmp( $path, $extdir, strlen( $extdir ) ) && file_exists( $path . '/client/html/themes' ) ) {
					$this->publishes( [$path . '/client/html/themes' => public_path( 'vendor/shop/themes' )], 'public' );
				}
			}
		}
	}
}