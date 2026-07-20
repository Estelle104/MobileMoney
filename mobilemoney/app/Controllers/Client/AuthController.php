<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\Client;

class AuthController extends BaseController
{

    public function login()
    {
        return view('Client/login');
    }

    //----------------------------------------------------

    public function checkLogin()
    {
        $numero = $this->request->getPost('numero');

        try
        {
            $clientModel = new Client();

            $client = $clientModel
                ->trouverOuCreerParNumero($numero);

            session()->set([

                'client_id'=>$client['id'],

                'numero'=>$client['numero']

            ]);

            return redirect()->to('/Client/dashboard');
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

        return redirect()->to('/Client/login');
    }

}