<?php

namespace KravanhEco\Report\Http\Middleware;

class AllowedCompanyOnly
{
    public function handle($request, $next)
    {
        return user()->isCompany() ? $next($request) : abort(403);
    }
}
