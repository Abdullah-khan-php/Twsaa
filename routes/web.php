<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FacebookCampaignController;
use App\Http\Controllers\GoogleCampaignController;
use App\Http\Controllers\YoutubeCampaignController;
use App\Http\Controllers\TiktokCampaignController;
use App\Http\Controllers\InstagramCampaignController;
use App\Http\Controllers\XCampaignController;
use App\Http\Controllers\SnapchatCampaignController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('snapchat-campaigns/redirect', [SnapchatCampaignController::class, 'redirect'])->name('redirect');
    Route::get('snapchat-campaigns/callback', [SnapchatCampaignController::class, 'callback'])->name('callback');
    Route::get('/snapchat-campaigns/sync-ad-accounts', [SnapchatCampaignController::class, 'fetchAdAccounts'])->name('sync-ad-accounts');
    
    Route::resource('snapchat-campaigns', SnapChatCampaignController::class);
    Route::resource('facebook-campaigns', FacebookCampaignController::class);
    Route::resource('google-campaigns', GoogleCampaignController::class);
    Route::resource('youtube-campaigns', YoutubeCampaignController::class);
    Route::resource('tiktok-campaigns', TiktokCampaignController::class);
    Route::resource('x-campaigns', XCampaignController::class);
    Route::resource('instagram-campaigns', InstagramCampaignController::class);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
