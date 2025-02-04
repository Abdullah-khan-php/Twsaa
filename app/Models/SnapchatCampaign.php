<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SnapchatCampaign extends Model
{
    protected $fillable = ['user_id', 'ad_account_id','campaign_name','objective'];
}
