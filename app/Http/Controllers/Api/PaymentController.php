<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Distributor;

class PaymentController extends Controller {
    public function index(Request $r){
        $dist=Distributor::where('user_id',$r->user()->id)->first();
        return response()->json($dist?Payment::where('distributor_id',$dist->id)->orderBy('payment_date','desc')->paginate(20):[]);
    }

    public function store(Request $r){
        $r->validate(['amount_paid'=>'required|numeric','payment_date'=>'required|date']);
        $dist=Distributor::where('user_id',$r->user()->id)->firstOrFail();
        $p=Payment::create(['distributor_id'=>$dist->id,'delivery_id'=>$r->delivery_id,'amount_paid'=>$r->amount_paid,'payment_method'=>$r->payment_method??'cash','payment_date'=>$r->payment_date,'note'=>$r->note]);
        return response()->json($p,201);
    }
}
