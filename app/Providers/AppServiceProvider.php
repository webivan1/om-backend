<?php

namespace App\Providers;

use App\Model\Common\Contracts\SlugContract;
use App\Model\Common\Contracts\TokenizerContract;
use App\Model\Common\Services\Slug;
use App\Model\Common\Services\Tokenizer;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(TokenizerContract::class, Tokenizer::class);
        $this->app->bind(SlugContract::class, Slug::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
