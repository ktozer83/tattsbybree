<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Validator;
use App\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    
    /*
     * Change account settings
     */
    public function getAccountSettings()
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Please login to continue.');
        }
        
        return view('auth.accountSettings');
    }
    
    public function postAccountSettings(Request $request)
    {
        $input = Input::all();
        $rules['name'] = 'required|max:255';
        
        // if the email submitted is different then make rules
        if ($input['email'] != Auth::user()->email) {
            $rules['email'] = 'required|email|max:255|unique:users,email';
        }
        
        // if the password box has been filled out then make rules
        if ($input['password']) {
            $rules['password'] = 'required|min:8|max:255|confirmed';
            $rules['password_confirmation'] = 'required';
        }
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return redirect('/members/settings')
                ->withInput()
                ->withErrors($validator);
        }
        
        // get instance of user
        $user = User::find(Auth::user()->id);
        
        // save updated info
        $user->email = $input['email'];
        $user->name = $input['name'];
        
        if ($input['password']){
            $user->password = bcrypt($input['password']);
        }
        
        if ($input['emailNotifications']) {
            $user->get_email = $input['emailNotifications'];
        } else {
            $user->get_email = 0;
        }
        
        if (!$user->save()) {
            return redirect()->back()->with('error', 'Unable to update user settings.');
        }
        
        // redirect back to settings page with success message
        return redirect('/members/settings')->with('success', 'Your account settings have been updated!');
    }
}
