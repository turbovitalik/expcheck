<?php

namespace App\Http\Controllers;

use App\DomainName;

class DomainController extends Controller
{
    public function list()
    {
        $domains = DomainName::paginate(30);

        return view('domain.list', [
            'domains' => $domains,
        ]);
    }
}