<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Cloth
 * 
 * @property int $id
 * @property int $type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $u_id
 *
 * @package App\Models
 */
class Cloth extends Model
{
	protected $table = 'cloths';

	protected $casts = [
		'type' => 'int',
		'u_id' => 'int'
	];

	protected $fillable = [
		'type',
		'u_id'
	];
}
