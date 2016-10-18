<?php

namespace App\Http\Controllers;

use Log;
use Auth;
use App\User;
use App\Quotes;
use App\UserImages;
use App\AppointmentStatus;
use App\BookingStatus;
use App\PortfolioImages;
use App\PortfolioCategories;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Mail;
use Validator;

class AdminController extends Controller {
    
    /*
     * Instantiate a new AdminController instance
     */
    
    public function __construct() {        
        if ((Auth::user()->account_type_id != '1') &&
            (Auth::user()->account_type_id != '2')) {
            abort(404);
        }        
    }
    
    /*
     * private functions
     */
    private function createFilename($extension)
    {
        $newFilename = str_random(25,50) . '.' . $extension;
        $file = PortfolioImages::where('filename', $newFilename)->first();
        if ($file) {
            $this->createFilename($extension);
        } else {
            return $newFilename;
        }
    }
    
    /*
     * pending quotes
     */
    public function getPendingQuotes()
    {
        $data['pendingQuotes'] = Quotes::where('appointment_status_id', '1')->orderBy('id', 'asc')->get();
        
        return view('admin.pendingQuotes')->with($data);
    }
    
    /*
     * all appointments
     */
    public function getAllAppointments()
    {
        $statusId = Input::get('status');
        
        if (isset($statusId)) {
            
            $status = AppointmentStatus::where('id', $statusId)->first();
            if (!$status) {
                return redirect()->back()
                        ->with('error', 'Status ID not found.');
            }
            
            $data['statusName'] = $status->status_name;
            $data['appointments'] = Quotes::where('appointment_status_id', $statusId)->orderBy('id', 'asc')->get();
            
        } else {
            $data['appointments'] = Quotes::orderBy('id', 'asc')->get();
        }
        
        $data['allStatus'] = AppointmentStatus::orderBy('id', 'asc')->get();
        
        return view('admin.allAppointments')->with($data);
    }
    
    // edit appointment details
    public function getEditDetails($id=null)
    {
        $quote = Quotes::where('id', $id)->first();
        
        if (!$quote) {
            return redirect('/members')->with('error', 'Quote not found!');
        }
        
        $data['quote'] = $quote;
        $data['allStatus'] = AppointmentStatus::orderBy('id', 'asc')->get();
        
        return view('admin.editQuoteDetails')->with($data);
    }
    
    public function postEditDetails(Request $request, $id=null)
    {
        $input = Input::all();
        
        $quote = Quotes::where('id', $id)->first();
         
        if (!$quote) {
            return redirect('/members')->with('error', 'Quote not found!');
        }
        
        $messages = [
            'consultation_time.date_format' => 'Consultation times must be in the format: 12:00am.',
            'consultation_date.date_format' => 'Consulation dates must be in the following format: YYYY-MM-DD.',
            'appointment_time.date_format' => 'Appointment times must be in the format: 12:00am.',
            'appointment_date.date_format' => 'Appointment dates must be in the following format: YYYY-MM-DD.'
        ];
        
        $rules = [
            'status' => 'in:1,2,3,4,5,6,7|numeric',
            'quote_price' => 'max:10',
            'editNeedConsult' => 'in:0,1|numeric',
            'consultation_date' => 'date_format:Y-m-d',
            'consultation_time' => 'date_format:g:ia',
            'editConsult' => 'in:0,1|numeric',
            'editAppointment' => 'in:0,1|numeric',
            'appointment_date' => 'date_format:Y-m-d',
            'appointment_time' => 'date_format:g:ia',
            'down_payment_cost' => 'max:10',
            'editDownPayment' => 'in:0,1|numeric'
        ];
        
        $validator = Validator::make($request->all(), $rules, $messages);
        
        if ($validator->fails()) {
            return redirect()->back()
                    ->withInput()
                    ->withErrors($validator);
        }
        
        // if any field is empty is has to be inserted as null
        foreach ($input as $field => $value) {
            if (empty($value)) {
                $input[$field] = null;
            }
        }
        
        // update data in db
        $quote->appointment_status_id = $input['status'];
        $quote->quote_price = $input['quote_price'];
        $quote->consultation_needed = $input['editNeedConsult'];
        $quote->consultation_date = $input['consultation_date'];
        $quote->consultation_time = $input['consultation_time'];
        $quote->consultation_confirmed = $input['editConsult'];
        $quote->appointment_made = $input['editAppointment'];
        $quote->appointment_date = $input['appointment_date'];
        $quote->appointment_time = $input['appointment_time'];
        $quote->down_payment_cost = $input['down_payment_cost'];
        $quote->down_payment_paid = $input['editDownPayment'];
        
        $quote->save();
        
        // get info of user who requested quote, make sure they
        // have email notifications set, send email if needed
        $user = User::where('id', $quote->user_id)->first();
        
        if ($user->get_email == '1'){
            // send email
            Mail::send(['text' => 'emails.quoteUpdate'], ['quoteId' => $quote->id], function($message) use ($user) {
                $message->to($user->email);
                $message->subject('Your quote has been updated!');
            });
        }
        
        return redirect('/members/details/'.$id)->with('success', 'Your changes have been saved!');
    }
    
