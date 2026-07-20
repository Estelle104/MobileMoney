<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\Client;

class CompteController extends BaseController
{

    public function solde()
    {

        $idClient = session()->get('client_id');


        $clientModel = new Client();


        $client = $clientModel->find($idClient);


        if(!$client)
        {
            return redirect()
                ->to('/client/login')
                ->with('error','Client introuvable');
        }


        return view('client/solde',[
            'client'=>$client
        ]);

    }

}