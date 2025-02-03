<?php

namespace App\Repositories;

use App\Models\InstagramCampaign;
use App\Services\InstagramService;
use App\Interfaces\InstagramCampaignRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class InstagramCampaignRepository implements InstagramCampaignRepositoryInterface
{
    protected $instagramCampaignModel, $userModel, $instagramService;

    public function __construct(InstagramCampaign $instagramCampaignModel, InstagramService $instagramService)
    {
        $this->instagramCampaignModel = $instagramCampaignModel;
        $this->instagramService = $instagramService;
    }

    public function all()
    {
        return $this->instagramCampaignModel->all();
    }


    public function find($id)
    {
        $instagram = $this->instagramCampaignModel->find($id);

        if (!$instagram) {
            return response()->json([
                'message' => 'Role not found.'
            ], 404); // Return a 404 status code
        }
        // Return the user data with a 200 status code
        return response()->json($instagram, 200);
    }

    public function store($data)
    {
        $instagramCampaign = $this->save($data);

        return $instagramCampaign;
    }

    public function update($data, $id)
    {
        $instagram = $this->save($data, $id);

        return $instagram;
    }

    public function destroy($id)
    {
        try {
            // Find the Role by ID
            $instagram = $this->instagramCampaignModel->findOrFail($id);

            // If no associated models, delete the Role
            $instagram->delete();

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
     * Create or update a instagramCampaignModel.
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
                $instagraminstagramCampaignModel = $this->instagramCampaignModel->findOrFail($id);
                
                if (!$instagraminstagramCampaignModel->update($requestData)) {
                    return redirect()->back()->with('error', 'Campaign could not be updated.');
                }

                return redirect()->route('facebook-campaigns.index')
                ->with('success', 'Campaign updated successfully.');
            } else {
                $fbService = $this->instagramService->createCampaign($requestData);
                if (isset($fbService['id'])) {
                    $$requestData['campaign_id'] = $fbService['id'];

                    $instagramCampaign = $this->instagramCampaignModel->create($requestData);

                    if ($instagramCampaign) {
                        return redirect()->route('facebook-campaigns.index')
                            ->with('success', 'Campaign created successfully.');
                    }
            }
            return redirect()->back()->with('error', 'Failed to create campaign.');
        }
        } catch (ModelNotFoundException $e) {
            // Handle case where instagramCampaignModel is not found for update
            return redirect()->back()
                ->with('error', 'Campaign not found.');
        } catch (Exception $e) {
            // Handle other errors
            return redirect()->back()
                ->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