    public function postDeleteQuote()
    {
        $input = Input::all();
        
        // get quote and any images associated
        $quote = Quotes::where('id', $input['deleteQuoteId'])->first();
        $images = UserImages::where('quote_id', $input['deleteQuoteId'])->get();
        
        if (!$quote) {
            return redirect()->back()->with('error', 'Quote not found.');
        }
        
        // delete images first to avoid foreign key check error
        foreach ($images as $image) {
            $image->delete();
        }
        
        $quote->delete();
        
        return redirect('/members')->with('success', 'Quote deleted successfully.');
    }
    
    /*
     * portfolio categories
     */
    public function getPortfolioCategories()
    {
        // get all categories
        $data['categories'] = PortfolioCategories::orderBy('id', 'asc')->get();
        
        return view('admin.portfolioCategories')->with($data);
    }
    
    public function postPortfolioCategories(Request $request)
    {
        $input = Input::all();
        $rules = [];
        
        /*
         * adding category
         */
        if ($input['formName'] == 'newForm') {
            
            // set rules for validation
            $rules = [
                'categoryName' => 'required|max:255|unique:portfolio_categories,category_name',
                'visibility' => 'required|in:visible,hidden',
            ];
            
            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors($validator);
            }
            
            if ($input['visibility'] == 'visible') {
                $hidden = '0';
            } elseif ($input['visibility'] == 'hidden') {
                $hidden = '1';
            }
            
            $addCategory = new PortfolioCategories;
            
            $addCategory->category_name = $input['categoryName'];
            $addCategory->hidden = $hidden;
            
            $addCategory->save();
                    
            // success message
            $success = "Category '" . $input['categoryName'] . "' added!";

        }
            
        /*
         * edit existing category
         */
        if ($input['formName'] == 'editForm') {
            
            // get original category info
            $getCategory = PortfolioCategories::where('category_name', $input['originalCategoryName'])->first();
            
            // uncategorized form cannot be edited
            if ($getCategory->id == '1') {
                return redirect()->back()->with('error', 'Unable to make changes to this category.');
            }
            
            // if the submitted category name is different than
            // than original name then make rule
            if ($input['newCategoryName'] != $getCategory->category_name) {
                $rules['newCategoryName'] = 'required|max:255|unique:portfolio_categories,category_name';
            }
            
            // rules for radio buttons
            $rules['editVisibility'] = 'required|in:visible,hidden';
            
            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors($validator);
            }
            
            $getCategory->category_name = $input['newCategoryName'];
            
            if ($input['editVisibility'] == 'visible') {
                $hidden = '0';
            } elseif ($input['editVisibility'] == 'hidden') {
                $hidden = '1';
            }
            
            $getCategory->hidden = $hidden;
            
