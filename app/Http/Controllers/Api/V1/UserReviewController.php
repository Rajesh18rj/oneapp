<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\CentralLogics\Helpers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class UserReviewController extends Controller
{

    public function index()
    {
    }

    public function show($id)
    {
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required',
            'rating' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }


        $user = auth()->user();

        $review = $user->reviews()->create([
            'comment' => $request->comment,
            'rating' => $request->rating,
        ]);

        return response()->json(['message' => 'Review Submitted Successfully'], 200);
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
}
