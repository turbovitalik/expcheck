<?php

namespace App\Http\Controllers;

use App\Entities\DomainName;
use App\Repository\DomainRepository;
use Doctrine\ORM\ORMException;
use Illuminate\Http\Request;
use LaravelDoctrine\ORM\Facades\EntityManager;

class DomainController extends Controller
{
    /**
     * @var DomainRepository
     */
    private $domainRepository;

    /**
     * DomainController constructor.
     * @param DomainRepository $domainRepository
     */
    public function __construct(DomainRepository $domainRepository)
    {
        $this->domainRepository = $domainRepository;
    }

    public function list()
    {
//        $domains = $this->domainRepository->findAll();
        $domains = $this->domainRepository->paginateAll(30);

        return view('domain.list', [
            'domains' => $domains,
        ]);
    }

    public function add()
    {
        return view('domain.add');
    }

    public function store(Request $request)
    {
        //todo: unique validation
        $validatedData = $request->validate([
            'name' => 'required|max:50',
        ]);

        $domain = new DomainName($validatedData['name']);

        EntityManager::persist($domain);

        try {
            EntityManager::flush();
        } catch (ORMException $e) {
            $error = $e->getMessage();
        }

        $status = !isset($error) ? 'New domain name was added' : 'Failed to add a new domain name';

        return redirect()->action('DomainController@list')->with('status', $status);
    }

    public function upload()
    {
        return view('domain.upload');
    }
}