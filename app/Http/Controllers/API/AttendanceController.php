<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    //checkin
    public function checkin(Request $request)
    {
        //validate lat and long
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        //get today attendance
        $attendance = Attendance::where('user_id', $request->user()->id)
            ->where('date', date('Y-m-d'))
            ->first();

        //check if attendance not found
        if (!$attendance) {
            //save new attendance
            $attendance = new Attendance;
            $attendance->user_id = $request->user()->id;
            $attendance->date = date('Y-m-d');
            $attendance->time_in = date('H:i:s');
            $attendance->latlong_in = $request->latitude . ',' . $request->longitude;
            $attendance->save();

            return response([

                'message' => 'Checkin success',
                'attendance' => $attendance
            ], 200);
        }

        return response([

            'message' => 'You have already checkin today'
        ], 400);
    }
    //checkout
    public function checkout(Request $request)
    {
        //validate lat and long
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        //get today attendance
        $attendance = Attendance::where('user_id', $request->user()->id)
            ->where('date', date('Y-m-d'))
            ->first();

        //check if attendance not found
        if (!$attendance) {
            return response(['message' => 'Checkin first'], 400);
        }

        //check if already checkout
        if ($attendance->time_out && $attendance->date == date('Y-m-d')) {
            return response(['message' => 'You have already checkout today'], 400);
        }

        //save checkout
        $attendance->time_out = date('H:i:s');
        $attendance->latlong_out = $request->latitude . ',' . $request->longitude;
        $attendance->save();

        return response([
            'message' => 'Checkout success',
            'attendance' => $attendance
        ], 200);
    }

    //check is checked in
    public function isCheckedIn(Request $request)
    {
        $attendance = Attendance::where('user_id', $request->user()->id)
            ->where('date', date('Y-m-d'))
            ->first();
        return $attendance ? response(['checked_in' => true], 200) : response(['checked_in' => false], 400);
    }
}
