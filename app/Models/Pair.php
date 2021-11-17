<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $left_asset_id
 * @property int $right_asset_id
 */
class Pair extends Model
{
    use HasFactory;

    public function leftAssetId()
    {
        return $this->hasOne(Asset::class, 'id', 'left_asset_id');
    }

    public function rightAssetId()
    {
        return $this->hasOne(Asset::class, 'id', 'right_asset_id');
    }
}
