<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Product_discussion;
use App\Product_discussion_reply;
use DB;

class ProductDiscussionController extends UserController
{
    public function index(){
        if(request()->has('product_id')){
            $discussion = Product_discussion::with('reply.user:id,user_id,first_name,last_name,avatar', 'user:id,user_id,first_name,last_name,avatar')->where('product_id', request('product_id'))->where('parent_id', null)->orderBy('created_at','Desc');
            return ok($discussion->paginate(5));
        }
    }
    public function store(Request $request){
        if(request('type') == 'discussion'){
            $validatedData = $request->validate([
                'discussion' => 'required|max:255',
                'product_id' => 'required|exists:products,id',
            ]);
            $discussion = new Product_discussion([
                'user_id' => auth()->user()->id,
                'product_id' => request('product_id'),
                'discussion' => request('discussion')
            ]);
            $discussion->save();
        }elseif(request('type') == 'discussion_reply'){
            $validatedData = $request->validate([
                'discussion_reply' => 'required|max:255',
                'discussion_id' => 'required|exists:product_discussions,id',
                'product_id' => 'required|exists:products,id'
            ]);
            $discussion = new Product_discussion([
                'user_id' => auth()->user()->id,
                'parent_id' => request('discussion_id'),
                'product_id' => request('product_id'),
                'discussion' => request('discussion_reply')
            ]);
            $discussion->save();
        }
        return ok($discussion);
    }
    public function destroy($discussion_id){
        if(request('type') == 'discussion'){
            $discussion = Product_discussion::find($discussion_id);
            if(!$discussion){
                return response()->json(['status' => '404', 'msg' => 'not found !'], 404);
            }
            if(auth()->user()->id == $discussion->user_id){
                $discussion->delete();
                return response()->json(['status' => '201', 'msg' => 'success', 'data' => $discussion]);
            }else{
                return response()->json(['status' => '403', 'msg' => 'not authorized !'], 403);
            }
        }elseif(request('type') == 'discussion_reply'){
            $discussion = Product_discussion::find($discussion_id);
            if(!$discussion){
                return response()->json(['status' => '404', 'msg' => 'not found !'], 404);
            }
            if(auth()->user()->id == $discussion->user_id){
                $discussion->delete();
                return response()->json(['status' => '201', 'msg' => 'success', 'data' => $discussion]);
            }else{
                return response()->json(['status' => '403', 'msg' => 'not authorized !'], 403);
            }
        }
        
    }
}