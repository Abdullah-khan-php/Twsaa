<?php
namespace App\Services;

use Facebook\Facebook;
use Illuminate\Support\Facades\Http;

class TiktokService
{
    protected $fb;
    protected $accessToken;
    protected $adAccountId;

    public function __construct()
    {
        $this->fb = new Facebook([
            'app_id' => env('FACEBOOK_APP_ID'),
            'app_secret' => env('FACEBOOK_APP_SECRET'),
            'default_graph_version' => 'v22.0',
        ]);

        $this->accessToken = env('FACEBOOK_ACCESS_TOKEN');
        $this->adAccountId = env('FACEBOOK_ACCOUNT_ID');
    }

    // Create Ad Campaign
    public function createCampaign($request)
    {
        try {
            $accessToken = $this->getFacebookAccessToken();
            // Define the API endpoint for creating a campaign
            $url = "https://graph.facebook.com/v13.0/act_{$this->adAccountId}/campaigns";

            // Send a POST request using Laravel's HTTP client
            $response = Http::post($url, [
                'name' => 'My New Campaign 1',        // The name of the campaign
                'objective' => 'REACH',      // Can be 'CONVERSIONS', 'LINK_CLICKS', etc.
                'status' => 'PAUSED',
                'special_ad_categories' => ['HOUSING'],               // Use 'ACTIVE' to launch the campaign immediately
                'access_token' => $this->accessToken,
            ]);

            if ($response->successful()) {
                // Decode and return the campaign data
                $campaign = $response->json();
                return response()->json($campaign);
            } else {
                dd($response->body());
                // Handle failure (return error message)
                return response()->json(['error' => 'Failed to create campaign', 'message' => $response->body()], 500);
            }
            // $response = $this->fb->post('/act_' . $this->adAccountId . '/campaigns', [
            //     'name' => 'My New Campaign',
            //     'objective' => 'CONVERSIONS', // Can be 'CONVERSIONS', 'LINK_CLICKS', etc.
            //     'status' => 'PAUSED', // Use 'ACTIVE' to launch immediately
            // ], $accessToken);

            // $campaign = $response->getDecodedBody();
            // dd($campaign);
           // return response()->json($campaign);
        } catch (\Exception $e) {
            dd($e->getTraceAsString()); // Log full error trace
            dd($e->getTraceAsString()); // Log full error trace
            return ['error' => $e->getMessage()];
        }
    }

    // Get Active Campaigns
    public function getCampaigns()
    {
        try {
            $response = $this->fb->get(
                "/act_{$this->adAccountId}/campaigns?fields=id,name,status",
                $this->accessToken
            );

            return $response->getDecodedBody();
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function getFacebookAccessToken()
{
   
    $response = Http::get('https://graph.facebook.com/oauth/access_token', [
        'client_id' => env('FACEBOOK_APP_ID'),
        'client_secret' => env('FACEBOOK_APP_SECRET'),
        'grant_type' => 'client_credentials',
    ]);
 
    $accessToken = $response->json()['access_token'];

    return $accessToken;
}
}
