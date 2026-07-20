<?php 

namespace App\Controllers;
use App\Models\Client;

class UserController extends BaseController
{
    public function checkLogin()
    {
        $email = $this->request->getPost('email');
        $mdp   = $this->request->getPost('mdp');

        $clientModel = new Client();
        $client = $clientModel->where('email', $email)->first();

        if ($client && password_verify($mdp, $client['mdp'])) {
            session()->set([
                'isLoggedIn' => true,
                'client_id'  => $client['id'],
                'email'      => $client['email'],
            ]);
            return redirect()->to('/client/caisse');
        }

        return redirect()->back()->with('error', 'Email ou mot de passe incorrect.');
    }
}