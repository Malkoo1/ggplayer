<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\AppRecord;

class AdminController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function authLogin(Request $request)
    {
        $inputVal = $request->all();


        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);


        if (auth()->attempt(array('email' => $inputVal['email'], 'password' => $inputVal['password']))) {
            return redirect()->route('home');
        } else {
            return redirect()->route('login')
                ->with('fail', 'Email & Password are incorrect.');
        }
    }

    public function adminHome()
    {

        $data = AppRecord::orderBy('id', 'desc')->get();
        return view('index', compact('data'));
    }


    public function switchUpdate(Request $request)
    {

        $data = AppRecord::find($request->id);
        $data->status = $request->status;
        $data->update();

        $response = [
            'status' => '200',
            'message' => "sucessfully Status Changed",
            'data'   => $data
        ];
        return response()->json($response, 200);
    }

    public function updateUrl(Request $request, $id)
    {

        $validatedData = $request->validate([
            'assign_url' => 'required',
        ]);

        $data = AppRecord::find($id);
        $data->assign_url = $request->assign_url;
        $data->update();

        return  redirect()->back()->with('success', 'Assign URL Updated Successfully!');
    }




    public function updateShowPage()
    {
        return view('updatepassword');
    }

    public function updateLoginCrend(Request $request)
    {


        $validatedData = $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['required', 'same:new_password'],
            'email' => ['required', 'email'],

        ]);

        User::find(auth()->user()->id)->update(['password' => Hash::make($request->new_password), 'email' => $request->email]);

        return  redirect('/admin/updatepassword')->with('success', 'Your Crenditional Updated Successfully!');
    }

    public function signOut()
    {
        Session::flush();
        Auth::logout();
        return redirect()->route('login');
    }
}
