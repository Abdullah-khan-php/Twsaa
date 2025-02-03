<?php

namespace App\Repositories;

use App\Models\TiktokCampaign;
use App\Services\TiktokService;
use App\Interfaces\TiktokCampaignRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class TiktokCampaignRepository implements TiktokCampaignRepositoryInterface
{
    protected $tiktokCampaignModel, $userModel, $tiktokService;

    public function __construct(TiktokCampaign $tiktokCampaignModel, TiktokService $tiktokService)
    {
        $this->tiktokCampaignModel = $tiktokCampaignModel;
        $this->tiktokService = $tiktokService;
    }

    public function all()
    {
        return $this->tiktokCampaignModel->all();
    }


    public function find($id)
    {
        $tiktok = $this->tiktokCampaignModel->find($id);

        if (!$tiktok) {
            return response()->json([
                'message' => 'Role not found.'
            ], 404); // Return a 404 status code
        }
        // Return the user data with a 200 status code
        return response()->json($tiktok, 200);
    }

    public function store($data)
    {
        $tiktokCampaign = $this->save($data);

        return $tiktokCampaign;
    }

    public function update($data, $id)
    {
        $tiktok = $this->save($data, $id);

        return $tiktok;
    }

    public function destroy($id)
    {
        try {
            // Find the Role by ID
            $tiktok = $this->tiktokCampaignModel->findOrFail($id);

            // If no associated models, delete the Role
            $tiktok->delete();

            return response()->json([
                'message' => 'Role deleted successfully.'
            ], 200); // Success status code
        } catch (ModelNotFoundException $e) {
            // Handle case where Role is not found
            return response()->json([
                'message' => 'Role not found.'
            ], 404); // Not Found status code
        } catch (\Exception $e) {
            // Catch any other exceptions
            return response()->json([
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500); // Internal Server Error status code
        }
    }


    /**
     * Create or update a youtubeCampaignModel.
     *
     * @param array $data
     * @param int|null $id
     * @return App\Models\FacebookCampaign
     */

    public function save($request, $id = null)
    {
        $requestData = $request->all();
        try {
            // Check if we are updating or creating
            if ($id) {
                // Find the model by ID
                $tiktokyoutubeCampaignModel = $this->tiktokCampaignModel->findOrFail($id);
                
                if (!$tiktokyoutubeCampaignModel->update($requestData)) {
                    return redirect()->back()->with('error', 'Campaign could not be updated.');
                }

                return redirect()->route('facebook-campaigns.index')
                ->with('success', 'Campaign updated successfully.');
            } else {
                $fbService = $this->tiktokService->createCampaign($requestData);
                if (isset($fbService['id'])) {
                    $$requestData['campaign_id'] = $fbService['id'];

                    $tiktokCampaign = $this->tiktokCampaignModel->create($requestData);

                    if ($tiktokCampaign) {
                        return redirect()->route('facebook-campaigns.index')
                            ->with('success', 'Campaign created successfully.');
                    }
            }
            return redirect()->back()->with('error', 'Failed to create campaign.');
        }
        } catch (ModelNotFoundException $e) {
            // Handle case where youtubeCampaignModel is not found for update
            return redirect()->back()
                ->with('error', 'Campaign not found.');
        } catch (Exception $e) {
            // Handle other errors
            return redirect()->back()
                ->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