            $getCategory->save();
            
            $success = "Changes to '" . $input['newCategoryName'] . "' saved.";
        }
        
        return redirect()->back()->with('success', $success);
    }
    
    /*
     * delete category
     */
    public function postDeleteCategory(Request $request)
    {
        // make sure the category name is posted
        if ($request->get('deleteCategoryName')) {
            // 'uncategorized' category cannot be deleted
            if ($request->get('deleteCategoryName') == 'Uncategorized') {
                return redirect()->back()->with('error', 'Cannot delete this category.');
                
            } else {
                // get category info to get id
                $category = PortfolioCategories::where('category_name', $request->get('deleteCategoryName'))->first();
                
                // category id
                $categoryID = $category->id;
                
                // update user images column to set all photos in
                // deleted category to 'uncategorized'
                PortfolioImages::where('category_id', $categoryID)->update(['category_id' => '1']);
                
                // delete category
                $category->delete();
            
                return redirect()->back()->with('success', $request->get('deleteCategoryName')." deleted!");
            
            }
            
        } else if (!$request->get('deleteCategoryName')) {
            
            return redirect()->back()->with('error', $request->get('deleteCategoryName')." not found.");
       
        }
        
    }
    
    /*
     * portfolio images
     */
    public function getEditPortfolioImages()
    {
        $categoryID = Input::get('category');
        
        // if viewing a specific category
        if ($categoryID) {
            $category = PortfolioCategories::where('id', $categoryID)->first();
            if ($category) {
                $data['images'] = PortfolioImages::where('category_id', $categoryID)->get();
                $data['category_name'] = $category->category_name;
            } else {
                return redirect()->back()->with('error', 'Category not found.');
            }
            
        } else {
            $data['images'] = PortfolioImages::all();
        }
        
        $data['categories'] = PortfolioCategories::orderBy('id', 'asc')->get(['id', 'category_name']);
        
        return view('admin.editPortfolioImages')->with($data);
    }
    
    public function getUploadPortfolioImages()
    {
        
        $data['categories'] = PortfolioCategories::orderBy('id', 'asc')->get(['id', 'category_name']);
        
        return view('admin.uploadPortfolioImages')->with($data);
    }
    
    public function postEditPortfolioImages(Request $request)
    {
        $input = Input::all();
        
        $rules = [
            'imageId' => 'required|numeric',
            'category' => 'required|numeric',
            'editFeatured' => 'required|in:0,1',
            'editCover' => 'required|in:0,1',
            'editHidden' => 'required|in:0,1',
            'image_title' => 'max:255',
            'image_caption' => 'max:500'
        ];
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return redirect()->back()
                    ->withErrors($validator);
        }
        
        $image = PortfolioImages::where('id', $input['imageId'])->first();
        
        if (!$image) {
            return redirect()->back()->with('error', 'Image ID not found.');
        }        
        
        if ($input['editCover'] == '1') {
            // if a cover image exists already, then update accordingly
            PortfolioImages::where('category_id', $input['category'])->update(['cover' => '0']);
        }
        
        // make sure null values get entered into db
        if (empty($input['image_title'])) {
            $input['image_title'] = null;
        }
        
        if (empty($input['image_caption'])) {
            $input['image_caption'] = null;
        }
        
        $image->category_id = $input['category'];
        $image->featured = $input['editFeatured'];
        $image->cover = $input['editCover'];
        $image->hidden = $input['editHidden'];
        $image->image_title = $input['image_title'];
        $image->image_caption = $input['image_caption'];
        
        $image->save();
        
        
        return redirect()->back()->with('success', 'Image updated!');
    }
    
    // delete portfolio image
    public function postDeletePortfolioImage()
    {
        $input = Input::all();
        
        $image = PortfolioImages::where('id', $input['deleteImageId'])->first();
        
        if (!$image) {
            return redirect()->back()->with('error', 'Image ID not found.');
        }
        
        $image->delete();
        
        return redirect()->back()->with('success', 'Image successfully deleted!');
    }
    
    /*
     * upload images
     */
    public function postUploadPortfolioImages(Request $request)
    {
        $input = Input::all();
        $files = $request->file('images');
        
        $messages = [
            'images.*.required' => 'At least one image is required.',
            'images.*.max' => 'Images must be less than 1MB.'
        ];
        
        $rules = [
            'category' => 'required|numeric',
            'images.*' => 'required|mimes:jpg,jpeg,png,bmp,tiff,gif|max:1000'
        ];
        
        $validator = Validator::make($request->all(), $rules, $messages);
        // validate all data including files
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }
        
        // set destination path for images and make sure it exists
        $destinationPath = ('../public/img/portfolio');
        if (!file_exists($destinationPath)) {
            // log error
            Log::error('Portfolio image upload storage path does not exist:' . $destinationPath);
            return redirect()->back()
                 ->with('error', 'Unable to upload images.');
        }
        
        foreach($files as $file) {
            $filename = $this->createFilename($file->getClientOriginalExtension());
            
            // make sure the file is be moved
            if (!$file->move($destinationPath, $filename)) {
                // log the error
                Log::error('Unable to upload image to folder: '. $file->getClientOriginalName());
                
                return redirect()->back()
                    ->with('error', 'Unable to upload image: '. $file->getClientOriginalName());
            }
                
            $newFile = new PortfolioImages;
                
            $newFile->filename = $filename;
            $newFile->category_id = $input['category'];
            $newFile->hidden = '0';
            $newFile->featured = '0';
            $newFile->cover = '0';
                
            $newFile->save();
                
        }
        
        return redirect('/members/admin/images')->with('success', 'Images uploaded successfully!');
            
    }
    
    /*
     * user accounts
     */    
    public function getViewUsers()
    {
        $data['users'] = User::where('id', '!=', '1')->orderBy('id', 'asc')->get();
        
        return view('admin.viewAllUsers')->with($data);
    }
    
    public function postViewUsers(Request $request)
    {
        $input = Input::all();
        
        $rules = [
            'accountType' => 'required|numeric|in:2,3',
            'editBanned' => 'required|numeric|in:0,1'
        ];
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return redirect()->back()
                    ->withErrors($validator);
        }
        
        $user = User::where('email', $input['userEmail'])->first();
        
        if ($user->account_type_id == '1') {
            Log::warning("Someone attempted to update SuperUser account.");        
            return redirect()->back()->with('error', 'Unable to save changes.');
        }
        
        $user->account_type_id = $input['accountType'];
        $user->banned = $input['editBanned'];
        
        $user->save();
        
        return redirect('/members/admin/users')->with('success', "User " . $input['userEmail'] . " updated successfully.");
    }
    
    /*
     * booking status
     */
    public function getBookingStatus()
    {
        $data['bookingStatus'] = BookingStatus::first();
        
        return view('admin.bookingStatus')->with($data);
    }
    
    public function postBookingStatus(Request $request)
    {
        $input = Input::all();
        $rules = [];
        
        $rules['can_book'] = 'required|in:0,1';
        
        if (isset($input['can_book'])) {
            if ($input['can_book'] == '1') {
                $rules['slots_available'] = 'required|numeric';
            } elseif ($input['can_book'] == '0') {
                $rules['no_bookings_until'] = 'required|date_format:Y-m-d';
            }
        }
        
        $rules['message'] = 'required';
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return redirect()->back()
                    ->withInput()
                    ->withErrors($validator);
        }
        
        $bookingStatus = BookingStatus::first();
        
        $bookingStatus->can_book = $input['can_book'];
        $bookingStatus->slots_available = $input['slots_available'];
        $bookingStatus->no_bookings_until = $input['no_bookings_until'];
        $bookingStatus->message = $input['message'];
        
        $bookingStatus->save();
        
        return redirect()->back()
                ->with('success', 'Booking status updated!');
        
    }
        
}