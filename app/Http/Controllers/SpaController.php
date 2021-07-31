<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class SpaController extends Controller
{
    /**
     * Get the SPA view.
     *
     * @return Response
     */
    public function __invoke(): Response
    {
        $path = public_path('_nuxt/index.html');

        abort_unless(file_exists($path), 400, 'Make sure to run npm run build!');

        return file_get_contents($path);
    }
}
