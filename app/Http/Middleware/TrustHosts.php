<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as Middleware;

class TrustHosts extends Middleware
{
    /**
     * Get the host patterns that should be trusted.
     *
     * @return array<int, string|null>
     */
    public function hosts(): array
    {
        return [
            //this is useless right now
            '197.58.195.208',
            '197.58.195.208:3000',
            'localhost:3000',
            'https://localhost:3000',
            $this->allSubdomainsOfApplicationUrl(),
        ];
    }
}
