<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use App\Admin;
use App\Mail\AdminResetPassword;
use DB;
use Carbon\Carbon;
use Mail;

class AdminAuth extends Controller
{
    public function login()
    {
    	return view('admin.login');
    }

    public function dologin()
    {
    	//check on email, password, and checkbox[remmberme] for admin to login
    	$remmberme=request('remmeberme')==1?true:false;

    	if(adguard()->attempt([
    	'email'    =>request('email'), //name in form
    	'password' =>request('password')
    	], $remmberme))
    	{
    		return redirect('admin');
    	}else{
    		session()->flash('error','admin.Incorrect_login');
    		return redirect(aurl('login'));
    	}
    }

    public function logout()
    {
    	auth()->guard('admin')->logout();
    	return redirect(aurl('login'));
    }

    public function forgot_password()
    {
    	return view('admin.forgot_password');
    }

    public function forgot_password_post()
    {
    	//call model Admin ,and check email
    	$admin = Admin::where('email', request('email'))->first();
    	if(!empty($admin)){
    		//create Token
    		$token = app('auth.password.broker')->createToken($admin);
    		$data  = DB::table('password_resets')->insert([
    			'email'=>$admin->email,
    			'token'=>$token,
    			'created_at'=>Carbon::now() //call carbon  
    		]);
    		Mail::to($admin->email)->send(new AdminResetPassword(['data'=>$admin, 'token'=>$token]));
            session()->flash('success',trans('admin.the_link_sent'));
            return back();
    	}
    	return back();
    }

    public function reset_password($token)
    {
        $check_token = DB::table('password_resets')->where('token',$token)
                       ->where('created_at','>',Carbon::now(),subHours(2))->first();

        if(!empty($check_token)){
            return view('admin.reset_password', ['data'=>$check_token]);
        }else{
          return redirect(aurl('forgot/password'));  
        } 

    }

    public function reset_password_final($token)
    {
        //Validaton
        $this->validate([
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ],[],[
            //nice name
            'password' => 'password',
            'password_confirmation' => 'Password Confirmation'
        ]);

        //check on token
       $check_token = DB::table('password_resets')->where('token',$token)
                       ->where('created_at','>',Carbon::now()->subHours(2))->first();

        //!empty => change password
        if(!empty($check_token)){
            $admin = Admin::where('email', $check_token->email)->update(['email'=>$check_token->email, 'password'=>bcrypt(request('password'))]);

            //clear data
            DB::table('password_resets')->where('email',request('email'))->delete();

            //redirect
            adguard('admin')->attempt(['email'=>$check_token->email, //name in form
            'password'=>request('password')], true);//redirect to login with data auth guard

            return redirect(aurl(''));
        }else{
            return redirect(aurl('forget/password'));
        } 
    }
}
