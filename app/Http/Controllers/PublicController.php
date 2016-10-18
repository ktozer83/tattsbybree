<?php

namespace App\Http\Controllers;

use App\PortfolioImages;
use App\PortfolioCategories;
use App\BookingStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Mail;
use Validator;

class PublicController extends Controller {

    /**
     * Show home page
     */
    public function getHome()
    {
        
        $data['featuredImages'] = PortfolioImages::where('featured', '1')->get();
        $data['bookingStatus'] = BookingStatus::first();
        
        return view('public/home')->with($data);
    }
    
    /*
     * Show about
     */
    public function getAbout()
    {
        // static page
        return view('public/about');
    }
    
    /*
     * Show portfolio
     */
    public function getPortfolio()
    {   
        
        // if specific category
        $getCategory = Input::get('category');
        
        if ($getCategory) {
            $category = PortfolioCategories::where('id', $getCategory)->where('hidden', '0')->first();
            
            // if category is not found redirect back to portfolio
            if (!$category) {
                return redirect('/portfolio')->with('error', 'Category not found.');
            }
            
            $data['category'] = PortfolioCategories::where('id', $getCategory)->first();
            $data['categories'] = null;
            $data['images'] = PortfolioImages::where('category_id', $getCategory)->where('hidden', '0')->get();
            
        } else {
            // get cover images
            $data['images'] = PortfolioImages::where('hidden', '!=', '1')->where('cover', '1')->orderBy('cover', 'desc')->get();
            // get all categories
            $data['categories'] = PortfolioCategories::where('hidden', '!=', '1')->orderBy('id', 'asc')->get();
        }
        
        
        
        // return view with data
        return view('public/portfolio')->with($data);
    }
    
    /*
     * Show FAQ
     */
    public function getFaq()
    {
        
        $data['bookingStatus'] = BookingStatus::first();
        
        return view('public/faq')->with($data);
    }
    
    /*
     * Show contact page, get and post
     */
    public function getContact()
    {
        // static page
        return view('public/contact');
    }
    
    public function postContact(Request $request)
    {
        $input = Input::all();
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'name' => 'required',
            'message' => 'required'
        ]);
        
        if ($validator->fails()) {
            return redirect('/contact')
                ->withInput()
                ->withErrors($validator);
        }
        
        // send email
        Mail::send(['text' =>'emails.contact'], ['email' => $input['email'], 'name' => $input['name'], 'formMessage' => $input['message']], function($message) {
            $message->to('inquiries@tattsbybree.com');
            $message->subject('New message from contact form');
        });
        
        return redirect('/contact')->with('success', 'Your message has been sent. Please allow 24-48 hours for a response.');
    }
}
