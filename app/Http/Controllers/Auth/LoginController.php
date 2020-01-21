<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

use Modules\Admin\Entities\Setting;
use Session;
use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\BaseController;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/teacher';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    
    }

    

    // public function login(Request $req)
    // {

    //      if (Auth::attempt(['username' => $req['username'], 'password' => $req['password']])) {
    //         if(Auth::user()->status==0){
    //              Auth::logout();
    //             return redirect()->route("login")->with("message","Account is not yet activated. Please contact the admin to activate your account.");
    //         }
    //         // echo 5555555555;
    //         $this->redirectTo();
    //         return redirect("/teacher");
    //     }else{
    //         return redirect()->back()->withErrors();
    //     }
    // }

    public function redirectTo()
    {
        $role=Auth::user()->role;
        $setting = Setting::first();
        
        

        Session::put('class_record_type',$setting->term);
        Session::put('semester',$setting->semester);
        Session::put('sy',$setting->sy);
         Session::put('student_id',Auth::user()->account_id);
        Session::put('teacher_id',Auth::id());
        BaseController::sendSms(Auth::user()->cp_number,'You have just logged in to your account. If you are not the one doing this, please contact admin immediatedly to reset your password.');
        if($role=="Student"){
            return "/student";
        }elseif($role=="Teacher"){
            return "/teacher";

        }elseif($role=="Admin"){
            return "/admin";

        }
        // return 1223;
    }


    public function username()
    {
        return 'username';
    }
}
