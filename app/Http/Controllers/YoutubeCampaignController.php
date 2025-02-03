<?php

namespace App\Http\Controllers;

use App\Interfaces\YoutubeCampaignRepositoryInterface;
use App\Http\Requests\YoutubeCampaignRequest;

class YoutubeCampaignController extends Controller
{
    protected $youtubeCampaignRepositoryInterface;

    public function __construct(YoutubeCampaignRepositoryInterface $youtubeCampaignRepositoryInterface) {
        $this->youtubeCampaignRepositoryInterface = $youtubeCampaignRepositoryInterface;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $youtubeCampaigns = $this->youtubeCampaignRepositoryInterface->all(); 

        return view("youtube.campaigns.index", compact("youtubeCampaigns"));
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
    public function store(YoutubeCampaignRequest $request)
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
    public function update(YoutubeCampaignRequest $request, string $id)
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
