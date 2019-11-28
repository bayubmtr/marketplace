<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Page;

class PageController extends AdminController
{


	public function index(){
		$datas = Page::with('photo_galery');

    if(request()->has('title'))
			$datas->where('title','like','%'.request('title').'%');

    if(request()->has('status'))
      $datas->whereStatus(request('status'));

      $datas->orderBy('id',request('order'));

		return $datas->paginate(request('pageLength'));
	}

    public function updateProfile(Request $request){

        $validation = Validator::make($request->all(),[
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'date_of_birth' => 'required|date_format:Y-m-d',
            'gender' => 'required|in:male,female'
        ]);

        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first()],422);

        $user = JWTAuth::parseToken()->authenticate();
        $profile = $user->Profile;

        $profile->first_name = request('first_name');
        $profile->last_name = request('last_name');
        $profile->date_of_birth = request('date_of_birth');
        $profile->gender = request('gender');
        $profile->twitter_profile = request('twitter_profile');
        $profile->facebook_profile = request('facebook_profile');
        $profile->google_plus_profile = request('google_plus_profile');
        $profile->save();

        return response()->json(['message' => 'Your profile has been updated!','user' => $user]);
    }

    public function updateAvatar(Request $request){
        $validation = Validator::make($request->all(), [
            'avatar' => 'required|image'
        ]);

        if ($validation->fails())
            return response()->json(['message' => $validation->messages()->first()],422);

        $user = JWTAuth::parseToken()->authenticate();
        $profile = $user->Profile;

        if($profile->avatar && \File::exists($this->avatar_path.$profile->avatar))
            \File::delete($this->avatar_path.$profile->avatar);

        $extension = $request->file('avatar')->getClientOriginalExtension();
        $filename = uniqid();
        $file = $request->file('avatar')->move($this->avatar_path, $filename.".".$extension);
        $img = \Image::make($this->avatar_path.$filename.".".$extension);
        $img->resize(200, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save($this->avatar_path.$filename.".".$extension);
        $profile->avatar = $filename.".".$extension;
        $profile->save();

        return response()->json(['message' => 'Avatar updated!','profile' => $profile]);
    }

    public function removeAvatar(Request $request){

        $user = JWTAuth::parseToken()->authenticate();

        $profile = $user->Profile;
        if(!$profile->avatar)
            return response()->json(['message' => 'No avatar uploaded!'],422);

        if(\File::exists($this->avatar_path.$profile->avatar))
            \File::delete($this->avatar_path.$profile->avatar);

        $profile->avatar = null;
        $profile->save();

        return response()->json(['message' => 'Avatar removed!']);
    }

    public function destroy(Request $request, $id){
        if(env('IS_DEMO'))
            return response()->json(['message' => 'You are not allowed to perform this action in this mode.'],422);

        $user = \App\User::find($id);

        if(!$user)
            return response()->json(['message' => 'Couldnot find user!'],422);

        if($user->avatar && \File::exists($this->avatar_path.$user->avatar))
            \File::delete($this->avatar_path.$user->avatar);

        $user->delete();

        return response()->json(['success','message' => 'User deleted!']);
    }

    public function dashboard(){
      $users_count = \App\User::count();
      $tasks_count = \App\Task::count();
      $recent_incomplete_tasks = \App\Task::whereStatus(0)->orderBy('due_date','desc')->limit(5)->get();
      return response()->json(compact('users_count','tasks_count','recent_incomplete_tasks'));
    }
}
