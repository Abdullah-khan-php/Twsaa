<?php

namespace App\Repositories;

use App\Models\YoutubeCampaign;
use App\Services\YoutubeService;
use App\Interfaces\YoutubeCampaignRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class YoutubeCampaignRepository implements YoutubeCampaignRepositoryInterface
{
    protected $youtubeCampaignModel, $userModel, $youtubeService;

    public function __construct(YoutubeCampaign $youtubeCampaignModel, YoutubeService $youtubeService)
    {
        $this->youtubeCampaignModel = $youtubeCampaignModel;
        $this->youtubeService = $youtubeService;
    }

    public function all()
    {
        return $this->youtubeCampaignModel->all();
    }


    public function find($id)
    {
        $x = $this->youtubeCampaignModel->find($id);

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
            $x = $this->youtubeCampaignModel->findOrFail($id);

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
                $xyoutubeCampaignModel = $this->youtubeCampaignModel->findOrFail($id);
                
                if (!$xyoutubeCampaignModel->update($requestData)) {
                    return redirect()->back()->with('error', 'Campaign could not be updated.');
                }

                return redirect()->route('facebook-campaigns.index')
                ->with('success', 'Campaign updated successfully.');
            } else {
                $fbService = $this->youtubeService->createCampaign($requestData);
                if (isset($fbService['id'])) {
                    $$requestData['campaign_id'] = $fbService['id'];

                    $xCampaign = $this->youtubeCampaignModel->create($requestData);

                    if ($xCampaign) {
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
