<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\AppRecord;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }
    public function userLicence($id)
    {
        $licence = AppRecord::find($id);
        $daysLeft = null; // Initialize daysLeft

        if ($licence->licence_pkg && $licence->licence_expire) {
            $expiryDate = Carbon::parse($licence->licence_expire);
            $now = Carbon::now();

            if ($expiryDate->greaterThan($now)) {
                $daysLeft = round($now->diffInDays($expiryDate));
            } else {
                $daysLeft = 0; // Or a negative value if you want to show it's expired
            }
        }

        return view('licence', compact('licence', 'daysLeft'));
    }

    public function makeUserLicence(Request $request, $id)
    {
        $licence = AppRecord::findOrFail($id); // Find the AppRecord or fail if not found

        // Get the selected licence package value from the form
        $licencePkgValue = $request->input('plan');

        // Determine the licence package name based on the value (you might need to adjust this)
        $licencePkgName = '';
        switch ($licencePkgValue) {
            case '1':
                $licencePkgName = '1 Month';
                break;
            case '3':
                $licencePkgName = '3 Month';
                break;
            case '6':
                $licencePkgName = '6 Month';
                break;
            case '12':
                $licencePkgName = '12 Month';
                break;
            case 'unlimited':
                $licencePkgName = 'Unlimited';
                break;
            default:
                $licencePkgName = 'Unknown'; // Handle cases where the value doesn't match
                break;
        }

        // Calculate the expiry date
        if ($licencePkgValue === 'unlimited') {
            $licenceExpire = Carbon::now()->addYears(100); // Set to a very far future date
        } else {
            $monthsToAdd = (int) $licencePkgValue;
            $licenceExpire = Carbon::now()->addMonths($monthsToAdd);
        }

        // Get the note from the form
        $note = $request->input('note');

        // Update the AppRecord
        $licence->licence_pkg = $licencePkgName;
        $licence->licence_expire = $licenceExpire;
        $licence->note = $note;
        $licence->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Licence updated successfully.');
    }

    public function removeUserLicence($id)
    {
        $licence = AppRecord::findOrFail($id); // Find the AppRecord or fail if not found

        $licence->licence_pkg = null;
        $licence->licence_expire = null;
        $licence->note = null;
        $licence->save();

        return redirect()->back()->with('success', 'Licence removed successfully.');
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
    public function settingsPage()
    {
        return view('settings');
    }

    public function updateSettings(Request $request)
    {

        $data = User::find(auth()->user()->id);
        $data->approve_status = $request->auto_approve ?? 'off';

        $data->assign_url = $request->assing_url;
        $data->update();
        return  redirect('/admin/settings')->with('success', 'Settings Updated Successfully!');
    }
    public function userDelete($id)
    {

        $data = AppRecord::find($id)->delete();
        return  redirect('/admin/home')->with('success', 'App Deleted Successfully!');
    }
}
