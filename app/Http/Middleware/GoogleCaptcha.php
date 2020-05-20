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
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class GoogleCaptcha
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->isMethod('post') && $request->has('captcha')) {
            $token = $request->input('captcha');

            $client = new Client();

            $response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
                'verify' => false,
                'form_params' => [
                    'secret' => env('GOOGLE_CAPTCHA_SERVER'),
                    'response' => $token,
                ],
            ]);

            $result = @json_decode($response->getBody()->getContents(), true);

            if (!(array_key_exists('success', (array) $result) && $result['success'] === true)) {
                abort(403, 'Are you bot?');
            }
        }

        return $next($request);
    }
}