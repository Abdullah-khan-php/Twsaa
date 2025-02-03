<?php

namespace App\Http\Controllers;

use App\Interfaces\InstagramCampaignRepositoryInterface;
use App\Http\Requests\InstagramCampaignRequest;

class InstagramCampaignController extends Controller
{
    protected $instagramCampaignRepositoryInterface;

    public function __construct(InstagramCampaignRepositoryInterface $instagramCampaignRepositoryInterface) {
        $this->instagramCampaignRepositoryInterface = $instagramCampaignRepositoryInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $instagramCampaigns = $this->instagramCampaignRepositoryInterface->all(); 

        return view("instagram.campaigns.index", compact("instagramCampaigns"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InstagramCampaignRequest $request)
    {
        //
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
    public function update(InstagramCampaignRequest $request, string $id)
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
