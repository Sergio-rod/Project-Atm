<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function Login(Request $request)
    {
        $cardNum = $request->cardNum;
        $pin = $request->pin;
        try{
            $client = Client::where('cardNum',$cardNum)
                            ->where('pin',$pin)
                            ->first();
                return response([
                    'message' => "Successfully login",
                    'id' => $client->id,
                    'client' => $client
                ],200);

        }catch(Exception $e){
            return response([
                'message'=>'Invalid credentials'
            ],401);
        }
    }//end method



    public function WithDraw(Request $request){
        $amount = $request->amount;
        $cardNum = $request->cardNum;

        $client = Client::where('cardNum',$cardNum)->first();

        $balance = $client->balance;
        if($amount>0 && $amount<=$balance){

            $balance -= $amount;
            $client->balance = $balance;
            $client->save();
            return response([
                "Message" => "Retiro exitoso",
                "New Balance" => $balance
            ]);
        }
        return response([
            "Message" => "Error: el monto solicitado es mayor que el saldo disponible.",
            "Balance" => $balance,
            "Client" => $client
        ]);
    }//end method

    public function Deposit(Request $request){

        $cardNum = $request->cardNum;
        $amount = $request->amount;
        $client = Client::where("cardNum",$cardNum)->first();
        $balance = $client->balance;

        if($amount>0){
            $balance += $amount;
            $client->balance = $balance;
            $client->save();

            return response([
                "Message" => "Deposito exitoso",
                "New Balance" => $balance

            ]);
        }
        return response([
            "Message" => "Tarjeta no encontrada",
            "Balance" => $balance,
            "Client" => $client
        ]);
    }


    function Transfer(Request $request){
        $sender = $request->sender;
        $addressee = $request->addressee;
        $amount =  $request->amount;

        $clientSender = Client::where('cardNum',$sender)->first();
        $clientTarget = Client::where('cardNum',$addressee)->first();

        $clientSenderBalance=$clientSender->balance;
        $clientTargetBalance=$clientTarget->balance;

        if($amount<=$clientSenderBalance && $amount>0){
            $clientSenderBalance-=$amount;
            $clientTargetBalance+=$amount;

            $clientSender->balance = $clientSenderBalance;
            $clientTarget->balance = $clientTargetBalance;

            $clientSender->save();
            $clientTarget->save();

            return response([
                "Message" => "Transferencia exitosa",
                "New Balance" => $clientSenderBalance

            ]);

        }

    }


    function CheckBalance(Request $request){

        $cardNum = $request->cardNum;

        $client = Client::where('cardNum',$cardNum)->first();

        $balance = $client->balance;

        return response([
            'Message' => "Tu saldo es $balance"
        ]);

    }

}
