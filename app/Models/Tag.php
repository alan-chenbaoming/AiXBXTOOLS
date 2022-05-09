<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tag
 *
 * @property int $id
 * @property string $name
 * @property int $type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $u_id
 *
 * @package App\Models
 */
class Tag extends Model
{
	protected $table = 'tags';

	protected $casts = [
		'type' => 'int',
		'u_id' => 'int'
	];

	protected $fillable = [
		'name',
		'type',
		'u_id'
	];
}
