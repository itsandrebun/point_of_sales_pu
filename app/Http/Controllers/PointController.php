<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Point;
use App\Models\Member;
use DB;

class PointController extends Controller
{
    public function get_point_per_member(){
        $all_member = Member::select(
                        'id_member',
                        'kode_member',
                        DB::raw('(SELECT (CASE WHEN SUM(CASE WHEN pn.status_id = 1 THEN pn.amount ELSE -pn.amount END) IS NOT NULL THEN SUM(CASE WHEN pn.status_id = 1 THEN pn.amount ELSE -pn.amount END) ELSE 0 END) FROM point AS pn WHERE pn.member_id = member.id_member) AS total_point')
                    )
                    ->where('active',1)->get()->toArray();

        for ($b=0; $b < count($all_member); $b++) { 
            $check_point = Point::where('member_id', $all_member[$b]['id_member'])->where(function($query){
                $query->where(DB::raw('DATE_FORMAT(get_point_at,"%Y-%m-%d")'),'=',date("Y-m-d", strtotime("-6 months")));
            })->orderBy('get_point_at','DESC')->first();

            if(isset($check_point) && $check_point['status_id'] == 1){
                $point_id = Point::insert(array(
                    "amount" => $check_point->amount,
                    "member_id" => $all_member[$b]['id_member'],
                    "status_id" => 2
                ));

                $all_member[$b]['total_point']-= $check_point->amount;
            }
        }

        return response()->json('Data berhasil dicari',200);
    }

    public function get_point_by_member($member_id){
        $member = Point::select(DB::raw('SUM((CASE WHEN status_id = 1 THEN amount ELSE -amount END)) AS total_point'))->where('member_id',$member_id)->first();

        $message = "Data tidak dapat dicari";
        $response = array();
        $status = 404;
        $total_point = 0;

        if(isset($member)){
            $message = "Data berhasil dicari";
            $status = 200;
            $total_point = (int)($member['total_point'] == null ? 0 : $member['total_point']);
        }

        $response['status'] = $status;
        $response['message'] = $message;
        $response['total_point'] = $total_point;

        return response()->json($response, $status);
    }
}
