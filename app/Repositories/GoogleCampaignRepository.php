<?php

namespace App\Repositories;

use App\Models\GoogleCampaign;
use App\Services\GoogleService;
use App\Interfaces\GoogleCampaignRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class GoogleCampaignRepository implements GoogleCampaignRepositoryInterface
{
    protected $googleCampaignModel, $userModel, $googleService;

    public function __construct(GoogleCampaign $googleCampaignModel, GoogleService $googleService)
    {
        $this->googleCampaignModel = $googleCampaignModel;
        $this->googleService = $googleService;
    }

    public function all()
    {
        return $this->googleCampaignModel->get();
    }


    public function find($id)
    {
        $facebook = $this->googleCampaignModel->find($id);

        if (!$facebook) {
            return response()->json([
                'message' => 'Role not found.'
            ], 404); // Return a 404 status code
        }
        // Return the user data with a 200 status code
        return response()->json($facebook, 200);
    }

    public function store($data)
    {
        $googleCampaign = $this->save($data);

        return $googleCampaign;
    }

    public function update($data, $id)
    {
        $facebook = $this->save($data, $id);

        return $facebook;
    }

    public function destroy($id)
    {
        try {
            // Find the Role by ID
            $facebook = $this->googleCampaignModel->findOrFail($id);

            // If no associated models, delete the Role
            $facebook->delete();

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
     * Create or update a googleCampaignModel.
     *
     * @param array $data
     * @param int|null $id
     * @return App\Models\googleCampaign
     */

    public function save($request, $id = null)
    {
        $requestData = $request->all();
        try {
            // Check if we are updating or creating
            if ($id) {
                // Find the model by ID
                $facebookgoogleCampaignModel = $this->googleCampaignModel->findOrFail($id);
                
                if (!$facebookgoogleCampaignModel->update($requestData)) {
                    return redirect()->back()->with('error', 'Campaign could not be updated.');
                }

                return redirect()->route('facebook-campaigns.index')
                ->with('success', 'Campaign updated successfully.');
            } else {
                $fbService = $this->googleService->createCampaign($requestData);
                if (isset($fbService['id'])) {
                    $$requestData['campaign_id'] = $fbService['id'];

                    $googleCampaign = $this->googleCampaignModel->create($requestData);

                    if ($googleCampaign) {
                        return redirect()->route('facebook-campaigns.index')
                            ->with('success', 'Campaign created successfully.');
                    }
            }
            return redirect()->back()->with('error', 'Failed to create campaign.');
        }
        } catch (ModelNotFoundException $e) {
            // Handle case where googleCampaignModel is not found for update
            return redirect()->back()
                ->with('error', 'Campaign not found.');
        } catch (Exception $e) {
            // Handle other errors
            return redirect()->back()
                ->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
