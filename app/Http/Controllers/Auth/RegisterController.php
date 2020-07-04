<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Modules\Teacher\Entities\Teacher;
use Modules\Admin\Entities\Setting;
use Illuminate\Support\Facades\Auth;
use Session;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|min:5',
            'username' => 'required|min:6|unique:users',
            'password' => 'required|min:6|confirmed',
            'cp_number' => 'required|digits:11',
            'degree' => 'required',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        $user = User::where('id_number',$data['id_number'])->get();
        $this->validate(request(),[
                'name' => 'required|string|min:5',
                'username' => 'required|min:6|unique:users',
                'password' => 'required|min:6|confirmed',
                'cp_number' => 'required|digits:11',
                'degree' => 'required',
            ]);
        if(count($user)>0){
            $user_update = User::where('id',$user[0]->id)->update([
				'name' => $data['last_name'].", ".$data['first_name'],
                'id_number' => $data['id_number'],
                'last_name' => $data['last_name'],
                'first_name' => $data['first_name'],
                'username' => $data['username'],
                'degree' => $data['degree'],            
                'role' => "Teacher",
                'is_registered' => 2,
                'password' => bcrypt($data['password']),
            ]);
            $user = User::find($user[0]->id);
            //$user = $user_update;
            return $user;
        }else{
            $user =  User::create([
                'name' => $data['last_name'].", ".$data['first_name'],
                'id_number' => $data['id_number'],
                'last_name' => $data['last_name'],
                'first_name' => $data['first_name'],
                'username' => $data['username'],
                'degree' => $data['degree'],            
                'role' => "Teacher",
                'is_registered' => 3,
                'password' => bcrypt($data['password']),
            ]);

            $user->account_id = $user->id;
            $user->save();

            $teacher = new Teacher;
            $teacher->id=$user->id;
            $teacher->save();
            
        }
        Session::put("register","TRUE");
        return $user;
    }
}
