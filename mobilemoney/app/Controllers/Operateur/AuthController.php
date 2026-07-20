<?php

namespace App\Controllers\Operateur;

use App\Controllers\BaseController;

class AuthController extends BaseController{
    
    public function login()
    {
        if (session()->get('operateur_id')) {
            return redirect()->to(site_url('operateur/dashboard'));
        }

        return view('operateur/login');
    }

    public function checkLogin()
    {
        $email = $this->request->getPost('email');
        $mdp = $this->request->getPost('mdp');

        $operateurModel = new \App\Models\Operateur();
        $operateur = $operateurModel->verifierIdentifiants($email, $mdp);

        if ($operateur) {
            $session = session();
            $session->set('operateur_id', $operateur['id']);
            $session->set('operateur_nom', $operateur['nom']);
            $session->set('operateur_email', $operateur['email']);

            $redirectUrl = $session->get('redirect_url') ?: site_url('operateur/dashboard');
            $session->remove('redirect_url');

            return redirect()->to($redirectUrl)
                ->with('success', 'Connexion réussie.');
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email ou mot de passe incorrect.');
        }
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to(site_url('operateur/login'))
            ->with('success', 'Vous avez été déconnecté avec succès.');
    }
}
