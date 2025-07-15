 <?php
/*
namespace App\Http\Controllers\Auth;

use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Controller;

class VerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum'); // or 'auth' if you're not using API
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    // ⚠️ هذا هو الجزء اللي يغير الوجهة بعد التأكيد
    protected function redirectTo()
    {
        return 'https://www.youtube.com/watch?v=hkT1A6L_cYc'; // غير هذا لأي رابط تبغاه
    }

    public function verify(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect($this->redirectTo());
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect($this->redirectTo())->with('verified', true);
    }
}
*/