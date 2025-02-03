<?php

namespace App\Http\Controllers;

use App\Interfaces\XCampaignRepositoryInterface;
use App\Http\Requests\XCampaignRequest;

class XCampaignController extends Controller
{
    protected $xCampaignRepositoryInterface;

    public function __construct(XCampaignRepositoryInterface $xCampaignRepositoryInterface) {
        $this->xCampaignRepositoryInterface = $xCampaignRepositoryInterface;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $xCampaigns = $this->xCampaignRepositoryInterface->all(); 

        return view("x.campaigns.index", compact("xCampaigns"));
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
    public function store(XCampaignRequest $request)
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
    public function update(XCampaignRequest $request, string $id)
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
