<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\ChatRecord;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;
use App\Models\AppRecord;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getAppId(Request $request)
    {

        $validatedData = $request->validate([
            'oprating_system' => 'required'
        ]);
        $user = User::find(1);
        if (isset($request->app_id)) {
            $data = AppRecord::where('app_id', $request->app_id)->first();
            if ($data) {
                $response = [
                    'status' => '200',
                    'data'    => $data,
                ];
                return response()->json($response, 200);
            } else {
                $data = new AppRecord();
                $data->app_id = $this->generateUniqueAppId(); // Generate a unique 6-digit app_id
                $data->os = $request->oprating_system;
                if ($user->approve_status == "on") {
                    $data->status = "enable";
                    $data->assign_url = $user->assign_url;
                }
                $data->save();

                $response = [
                    'status' => '200',
                    'data'   => $data,
                ];
                return response()->json($response, 200);
            }
        }

        $data = new AppRecord();
        $data->app_id = $this->generateUniqueAppId(); // Generate a unique 6-digit app_id
        $data->os = $request->oprating_system;
        if ($user->approve_status == "on") {
            $data->status = "enable";
            $data->assign_url = $user->assign_url;
        }
        $data->save();

        $response = [
            'status' => '200',
            'data'   => $data,
        ];
        return response()->json($response, 200);
    }

    function generateUniqueAppId()
    {
        do {
            $appId = mt_rand(100000, 999999); // Generate a random 6-digit number
        } while (AppRecord::where('app_id', $appId)->exists());

        return $appId;
    }

    // public function getStatus($id)
    // {
    //     $data = AppRecord::where('app_id', $id)->first();

    //     if ($data->status == 'enable') {
    //         $response = [
    //             'status' => '200',
    //             'app_status'    => $data->status,
    //             'assign_url' => $data->assign_url
    //         ];
    //         return response()->json($response, 200);
    //     } else {
    //         $response = [
    //             'status' => '200',
    //             'app_status'    => $data->status,
    //             'assign_url' => ''
    //         ];
    //         return response()->json($response, 200);
    //     }
    // }

    public function getStatus($id)
    {
        $data = AppRecord::where('app_id', $id)->first();

        if ($data) {
            if ($data->status == 'enable') {
                if ($data->licence_pkg && $data->licence_expire) {
                    $expiryDate = \Carbon\Carbon::parse($data->licence_expire);
                    $formattedExpiryDate = $expiryDate->format('F j, Y');
                    $now = \Carbon\Carbon::now();
                    $licenceStatus = 'active';

                    if ($expiryDate->lessThan($now)) {
                        $licenceStatus = 'expired';
                    }

                    $response = [
                        'status' => '200',
                        'app_status' => $data->status,
                        'assign_url' => $data->assign_url,
                        'licence_pkg' => $data->licence_pkg,
                        'licence_expire' => $formattedExpiryDate,
                        'licence_status' => $licenceStatus,
                    ];
                    return response()->json($response, 200);
                } else {
                    $response = [
                        'status' => '200',
                        'app_status' => $data->status,
                        'assign_url' => $data->assign_url,
                        'licence_status' => 'inactive', // Or any other appropriate status
                    ];
                    return response()->json($response, 200);
                }
            } else {
                $response = [
                    'status' => '200',
                    'app_status' => $data->status,
                    'assign_url' => '',
                    'licence_status' => 'inactive', // Or any other appropriate status
                ];
                return response()->json($response, 200);
            }
        } else {
            $response = [
                'status' => '404',
                'message' => 'App Record not found',
            ];
            return response()->json($response, 404);
        }
    }
}
