<?php

namespace App\Repositories;

use App\Models\FacebookCampaign;
use App\Services\FBService;
use App\Interfaces\FacebookCampaignRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class FacebookCampaignRepository implements FacebookCampaignRepositoryInterface
{
    protected $facebookCampaignModel, $userModel, $fbService;

    public function __construct(FacebookCampaign $facebookCampaignModel, FBService $fbService)
    {
        $this->facebookCampaignModel = $facebookCampaignModel;
        $this->fbService = $fbService;
    }

    public function all()
    {
        return $this->facebookCampaignModel->all();
    }


    public function find($id)
    {
        $facebook = $this->facebookCampaignModel->find($id);

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
        $facebookCampaign = $this->save($data);

        return $facebookCampaign;
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
            $facebook = $this->facebookCampaignModel->findOrFail($id);

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
     * Create or update a facebookCampaignModel.
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
                $facebookfacebookCampaignModel = $this->facebookCampaignModel->findOrFail($id);
                
                if (!$facebookfacebookCampaignModel->update($requestData)) {
                    return redirect()->back()->with('error', 'Campaign could not be updated.');
                }

                return redirect()->route('facebook-campaigns.index')
                ->with('success', 'Campaign updated successfully.');
            } else {
                $fbService = $this->fbService->createCampaign($requestData);
                if (isset($fbService['id'])) {
                    $$requestData['campaign_id'] = $fbService['id'];

                    $facebookCampaign = $this->facebookCampaignModel->create($requestData);

                    if ($facebookCampaign) {
                        return redirect()->route('facebook-campaigns.index')
                            ->with('success', 'Campaign created successfully.');
                    }
            }
            return redirect()->back()->with('error', 'Failed to create campaign.');
        }
        } catch (ModelNotFoundException $e) {
            // Handle case where facebookCampaignModel is not found for update
            return redirect()->back()
                ->with('error', 'Campaign not found.');
        } catch (Exception $e) {
            // Handle other errors
            return redirect()->back()
                ->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
