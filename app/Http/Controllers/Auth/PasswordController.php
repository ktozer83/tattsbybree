<?php

namespace App\Http\Controllers\Auth;

use Mail;
use Validator;
use App\User;
use App\Reset;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PasswordController extends Controller
{

    protected function generateToken()
    {
        // get a random number between 50 and 100 for length
        $randInt = rand(50,100);
        // create random string
        $token = str_random($randInt);
        // check to make sure token is not already used in db
        $tokenCheck = Reset::where('token', $token)->first();
        if ($tokenCheck) {
            $this->generateToken();
        }
        // return token
        return $token;
    }    
    
    /*
     * Forgot Password Form
     */
    public function getForgotPass()
    {
        return view('auth.forgot');
    }
    
    public function postForgotPass(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);
        
        if ($validator->fails()) {
            return redirect('/forgot')
                ->withInput()
                ->withErrors($validator);
        }
        
        $email = $request->input('email'); 
        $user = User::where('email', $email)->first();
        
        // if the user is found that means the email can be sent
        if ($user) {
            
            // generate token
            $token = $this->generateToken();
            // set expiration date
            $carbon = new Carbon();
            $expires = $carbon->addDays(2);
            
            // insert data into password_resets table
            $reset = new Reset;
            
            $reset->email = $email;
            $reset->token = $token;
            $reset->expire_date = $expires;
            
            $reset->save();
            
            // send email
            Mail::send('emails.password', ['email' => $email, 'token' => $token], function($message) use ($email) {
                $message->to($email);
                $message->subject('Password Reset');
            });
        }
        
        // show user confirm message regardless to avoid giving emails away
        return view('auth.forgotConfirm');
    }
    
    /*
     * Reset Password Form
     */
    public function getResetPassword($token=null)
    {
        if ($token === null) {
            return redirect('/')->with('error', 'Missing token.');
        }
        $email = Input::get('email');
        $resetVerify = Reset::where(['token' => $token, 'email' => $email])->first();
        
        if ((!$resetVerify) or (strtotime($resetVerify->expire_date) <= time())) {
            return redirect('/')
                ->with('error', 'badToken');
        }
        
        return view('auth.resetPassword');
    }
    
    public function postResetPassword(Request $request, $token)
    {
        $input = Input::all();
        
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:8|max:255|confirmed',
            'password_confirmation' => 'required'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        // get user entry to update password
        $user = User::where('email', $input['email'])->first();
        // get reset entry to set expired date
        $reset = Reset::where(['token' => $token, 'email' => $input['email']])->first();
        
        // update password
        $user->password = bcrypt($input['password']);
        $user->save();
        
        // set token to expired
        $reset->expire_date = Carbon::now();
        $reset->save();
        
        // redirect user to login page
        return redirect('/login')
            ->with('success', 'Your password has been changed. Please log in below using your new password.');
        
    }
}
