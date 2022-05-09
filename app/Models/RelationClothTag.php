<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RelationClothTag
 * 
 * @property int $id
 * @property int $tag_id
 * @property int $cloths_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class RelationClothTag extends Model
{
	protected $table = 'relation_cloth_tag';

	protected $casts = [
		'tag_id' => 'int',
		'cloths_id' => 'int'
	];

	protected $fillable = [
		'tag_id',
		'cloths_id'
	];
}
