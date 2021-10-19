<?php

namespace App\Providers;

use App\Http\Controllers\QueryController;
use App\Http\Requests\RunQueryRequest;
use App\Models\Query;
use App\Policies\QueryPolicy;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bindVariables();
    }

    public function bindVariables()
    {
        $this->app->when([
            QueryController::class,
            QueryPolicy::class,
            RunQueryRequest::class,
        ])
            ->needs('$runnable_query')
            ->give(
                fn () => Query::whereRelation('connection.user', 'id', auth()->id())
                    ->where('id', '=', request('query'))
                    ->where('status', '=', true)
                    ->first()
            );
    }
}
