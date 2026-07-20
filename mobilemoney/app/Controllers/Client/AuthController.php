<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\ClientModel;

class AuthController extends BaseController
{

    public function login()
    {
        return view('client/login');
    }

    //----------------------------------------------------

    public function checkLogin()
    {
        $numero = $this->request->getPost('numero');

        try
        {
            $clientModel = new ClientModel();

            $client = $clientModel
                ->trouverOuCreerParNumero($numero);

            session()->set([

                'client_id'=>$client['id'],

                'numero'=>$client['numero']

            ]);

            return redirect()->to('/client/dashboard');
        }
        catch(\Exception $e)
        {
            return redirect()
                ->back()
                ->withInput()
                ->with('error',$e->getMessage());
        }

    }

    //----------------------------------------------------

    public function logout()
    {
        session()->remove([
            'client_id',
            'numero'
        ]);

        return redirect()->to('/client/login');
    }

}