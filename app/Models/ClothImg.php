<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ClothImg
 * 
 * @property int $id
 * @property int $cloths_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $img
 *
 * @package App\Models
 */
class ClothImg extends Model
{
	protected $table = 'cloth_imgs';

	protected $casts = [
		'cloths_id' => 'int'
	];

	protected $fillable = [
		'cloths_id',
		'img'
	];
}
