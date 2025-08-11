<?php

namespace App\Http\Middleware;

use Closure;

class CheckMassTranscriptFeature
{
    public function handle($request, Closure $next)
    {
        if (!config('features.mass_transcript')) {
            abort(404);
        }

        return $next($request);
    }
}
