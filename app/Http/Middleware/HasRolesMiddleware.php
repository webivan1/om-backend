<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 20.06.2019
 * Time: 0:36
 */
declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use App\Model\User\Entities\User\User;
use Illuminate\Http\Request;

class HasRolesMiddleware
{
    public function handle(Request $request, Closure $next, $roles)
    {
        /** @var User|null $user */
        $user = $request->user();

        if ($user && $user->hasRoleByName(explode('|', trim($roles)))) {
            return $next($request);
        }

        abort(403, 'Access denied');
    }
}
