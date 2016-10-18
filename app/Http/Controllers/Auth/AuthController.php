<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Support\Facades\Input;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use ThrottlesLogins;

    protected $loginPath = '/login';

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'account_type_id' => $data['account_type']
        ]);
    }
    
    /*
     * Register
     */
    public function getRegister()
    {
        return view('auth.register');
    }
    
    public function postRegister(Request $request)
    {
        $input = Input::all();
        $input['account_type'] = "3";
        // set custom error messages
        $messages = [
            'password_confirmation.confirmed' => 'The passwords entered do not match.',
            'g-recaptcha-response.required' => 'You must prove you are a human!'
        ];
        
        // initialize validator, set rules, and messages
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255|unique:users,email',
            'name' => 'required|max:255',
            'password' => 'required|min:8|max:255|confirmed',
            'password_confirmation' => 'required',
            'g-recaptcha-response' => 'required'
        ], $messages);
        
        // if validation fails redirect to register with errors
        if ($validator->fails()) {
            return redirect('/register')
                ->withInput(Input::except('password, password-confirmation'))
                ->withErrors($validator);
        }
        
        // verify recaptcha
        $secretKey = env('REGISTER_SECRET');
        $response = $input['g-recaptcha-response'];
        $userIp = $request->ip();
        
        $verifyResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$response&remoteip=$userIp");
        $responseData = json_decode($verifyResponse);
        
        if (!$responseData->success) {
            return redirect()->back()->with('error', 'Unable to register account at this time.');
        }
        
        // create new user and redirect
        if ($this->create($input)) {
            Auth::attempt(['email' => $input['email'], 'password' => $input['password']]);
            return redirect('/members')
                ->with('success', 'Thank you for registering!');
        }
        
    }
    
    /*
     * Login
     */
    public function getLogin()
    {
        if (Auth::user()) {
            return redirect('/');
        } else {
            return view('auth.login');
        }
    }
    
    public function postLogin(Request $request)
    {   
        $input = Input::all();
        
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);
        
        if ($validator->fails()) {
            return redirect('/login')
                ->withInput(Input::except('password'))
                ->withErrors($validator);
        }
        
        // this needs to be done to avoid an 'undefined index' error
        if (!empty($input['remember'])) {
            $remember = true;
        } else {
            $remember = false;
        }
        
        if (Auth::attempt([
            'email' => $input['email'], 
            'password' => $input['password']
            ], $remember))
        {
            
            // check if user is banned
            if (Auth::user()->banned == '1') {
                Auth::logout();
                return redirect('/')->with('error', 'Your account has been banned.');
            }
            
            return redirect()->intended('/members')->with('success', 'You are now logged in!');
        } else {
            return redirect('/login')
                ->withInput()
                ->with('error', 'Incorrect email or password.');
        }
        
        return view('auth.login');
    }
    
    /*
     * Logout
     */
    public function doLogout()
    {   
        Auth::logout();
        return redirect('/')
            ->with('success', 'You are now logged out.');
    }
}
