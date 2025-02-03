<?php

namespace App\Repositories;

use App\Models\XCampaign;
use App\Services\XService;
use App\Interfaces\XCampaignRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class XCampaignRepository implements XCampaignRepositoryInterface
{
    protected $xCampaignModel, $userModel, $xService;

    public function __construct(XCampaign $xCampaignModel, XService $xService)
    {
        $this->xCampaignModel = $xCampaignModel;
        $this->xService = $xService;
    }

    public function all()
    {
        return $this->xCampaignModel->all();
    }


    public function find($id)
    {
        $x = $this->xCampaignModel->find($id);

        if (!$x) {
            return response()->json([
                'message' => 'Role not found.'
            ], 404); // Return a 404 status code
        }
        // Return the user data with a 200 status code
        return response()->json($x, 200);
    }

    public function store($data)
    {
        $xCampaign = $this->save($data);

        return $xCampaign;
    }

    public function update($data, $id)
    {
        $x = $this->save($data, $id);

        return $x;
    }

    public function destroy($id)
    {
        try {
            // Find the Role by ID
            $x = $this->xCampaignModel->findOrFail($id);

            // If no associated models, delete the Role
            $x->delete();

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
     * Create or update a xCampaignModel.
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
                $xxCampaignModel = $this->xCampaignModel->findOrFail($id);
                
                if (!$xxCampaignModel->update($requestData)) {
                    return redirect()->back()->with('error', 'Campaign could not be updated.');
                }

                return redirect()->route('facebook-campaigns.index')
                ->with('success', 'Campaign updated successfully.');
            } else {
                $fbService = $this->xService->createCampaign($requestData);
                if (isset($fbService['id'])) {
                    $$requestData['campaign_id'] = $fbService['id'];

                    $xCampaign = $this->xCampaignModel->create($requestData);

                    if ($xCampaign) {
                        return redirect()->route('facebook-campaigns.index')
                            ->with('success', 'Campaign created successfully.');
                    }
            }
            return redirect()->back()->with('error', 'Failed to create campaign.');
        }
        } catch (ModelNotFoundException $e) {
            // Handle case where xCampaignModel is not found for update
            return redirect()->back()
                ->with('error', 'Campaign not found.');
        } catch (Exception $e) {
            // Handle other errors
            return redirect()->back()
                ->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
