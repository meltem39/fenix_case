<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Payment;
use App\Models\User;
use App\Models\UserPackage;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ListController extends Controller
{

    public function index(){

        if(\request()->ajax()){
            $datas = [];
            if (auth()->user()->status == "admin"){
                $datas = Payment::latest()->get();
                foreach ($datas as $data){
                    $package = UserPackage::where("payment_id",$data["id"])->first();
                    $data["user_id"] = User::whereId($data["user_id"])->first()->device_name;
                    $data["productId"] = Package::whereId($data["productId"])->first()->name;
                    $data["quota"] =  $data->status=="1" ? $package->quota : "-";
                }
            }

            return DataTables::of($datas)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('dashboard');
    }
}
