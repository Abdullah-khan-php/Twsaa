<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use App\Models\SnapchatCampaign;
use App\Models\SnapchatAdAccount;

class SnapChatCampaignController extends Controller
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
        $user = Auth::user();

        if (!$user->snapchat_access_token) {
            return redirect()->back()->with('error', 'Snapchat Access Token Missing. Please re-authenticate.');
        }

        $response = Http::withToken($user->snapchat_access_token)
            ->get('https://adsapi.snapchat.com/v1/adaccounts');

        if ($response->failed()) {
            return redirect()->back()->with('error', 'Failed to fetch ad accounts. Please check your API credentials.');
        }

        foreach ($response->json()['adaccounts'] as $account) {
            SnapchatAdAccount::updateOrCreate(
                ['account_id' => $account['id']],
                [
                    'user_id' => $user->id,
                    'account_name' => $account['name'],
                    'access_token' => $user->snapchat_access_token,
                ]
            );
        }

        return redirect()->back()->with('success', 'Snapchat Ad Accounts Synced Successfully');
    }

    /**
     * Store a newly created campaign.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ad_account_id' => 'required|exists:snapchat_ad_accounts,id',
            'campaign_name' => 'required|string',
            'objective' => 'required|in:increase_sales,increase_store_visits,promote_application,increase_engagement',
        ]);

        $account = SnapchatAdAccount::find($request->ad_account_id);
        if (!$account) {
            return redirect()->back()->with('error', 'Invalid Ad Account');
        }

        $response = Http::withToken($account->access_token)->post("https://adsapi.snapchat.com/v1/campaigns", [
            'ad_account_id' => $account->account_id,
            'name' => $request->campaign_name,
            'objective' => $request->objective,
        ]);

        if ($response->failed()) {
            return redirect()->back()->with('error', 'Failed to create campaign: ' . $response->body());
        }

        SnapchatCampaign::create([
            'user_id' => Auth::id(),
            'ad_account_id' => $request->ad_account_id,
            'campaign_name' => $request->campaign_name,
            'objective' => $request->objective,
        ]);

        return redirect()->back()->with('success', 'Campaign Created Successfully');
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
        return Socialite::driver('snapchat')->redirect();
    }

    /**
     * Handle the callback from Snapchat OAuth.
     */
    public function callback()
    {
      
        try {
          
            $snapUser = Socialite::driver('snapchat')->user();
            dd($snapUser);
            // Store user details & access token
            $user = Auth::user();
            $user->snapchat_access_token = $snapUser->token;
            $user->save();

            return redirect()->route('snapchat-campaigns.index')->with('success', 'Snapchat connected successfully');
        } catch (\Exception $e) {
            return redirect()->route('snapchat-campaigns.index')->with('error', 'Failed to authenticate with Snapchat.');
        }
    }
}
