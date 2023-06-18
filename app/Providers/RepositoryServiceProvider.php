<?php

namespace App\Providers;

use App\Repositories\Read\ProductReadRepository;
use App\Repositories\Read\ProductReadRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            ProductReadRepositoryInterface::class,
            ProductReadRepository::class
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
