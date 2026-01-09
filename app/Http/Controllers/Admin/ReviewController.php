<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Models\DMReview;
use Illuminate\Support\Collection;
use App\Models\Store;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function list(){
   
        
        $dm_reviews = DMReview::with(['delivery_man', 'customer'])
		    ->whereHas('delivery_man', function ($query) {
			$query->where('type', 'zone_wise');
		    })->get();

	$item_reviews = Review::with(['item', 'customer'])
	    ->get();

    
    

	// Merge both sets of reviews into a single collection
	$mergedReviews = new Collection([...$dm_reviews->toArray(), ...$item_reviews->toArray()]);

	// Sort the merged collection by 'created_at' in descending order
	$mergedReviews = $mergedReviews->sortByDesc('created_at');

	// Paginate the merged collection
	$perPage = config('default_pagination');

	$page = request()->get('page', 1);
	$total = $mergedReviews->count();
	$currentPageItems = $mergedReviews->forPage($page, $perPage)->values();

	$reviews = new \Illuminate\Pagination\LengthAwarePaginator(
	    $currentPageItems,
	    $total,
	    $perPage,
	    $page,
	    ['path' => request()->url()]
	);


        

        return view('admin-views.reviews.list',compact('reviews'));
    }
    
    
    public function update_review_status(Request $request)
    {
        $review_id = request()->segment(4); 
	$status = request()->segment(5); 
	$type = request()->segment(6);	
	$user_id = request()->segment(7);
	$review_to_id = request()->segment(8);
	
	
	// Check if a record with the given id and type exists
	$existingReview = DB::table('review')
	    ->where('review_to_id', $review_to_id)
	    ->where('review_id', $review_id)
	    ->where('user_id', $user_id)
	    ->where('type', $type)
	    ->first();
	    if ($existingReview) {
	
	    // Update the existing record
	    DB::table('review')
		->where('review_to_id', $review_to_id)
		 ->where('user_id', $user_id)
		 ->where('review_id', $review_id)
		->where('type', $type)
		->update(['admin_status' => $status]);

	    // Display success message
	    if ($status == 'approve') {
		Toastr::success(translate('Review approved successfully'));
	    } else {
		Toastr::success(translate('Review rejected successfully'));
	    }
	} else {
	    // Insert a new record
	    DB::table('review')->insert([
		'review_to_id' => $review_to_id,
		'review_id' => $review_id,
		'user_id' => $user_id,
		'type' => $type,
		'admin_status' => $status
	    ]);

	    // Display success message for new record
	    if ($status == 'approve') {
		Toastr::success(translate('Review approved successfully'));
	    } else {
		Toastr::success(translate('Review rejected successfully'));
	    }
	}
        
        return back();
    }
    

}
