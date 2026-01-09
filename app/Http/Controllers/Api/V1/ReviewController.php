<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\CentralLogics\Helpers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\DMReview;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{

    public function getAllReviewsold()
{
    $item_reviews = Review::with(['customer', 'item'])
                          ->where('admin_status', 'approve')
                          ->get();
                          
   /*  $item_reviews = DB::table('reviews')
    ->select('reviews.*', 'users.*')
    ->join('users', 'reviews.user_id', '=', 'users.id')
    ->join('items', 'reviews.item_id', '=', 'items.id')
    ->join('review', 'review.review_id', '=', 'reviews.id')
    ->where('review.admin_status', 'approve')
    ->get();*/

    $dm_reviews = DMReview::with(['customer', 'delivery_man'])
                          ->where('admin_status', 'approve')
                          ->get();

    // Merge the collections
  //  $reviews = $item_reviews->concat($dm_reviews);
	$reviews = $item_reviews;
    // Additional processing if needed
    $storage = [];
    foreach ($reviews as $temp) {
        $temp['attachment'] = json_decode($temp['attachment']);
        $temp['item_name'] = null;
        if ($temp->item) {
            $temp['item_name'] = $temp->item->name;
            if (count($temp->item->translations) > 0) {
                $translate = array_column($temp->item->translations->toArray(), 'value', 'key');
                $temp['item_name'] = $translate['name'];
            }
        }

        unset($temp['item']);
        array_push($storage, $temp);
    }
    
    $data = array('status' => true,'data' => $storage);

    return response()->json($data, 200);
}


    public function getAllReviews()
{
    $item_reviews = Review::with(['customer', 'item'])
                          ->get();
                          

    
    
    

    $dm_reviews = DMReview::with(['customer', 'delivery_man'])
                          ->get();

    // Merge the collections
   $reviews = $item_reviews->concat($dm_reviews);

    // Additional processing if needed
    $storage = [];
    foreach ($reviews as $temp) {
        $temp['attachment'] = json_decode($temp['attachment']);
        $temp['item_name'] = null;
        if ($temp->item) {
            $temp['item_name'] = $temp->item->name;
            if (count($temp->item->translations) > 0) {
                $translate = array_column($temp->item->translations->toArray(), 'value', 'key');
                $temp['item_name'] = $translate['name'];
            }
        }

        
       
        
        	$user_id = $temp->customer->id;
        	 $review_to_id = $temp['delivery_man_id'];	
        	
        
      if (!empty($review_to_id)){
           $type = 'delivery_man';
           $review_to_id = $temp['delivery_man_id'];	

                                       	
    	} else {
    	
    	if (!empty($temp['item'])){
            $type = 'item';
            $review_to_id = $temp['item']['id'];
            
        	
    	} else {
    	  $type = '';
            $review_to_id = '';
    	
    	}
    	
    		
    	}
    	
    		
    	
    
                                    	
    	
    	$review_id = $temp['id'];
    	
    	

    	
        if(!empty($review_to_id)){
           $reviewData = DB::table('review')
	    ->where('review_to_id', $review_to_id)
	    ->where('review_id', $review_id)
	    ->where('user_id', $user_id)
	    ->where('type', $type)
	    ->first();
	    
	    
	    if(isset($reviewData) && !empty($reviewData)){
	    		$admin_status = $reviewData->admin_status;
	    		if($admin_status == 'approve'){
	    			unset($temp['item']);
        		array_push($storage, $temp);
	    		
	    		}
	    		
	    }
        
        }
       
 

        
    }

     $data = array('status' => true,'data' => $storage);

    return response()->json($data, 200);
}

}
