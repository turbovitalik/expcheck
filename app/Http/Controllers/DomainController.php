<?php

namespace App\Http\Controllers;

use App\DomainName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DomainController extends Controller
{
    public function index(Request $request, DomainName $domainName)
    {
        $domainName = $domainName->newQuery();

        if (null !== $request->input('trust_flow')) {
            $domainName->where(['trust_flow' => $request->input('trust_flow')]);
        }

        if (null !== $request->input('citation_flow')) {
            $domainName->where(['citation_flow' => $request->input('citation_flow')]);
        }

        if ($request->input('tld')) {
            $tldArray = $request->input('tld');
            $domainName->where(function ($query) use ($tldArray) {
                for ($i = 0; $i < count($tldArray); $i++) {
                    $query->orwhere('tld', '=', $tldArray[$i]);
                }
            });
        }

        $domains = $domainName->paginate(10)->appends([
            'trust_flow' => $request->input('trust_flow'),
            'citation_flow' => $request->input('citation_flow'),
            'tld' => $request->input('tld'),
        ]);

        $tlds = DB::table('domains')
            ->select('tld')
            ->groupBy('tld')
            ->get();

        $undefined = DB::table('domains')
            ->where(['trust_flow' => null])
            ->orWhere(['citation_flow' => null])
            ->count();

        return view('domain.list', [
            'domains' => $domains,
            'tlds' => $tlds,
            'undefined' => $undefined,
        ]);
    }
}