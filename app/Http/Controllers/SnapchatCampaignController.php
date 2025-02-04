<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Snapchat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use App\Models\SnapchatCampaign;
use App\Models\SnapchatAdAccount;

class SnapchatCampaignController extends Controller
{
    protected $snapchatCampaign;

    public function __construct(SnapchatCampaign $snapchatCampaign) {
        $this->snapchatCampaign = $snapchatCampaign;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();
        $accounts = SnapchatAdAccount::where('user_id', $userId)->get();
        $campaigns = SnapchatCampaign::where('user_id', $userId)->get();

        return view('snapchat.campaigns.index', compact('accounts', 'campaigns'));
    }


    public function create() {
        $userId = Auth::id();
        $accounts = SnapchatAdAccount::where('user_id', $userId)->get();

        return view("snapchat.campaigns.create", compact('accounts'));
    }
    /**
     * Fetch Snapchat Ad Accounts
     */
    public function fetchAdAccounts()
    {
        $user = Snapchat::where('user_id', Auth::user()->id)->first();

        if (!$user->snapchat_token) {
            return redirect()->back()->with('error', 'Snapchat Access Token Missing. Please re-authenticate.');
        }

        $response = Http::withToken($user->snapchat_token)
            ->get('https://adsapi.snapchat.com/v1/adaccounts');

        if ($response->failed()) {

            return redirect()->back()->with('error', 'Failed to fetch ad accounts. Please check your API credentials.');
        }

        foreach ($response->json()['adaccounts'] as $account) {
            SnapchatAdAccount::updateOrCreate(
                ['account_id' => $account['id']],
                [
                    'user_id' => $user->user_id,
                    'account_name' => $account['name'],
                    'access_token' => $user->snapchat_token,
                ]
            );
        }

        return redirect()->back()->with('success', 'Snapchat Ad Accounts Synced Successfully');
    }
    public function handleSnapchatRedirect()
    {
        $code = request('code');  // Capture the authorization code
    
        $response = Http::asForm()->post('https://accounts.snapchat.com/login/oauth2/access_token', [
            'client_id' => env('SNAPCHAT_CLIENT_ID'),
            'client_secret' => env('SNAPCHAT_CLIENT_SECRET'),
            'code' => $code,
            'redirect_uri' => env('SNAPCHAT_REDIRECT_URI'),
            'grant_type' => 'authorization_code',
        ]);
    
        return $response->json();  // This contains the access_token needed for API calls
    }
    /**
     * Store a newly created campaign.
     */
    public function store(Request $request)
    {
        // $request->validate([
        //     'ad_account_id' => 'required|exists:snapchat_ad_accounts,id',
        //     'campaign_name' => 'required|string',
        //     'objective' => 'required|in:increase_sales,increase_store_visits,promote_application,increase_engagement',
        // ]);

        // $account = SnapchatAdAccount::find($request->ad_account_id);
        // if (!$account) {
        //     return redirect()->back()->with('error', 'Invalid Ad Account');
        // }
        $user = Snapchat::where('user_id', Auth::user()->id)->first();

        $url = 'https://accounts.snapchat.com/accounts/oauth2/auth?' . http_build_query([
            'client_id' => env('SNAPCHAT_CLIENT_ID'),
            'redirect_uri' => env('SNAPCHAT_REDIRECT_URI'),
            'response_type' => 'code',
            'scope' => 'snapchat-marketing-api',
        ]);
        
        return redirect()->to($url);
        if ($response->failed()) {
            return redirect()->back()->with('error', 'Failed to create campaign: ' . $response->body());
        }

        SnapchatCampaign::create([
            'user_id' => Auth::id(),
            'ad_account_id' => env("SNAPCHAT_APP_ID"),
            'campaign_name' => $request->campaign_name,
            'objective' => $request->objective,
        ]);

        //return redirect()->back()->with('success', 'Campaign Created Successfully');
    }

    /**
     * Remove the specified campaign from storage.
     */
    public function destroy($id)
    {
        $campaign = SnapchatCampaign::find($id);

        if (!$campaign) {
            return redirect()->back()->with('error', 'Campaign not found.');
        }

        $campaign->delete();
        return redirect()->back()->with('success', 'Campaign deleted successfully.');
    }

    /**
     * Redirect user to Snapchat for authentication.
     */
    public function redirect()
    {
        // $url = 'https://accounts.snapchat.com/accounts/oauth2/auth?' . http_build_query([
        //     'client_id' => env('SNAPCHAT_CLIENT_ID'),
        //     'redirect_uri' => env('SNAPCHAT_REDIRECT_URI'),  // Use only the base URL
        //     'response_type' => 'code',
        // ]);

        // return redirect()->to($url);

        // return redirect()->to($url);
        return Socialite::driver('snapchat')->redirect();
    }

    /**
     * Handle the callback from Snapchat OAuth.
     */
    public function callback()
    {
        $snapchat = Socialite::driver('snapchat')->user();
        dd($snapchat);   
        try {
          
            $snapchat = Socialite::driver('snapchat')->user();
            dd($snapchat);   
            // Store user info and token in database
            $snapchats = Snapchat::updateOrCreate([
                'snapchat_id' => $snapchat->id,
            ], [
                'user_id' => Auth::id(),
                'snapchat_token' => $snapchat->token,
            ]);

            return view('snapchat.campaigns.accounts.index', compact('snapchats'));
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->route('snapchat-campaigns.index')->with('error', 'Failed to authenticate with Snapchat.');
        }
    }
}
