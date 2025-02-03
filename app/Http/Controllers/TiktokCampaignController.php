<?php

namespace App\Http\Controllers;

use App\Interfaces\TiktokCampaignRepositoryInterface;
use App\Http\Requests\TiktokCampaignRequest;

class TiktokCampaignController extends Controller
{
    protected $tiktokCampaignRepositoryInterface;

    public function __construct(TiktokCampaignRepositoryInterface $tiktokCampaignRepositoryInterface) {
        $this->tiktokCampaignRepositoryInterface = $tiktokCampaignRepositoryInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tiktokCampaigns = $this->tiktokCampaignRepositoryInterface->all(); 

        return view("tiktok.campaigns.index", compact("tiktokCampaigns"));
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
    public function store(TiktokCampaignRequest $request)
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
    public function update(TiktokCampaignRequest $request, string $id)
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
