<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param Request $request
     * @param  string  $response
     * @return RedirectResponse
     */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        return ['status' => trans($response)];
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param Request $request
     * @param  string  $response
     * @return RedirectResponse
     */
    protected function sendResetLinkFailedResponse(Request $request, $response): RedirectResponse
    {
        return response()->json(['email' => trans($response)], 400);
    }
}
