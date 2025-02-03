<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\FacebookCampaignRepositoryInterface;
use App\Http\Requests\FacebookCampaignRequest;
class FacebookCampaignController extends Controller
{
    protected $facebookCampaignRepositoryInterface;

    public function __construct(FacebookCampaignRepositoryInterface $facebookCampaignRepositoryInterface) {
        $this->facebookCampaignRepositoryInterface = $facebookCampaignRepositoryInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $facebookCampaigns = $this->facebookCampaignRepositoryInterface->all(); 

        return view("facebook.campaigns.index", compact("facebookCampaigns"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("facebook.campaigns.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FacebookCampaignRequest $request)
    {
        return $this->facebookCampaignRepositoryInterface->store($request); 
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
