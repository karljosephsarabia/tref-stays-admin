<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SMD\Common\ReservationSystem\Models\RsUsersLog;
use Yajra\Datatables\Facades\Datatables;

class UserLogController extends AppBaseController
{
    public function index()
    {
        return view('userlog');
    }

    public function datatable(Request $request)
    {
        $list = RsUsersLog::where('action','<>', 'login')
            ->where('action','<>', 'logout')
            ->where('log', 'not like','%Command Not Permitted on a dead channel%')
            ->orderBy('id','desc')->limit(500);

        return Datatables::of($list)
            ->make(true);
    }
}
