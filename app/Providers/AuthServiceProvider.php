<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Contracts\Auth\Access\Gate as GateContract; //add
use App\User; //add
use App\Demand; //add
use App\Permission;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        //\App\Demand::class => \App\Policies\PostPolicy::class,
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        Passport::routes();

        // $gate->define('update-post',function(User $user, Demand $post){
        //     return $user->id == $post->user_id;
        // });

        $permissions = Permission::with('roles')->get();
        foreach($permissions as $permission){
            $gate->define($permission->name,function(User $user) use ($permission){
                return $user->hasPermission($permission);
            });
        }

        $gate->before(function(User $user, $ability){
            if($user->hasAnyRoles("administrador")){
                return true;
            }
        });
        
    }
}
