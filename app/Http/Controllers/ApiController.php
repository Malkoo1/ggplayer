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

    public function getStatus($id)
    {
        $data = AppRecord::where('app_id', $id)->first();

        if ($data->status == 'enable') {
            $response = [
                'status' => '200',
                'app_status'    => $data->status,
                'assign_url' => $data->assign_url
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'status' => '200',
                'app_status'    => $data->status,
                'assign_url' => ''
            ];
            return response()->json($response, 200);
        }
    }
}
