<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RelationCloth
 * 
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $t_cloths_id
 * @property int $d_cloths_id
 *
 * @package App\Models
 */
class RelationCloth extends Model
{
	protected $table = 'relation_cloths';

	protected $casts = [
		't_cloths_id' => 'int',
		'd_cloths_id' => 'int'
	];

	protected $fillable = [
		't_cloths_id',
		'd_cloths_id'
	];
}
