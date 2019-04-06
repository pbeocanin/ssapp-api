<?php


namespace App\Http\Controllers;


use App\Screenshot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Webpatser\Uuid\Uuid;

class ScreenshotsController extends Controller
{
    public function getSS(Request $request)
    {
        $id = $request['id'];
        $ss = Screenshot::where('ss_id', $id)->first();
        $now = Carbon::now();
        if ($ss) {
            if ($ss->expires !== null) {
                if ($now->gt(Carbon::parse($ss->expires)) === false) {
                    return response($ss, 200);
                }
                return response()->json(['error' => 'Screenshot expired', 'exptime' => Carbon::parse($ss->expires)->diffForHumans($now)], 400);
            } else {
                return response($ss, 200);
            }
        }
        return response()->json(['error' => 'not found'], 400);

    }

    public function saveSS(Request $request)
    {
        $file = $request->file('screenshot');
        $name = $file->getClientOriginalName();
        $sys_name = Uuid::generate(4)->string . '.png';
        $owner = $request->id;
        $ss = new Screenshot;
        $ss->user_id = $owner;
        $ss->ss_id = $request['ss_id'];
        $ss->filename = $sys_name;
        if ($ss->save()) {
            $file->move('screenshots', $sys_name);
        }
        return response()->json(['message' => 'okay'], 200);
    }

    public function setExp(Request $request)
    {
        $user = $request->id;
        $ssid = $request['id'];
        $expires = Carbon::now();
        if ($request['exptime'] == 'month') {
            $expires->add(1, 'month');
        }
        if ($request['exptime'] == 'week') {
            $expires->add(1, 'week');
        }
        if ($request['exptime'] == 'day') {
            $expires->add(1, 'day');
        }
        if ($request['exptime'] == 'hour') {
            $expires->add(1, 'hour');
        }
        $ss = Screenshot::where('ss_id', $ssid)->where('user_id', $user)->first();
        if ($ss) {
            $ss->expires = $expires;
            $ss->save();
            return response()->json(['message' => 'okay'], 200);
        }
        return response()->json(['error' => 'You can\'t do that'], 400);

    }
}