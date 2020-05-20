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
use App\Model\Token\Repositories\TokenRepository;
use App\Model\Token\Entities\Token\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LaravelDoctrine\ORM\Facades\EntityManager;

class AuthTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $id = $request->headers->get('X-AUTH-ID') ?? '';
        $tokenId = $request->bearerToken();

        /** @var TokenRepository $repo */
        $repo = EntityManager::getRepository(Token::class);

        /** @var Token|null $token */
        if ($token = $repo->findOneBy(['user' => $id, 'token' => $tokenId])) {
            if ($token->isExpire()) {
                abort(401);
            } else {
                Auth::setUser($token->getUser());
                return $next($request);
            }
        }

        abort(403);
    }
}
