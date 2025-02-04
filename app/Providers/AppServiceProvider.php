<?php

namespace App\Providers;

use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\Snapchat\SnapchatExtendSocialite;
use Illuminate\Support\Facades\Event;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\FacebookCampaignRepositoryInterface;
use App\Repositories\FacebookCampaignRepository;
use App\Interfaces\GoogleCampaignRepositoryInterface;
use App\Repositories\GoogleCampaignRepository;
use App\Interfaces\TiktokCampaignRepositoryInterface;
use App\Repositories\TiktokCampaignRepository;
use App\Interfaces\XCampaignRepositoryInterface;
use App\Repositories\XCampaignRepository;
use App\Interfaces\InstagramCampaignRepositoryInterface;
use App\Repositories\InstagramCampaignRepository;
use App\Interfaces\YoutubeCampaignRepositoryInterface;
use App\Repositories\YoutubeCampaignRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(FacebookCampaignRepositoryInterface::class, FacebookCampaignRepository::class);
        $this->app->bind(GoogleCampaignRepositoryInterface::class, GoogleCampaignRepository::class);
        $this->app->bind(YoutubeCampaignRepositoryInterface::class, YoutubeCampaignRepository::class);
        $this->app->bind(XCampaignRepositoryInterface::class, XCampaignRepository::class);
        $this->app->bind(TiktokCampaignRepositoryInterface::class, TiktokCampaignRepository::class);
        $this->app->bind(InstagramCampaignRepositoryInterface::class, InstagramCampaignRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //parent::boot();
        Event::listen(SocialiteWasCalled::class, SnapchatExtendSocialite::class);
    }
}
