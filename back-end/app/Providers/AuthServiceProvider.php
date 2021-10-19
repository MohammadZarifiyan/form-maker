<?php

namespace App\Providers;

use App\Models\Connection;
use App\Models\Form;
use App\Models\Project;
use App\Models\Query;
use App\Policies\ConnectionPolicy;
use App\Policies\FormPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\QueryPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Connection::class => ConnectionPolicy::class,
        Form::class => FormPolicy::class,
        Project::class => ProjectPolicy::class,
        Query::class => QueryPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
