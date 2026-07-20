<?php
namespace App\Controllers\Operateur;

use App\Controllers\BaseController;
use App\Models\Operateur;
use App\Models\Prefixe;


class PrefixeController extends BaseController
{
    public function index()
    {
        return $this->list();
    }

    public function list()
    {
        $prefixeModel = new Prefixe();
        $prefixes = $prefixeModel->findAllByOperateur(session()->get('operateur_id'));

        return view('operateur/configuration/list', ['prefixes' => $prefixes]);
    }

    public function creer()
    {
        return view('operateur/configuration/creer');
    }


}