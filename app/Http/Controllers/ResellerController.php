<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AppRecord;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ResellerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware(['reseller']);
    }

    /**
     * Display a listing of resellers.
     */
    public function index()
    {
        $resellers = User::where('role', 'reseller')->orderBy('created_at', 'desc')->get();
        return view('resellers.index', compact('resellers'));
    }

    /**
     * Show the form for creating a new reseller.
     */
    public function create()
    {
        return view('resellers.create');
    }

    /**
     * Store a newly created reseller in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'credit' => 'required|numeric|min:0',
        ]);

        $reseller = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'sample_password' => $request->password,
            'role' => 'reseller',
            'credit' => $request->credit,
        ]);

        return redirect()->route('resellers.index')
            ->with('success', 'Reseller created successfully.');
    }

    /**
     * Display the specified reseller.
     */
    public function show(User $reseller)
    {
        $appRecords = AppRecord::where('reseller_id', $reseller->id)->orderBy('created_at', 'desc')->get();
        return view('resellers.show', compact('reseller', 'appRecords'));
    }

    /**
     * Show the form for editing the specified reseller.
     */
    public function edit(User $reseller)
    {
        return view('resellers.edit', compact('reseller'));
    }

    /**
     * Update the specified reseller in storage.
     */
    public function update(Request $request, User $reseller)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $reseller->id,
            'credit' => 'required|numeric|min:0',
        ]);

        $reseller->update([
            'name' => $request->name,
            'email' => $request->email,
            'credit' => $request->credit,
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
            $reseller->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('resellers.index')
            ->with('success', 'Reseller updated successfully.');
    }

    /**
     * Remove the specified reseller from storage.
     */
    public function destroy(User $reseller)
    {
        $reseller->delete();
        return redirect()->route('resellers.index')
            ->with('success', 'Reseller deleted successfully.');
    }

    /**
     * Display the reseller dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();
        $appRecords = AppRecord::where('reseller_id', $user->id)->orderBy('created_at', 'desc')->get();

        return view('resellers.dashboard', compact('appRecords'));
    }

    /**
     * Display the assign app form.
     */
    public function assignApp()
    {
        return view('resellers.assign_app');
    }

    /**
     * Search for an app to assign.
     */
    public function searchApp(Request $request)
    {
        $request->validate([
            'app_id' => 'required|string',
            'os' => 'required|in:android,ios'
        ]);

        $appRecord = AppRecord::where('app_id', $request->app_id)
            ->where('os', $request->os)
            ->where('status', 'disable')
            ->first();

        if (!$appRecord) {
            return redirect()->back()->with('fail', 'No matching app found or app is not available for assignment.');
        }

        return view('resellers.assign_app_details', compact('appRecord'));
    }

    /**
     * Search for apps via AJAX.
     */
    public function searchAppAjax(Request $request)
    {
        $request->validate([
            'app_id' => 'required|string|min:3',
        ]);

        $apps = AppRecord::where('app_id', 'like', '%' . $request->app_id . '%')
            ->where('status', 'disable')
            ->select('id', 'app_id', 'os', 'status')
            ->get()
            ->map(function ($app) {
                $app->assign_url = route('reseller.assign_app_details', $app->id);
                return $app;
            });

        return response()->json([
            'success' => true,
            'apps' => $apps
        ]);
    }

    /**
     * Display the assign app details form.
     */
    public function assignAppDetails($id)
    {
        $app = AppRecord::findOrFail($id);

        // Check if the app is still available for assignment
        if ($app->status !== 'disable') {
            return redirect()->route('reseller.dashboard')
                ->with('fail', 'This app is no longer available for assignment.');
        }

        return view('resellers.assign_app_details', compact('app'));
    }

    /**
     * Process the app assignment.
     */
    public function assignAppProcess(Request $request, $id)
    {
        $request->validate([
            'licence_pkg' => 'required|string|in:1 Month,3 Month,6 Month,12 Month,Unlimited',
            'licence_expire' => 'required|date',
            'note' => 'nullable|string',
            'credit_cost' => 'required|integer|min:0',
        ]);

        $appRecord = AppRecord::findOrFail($id);
        $user = Auth::user();

        // Check if app is still available
        if ($appRecord->status !== 'disable') {
            return redirect()->back()->with('fail', 'This app is no longer available for assignment.');
        }

        // Check if user has enough credits
        if ($user->credit < $request->credit_cost) {
            return redirect()->back()->with('fail', 'Insufficient credits. Please recharge your account.');
        }

        try {
            DB::beginTransaction();

            // Deduct credits from user
            DB::table('users')->where('id', $user->id)->update(['credit' => $user->credit - $request->credit_cost]);

            // Update app status and assign to user
            $appRecord->update([
                'status' => 'enable',
                'reseller_id' => $user->id,
                'licence_pkg' => $request->licence_pkg,
                'licence_expire' => $request->licence_expire,
                'note' => $request->note,
                'expiry_date' => $request->licence_expire
            ]);

            DB::commit();

            return redirect()->route('reseller.dashboard')
                ->with('success', 'App assigned successfully. Your license will expire on ' . date('Y-m-d H:i:s', strtotime($appRecord->licence_expire)) . '. ' . $request->credit_cost . ' credit(s) deducted.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('fail', 'An error occurred while assigning the app. Please try again.');
        }
    }

    /**
     * Logout the reseller.
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function editApp($id)
    {
        $app = AppRecord::findOrFail($id);
        return view('resellers.edit_app', compact('app'));
    }

    public function updateApp(Request $request, $id)
    {
        try {
            $app = AppRecord::findOrFail($id);
            $user = Auth::user();

            $validated = $request->validate([
                'app_id' => 'required|string|max:255',
                'assign_url' => 'nullable|url|max:255',
                'licence_pkg' => 'required|string|in:1 Month,3 Month,6 Month,12 Month,Unlimited',
                'licence_expire' => 'required|date',
                'note' => 'nullable|string|max:1000',
                'credit_cost' => 'required|integer|min:0',
            ]);

            // Check if user has enough credits
            if ($user->credit < $validated['credit_cost']) {
                return back()->with('fail', 'Insufficient credits. Please recharge your account.');
            }

            // Store credit cost before unsetting
            $creditCost = $validated['credit_cost'];

            // Start a database transaction
            DB::beginTransaction();

            try {
                // Deduct credits from user
                DB::table('users')->where('id', $user->id)->update(['credit' => $user->credit - $creditCost]);

                // Remove credit_cost from validated data before updating app
                unset($validated['credit_cost']);

                // Update the app record with all validated data
                $app->update([
                    'app_id' => $validated['app_id'],
                    'assign_url' => $validated['assign_url'],
                    'licence_pkg' => $validated['licence_pkg'],
                    'licence_expire' => $validated['licence_expire'],
                    'note' => $validated['note'] ?? null,
                ]);

                // Commit the transaction
                DB::commit();

                return redirect()->route('reseller.dashboard')
                    ->with('success', 'App record updated successfully. ' . $creditCost . ' credit(s) deducted.');
            } catch (\Exception $e) {
                // Rollback the transaction if there's an error
                DB::rollBack();
                return back()->with('fail', 'An error occurred while updating the app record: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            return back()->with('fail', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function profile()
    {
        return view('resellers.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Update user information
        $user->name = $validated['name'];

        // Update password if provided
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        // Save changes
        DB::table('users')->where('id', $user->id)->update([
            'name' => $user->name,
            'password' => $user->password,
            'sample_password' => $request->password,
        ]);

        return redirect()->route('reseller.profile')
            ->with('success', 'Profile updated successfully.');
    }
}
