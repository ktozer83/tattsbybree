<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\BookingStatus;
use App\Quotes;
use App\User;
use App\UserImages;
use App\PortfolioCategories;
use App\PortfolioImages;
use App\QuoteComments;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Mail;
use Validator;

class PrivateController extends Controller {
    
    /*
     * private functions
     */
    private function createFilename($extension)
    {
        $newFilename = str_random(25,50) . '.' . $extension;
        $file = UserImages::where('filename', $newFilename)->first();
        if ($file) {
            $this->createFilename($extension);
        } else {
            return $newFilename;
        }
    }
    
    
    /*
     * members home page
     */
    public function getMembersHome()
    {
        
        // get booking status    
        $data['bookingStatus'] = BookingStatus::first();
        
        // if user is an admin or regular user
        if ((Auth::user()->account_type_id == '1') or (Auth::user()->account_type_id == '2')) {
            
            $data['pendingCount'] = count(Quotes::where('appointment_status_id', '1')->get(['id']));
            
            $data['categoryCount'] = count(PortfolioCategories::get(['id']));
            
            $data['imageCount'] = count(PortfolioImages::get(['id']));
            
            $data['recentUser'] = User::orderBy('id', 'desc')->where('id', '!=', '1')->first(['name', 'created_at']);
            
            // return admin view
            return view('admin.membersAdminHome')->with($data);
            
        } elseif (Auth::user()->account_type_id == '3') {
            
            $data['appointments'] = Quotes::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();
            
        
            // return view with data
            return view('private.membersUserHome')->with($data);
            
        }
    }
    
    /*
     * quote form page
     */
    public function getQuoteForm()
    {
        $data['bookingStatus'] = BookingStatus::first();
        
        return view('private.newQuoteForm')->with($data);
    }
    
    public function postQuoteForm(Request $request)
    {   
        
        $input = Input::all();
        $files = $request->file('images');
        
        $messages = [
            'images.*.required' => 'At least one image is required.',
            'images.*.max' => 'Images must be less than 1MB.'
        ];
        
        // remove brackets, dashes and spaces from phone number
        if (isset($request['phone_number'])) {
            $request['phone_number'] = str_replace('(', "", $request['phone_number']);
            $request['phone_number'] = str_replace(')', "", $request['phone_number']);
            $request['phone_number'] = str_replace('-', "", $request['phone_number']);
            $request['phone_number'] = str_replace(' ', "", $request['phone_number']);
        }
                
        $rules = [
            'phone_number' => 'required|digits:10|numeric',
            'detail' => 'required|in:colour,black_white',
            'budget_range' => 'required',
            'description' => 'required|max:1000',
            'images.*' => 'required|mimes:jpg,jpeg,png,bmp,tiff,gif|max:1000'
        ];
        
        $validator = Validator::make($request->all(), $rules, $messages);
        // validate all data including files
        if ($validator->fails()) {
            return redirect('/members/quote')
                ->withInput()
                ->withErrors($validator);
        }

        // set destination path for images and make sure it exists
        $destinationPath = ('../public/img/user_img');
        if (!file_exists($destinationPath)) {
            // log error
            Log::error('User upload storage path does not exist:' . $destinationPath);
            return redirect('/members/quote')
                 ->with('error', 'Unable to upload images.');
        }

        $newQuote = new Quotes;
        // insert quote data into db
        DB::transaction(function() use ($request, $input, &$newQuote)
        {
            // using a transaction will allow us to rollback
            // if anything bad happens further down the line
            
            $newQuote->user_id = Auth::user()->id;
            $newQuote->client_name = Auth::user()->name;
            $newQuote->email = Auth::user()->email;
            $newQuote->phone_number = $request['phone_number'];
            $newQuote->budget_range = $input['budget_range'];
            if ($input['detail'] == 'black_white') {
                $newQuote->black_white = 1;
                $newQuote->colour = 0;
            } else {
                $newQuote->black_white = 0;
                $newQuote->colour = 1;
            }
            $newQuote->description = $input['description'];
            
            $newQuote->save();
            
            return compact('newQuote');
        });
        
        foreach($files as $file) {
            $filename = $this->createFilename($file->getClientOriginalExtension());
            
            //make sure the file is be moved
            if (!$file->move($destinationPath, $filename)) {
                //log the error
                Log::error('Unable to upload image to folder: '. $file->getClientOriginalName());
                
                // rollback db transaction as error has occured
                DB::rollback();
                
                return redirect('/members/quote')
                    ->with('error', 'Unable to upload image: '. $file->getClientOriginalName());
                
            }
            // save quote in db first to get quote id for user_images
            $fileUpload = new UserImages;
            
            $fileUpload->quote_id = $newQuote->id;
            $fileUpload->filename = $filename;
            $fileUpload->user = Auth::user()->id;
            
            $fileUpload->save();
            
        }
        
        // send email
        Mail::send(['text' =>'emails.newQuote'], ['name' => Auth::user()->name, 'quoteId' => $newQuote->id], function($message) {
            $message->to('inquiries@tattsbybree.com');
            $message->subject('A new quote has been submitted!');
        });
        
        // redirect user to home page
        return redirect('/members')
            ->with('success', 'Your quote has been submitted! Please allow 24-48 hours for a response.');
    }
    
    /*
     * details
     */
    public function getDetails($id=null)
    {
        
        if ($id == null) {
            abort(404);
        }
        
        // get appointment/quote
        $quote = Quotes::where('id', $id)->first();
        
        // make sure user is allowed to view appointment
        if ((Auth::user()->id != $quote->user_id) &&
            (Auth::user()->account_type_id != '1') &&
            (Auth::user()->account_type_id != '2')) 
        {
            abort(404);
        }
        
        $data['quote'] = $quote;
        
        return view('private.details')->with($data);
    }
    
    public function postDetails(Request $request, $id)
    {
        $input = Input::all();
        
        $validator = Validator::make($request->all(), [
            'comment' => 'required|max:1000'
        ]);
        
        if ($validator->fails()) {
            return redirect("members/details/$id")
                ->withInput()
                ->withErrors($validator);
        }
        
        $quote = Quotes::where('id', $id)->first();
        
        // check is user who submitted comment was og quote creator
        if (Auth::user()->id != $quote->user_id) {
            $user = User::where('id', $quote->user_id)->first();
            //admin made comment
            $email = $user->email;
        } else {
            //user made comment
            $email = 'inquiries@tattsbybree.com';
        }
        
        // send mail
        Mail::send(['text' =>'emails.newComment'], ['name' => Auth::user()->name, 'quoteId' => $id], function($message) use ($email) {
            $message->to($email);
            $message->subject('A new comment has been added!');
        });
        
        // save quote to db
        $newComment = new QuoteComments;
        
        $newComment->quote_id = $id;
        $newComment->user_name = Auth::user()->name;
        $newComment->comment = $input['comment'];
        
        $newComment->save();
        
        return redirect("/members/details/$id")->with('success', 'Your comment has been added!');
        
    }
}