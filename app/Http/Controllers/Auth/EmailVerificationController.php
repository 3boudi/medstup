<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\URL;

class EmailVerificationController extends Controller
{
    
    public function verify(Request $request, $id, $hash)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json(['message' => 'Invalid verification link'], 403);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified']);
        }

        $user->markEmailAsVerified();

        event(new Verified($user));

        return response()->json(['message' => 'Email verified successfully']);
        
    }

    


























    
    
    /*public function verify(Request $request, $id, $hash)
{
    $user = User::find($id);

    if (!$user) {
        return redirect('https://your-frontend.com/verification-failed');
    }

    if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        return redirect('https://your-frontend.com/invalid-verification-link');
    }

    if ($user->hasVerifiedEmail()) {
        return redirect('https://your-frontend.com/already-verified');
    }

    $user->markEmailAsVerified();
    event(new Verified($user));

    return redirect('https://your-frontend.com/verification-success'); // ✅ واجهة المستخدم بعد التفعيل
}
*/
}