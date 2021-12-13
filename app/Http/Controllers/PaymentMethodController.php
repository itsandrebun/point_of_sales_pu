<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('payment_method.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $payment_method = PaymentMethod::orderBy('method_id', 'desc')->get();
        
        return datatables()
            ->of($payment_method)
            ->addIndexColumn()
            ->addColumn('action', function($payment_method){
                return '
                <div class="btn-group">
                    <button onclick="editForm(`'. route('payment_method.update', $payment_method->method_id) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-edit"></i></button>
                    <button onclick="deleteData(`'. route('payment_method.destroy', $payment_method->method_id) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $payment_method = new PaymentMethod();
        $payment_method->method_name = $request->method_name;
        $payment_method->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $payment_method = PaymentMethod::find($id);
        return response()->json($payment_method);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $payment_method = PaymentMethod::find($id);
        $payment_method->method_name = $request->method_name;
        $payment_method->update();

        return response()->json('Data Stored Successfully!', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $payment_method = PaymentMethod::find($id)->update(['active' => -1]);

        return response(null, 204);
    }
}