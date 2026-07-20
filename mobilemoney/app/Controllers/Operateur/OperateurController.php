<?php
namespace App\Controllers\Operateur;

use App\Controllers\BaseController;

class OperateurController extends BaseController
{
    public function index()
    {
        return view('operateur/dashboard');
    }

    public function login()
    {
        return view('operateur/login');
    }

    public function logout()
    {
        $session = session();
        $session->destroy();

        return redirect()->to('/operateur/login')
            ->with('success', 'Vous avez été déconnecté avec succès.');
    }
}