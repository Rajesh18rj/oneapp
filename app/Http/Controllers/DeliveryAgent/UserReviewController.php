<?php

namespace App\Http\Controllers\DeliveryAgent;

use App\Http\Controllers\Controller;
use App\Models\DeliveryMan;
use App\Models\UserReview;
use App\Models\Zone;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\CentralLogics\Helpers;
use Illuminate\Support\Facades\DB;

class UserReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = UserReview::with('reviewable')->latest()->paginate(config('default_pagination'));

        return view('admin-views.user-review.list', compact('reviews'));
    }


    public function reviews_status(Request $request)
    {
        $review = UserReview::find($request->id);
        $review->status = $request->status;
        $review->save();
        Toastr::success(translate('messages.review_visibility_updated'));
        return back();
    }
}
