<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'أدخل البريد الإلكتروني.',
            'email.email' => 'أدخل بريداً إلكترونياً صحيحاً.',
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'أرسلنا رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني.');
        }

        $message = match ($status) {
            Password::INVALID_USER => 'لا يوجد حساب مسجّل بهذا البريد الإلكتروني.',
            Password::RESET_THROTTLED => 'أرسلنا رابطاً منذ قليل. حاول مرة أخرى بعد دقيقة.',
            default => 'تعذّر إرسال رابط إعادة التعيين. حاول مرة أخرى.',
        };

        return back()->withInput($request->only('email'))
            ->withErrors(['email' => $message]);
    }
}
