<?php

namespace App\Http\Controllers;

use App\Entities\DomainName;
use App\Manager\DomainNameManager;
use App\Repository\DomainRepository;
use App\Utils\DomainsFileParser;
use Doctrine\ORM\ORMException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

        $domain = new DomainName();
        $domain->setName($validatedData['name']);

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

    public function handleUpload(Request $request, DomainsFileParser $domainsFileParser, DomainNameManager $domainManager)
    {
        $file = $request->file('file');

        $filename = 'domains-list-' . time() . $file->getClientOriginalExtension();

        $path = $file->storeAs('uploads', $filename);

        $contents = Storage::get($path);

//        $contents = "puchd.ac,10/25/2018 12:40:00 PM,AUC
//napoleon.ac,10/25/2018 12:40:00 PM,AUC
//myapi.bet,10/25/2018 12:00:00 AM,AUC
//cem.bet,10/25/2018 12:01:00 AM,AUC
//manage.bet,10/25/2018 12:00:00 AM,AUC
//manage4.bet,10/25/2018 12:21:00 PM,AUC";

        $domainsParsed = $domainsFileParser->parse($contents);

        $domainsStored = $this->domainRepository->findAllIndexedByName();

        foreach ($domainsParsed as $domain) {
            // Domain name is not in DB. Create and insert to DB
            if (!array_key_exists($domain['name'], $domainsStored)) {
                $domainEntity = $domainManager->createFromArray($domain);
                $this->domainRepository->save($domainEntity);
                continue;
            }

            if ($domain['expiresAt'] != $domainsStored[$domain['name']]->getExpiresAt()) {
                var_dump('need update');
                $this->domainRepository->updateExpiresAt($domainsStored[$domain['name']]->getId(), $domain['expiresAt']);
            } else {
                var_dump('no need update');
            }
        }


//        foreach ($domainsArray as $domainData) {
//
//            /** @var DomainName $existedDomain */
//            $existedDomain = $this->domainRepository->findByName($domainData['name']);
//
//            if ($existedDomain && $existedDomain->getExpiresAt() !== $domainData['expiresAt']) {
//                $this->domainRepository->update($existedDomain, $domainData);
//            } else {
//                $domainEntity = $domainManager->createFromArray($domainData);
//                $this->domainRepository->save($domainEntity);
//            }
//        }

        $status = !isset($error) ? 'Success! Domain names from file were imported' : 'Failed to import domain names from file';

        return redirect()->action('DomainController@list')->with('status', $status);
    }
}