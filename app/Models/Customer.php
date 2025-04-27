<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;

	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array<string>
	 */
	protected $guarded = ['id'];
	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array<string>
	 */
	protected $dates = ['created_at', 'updated_at', 'subscription_end_date'];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'subscription_end_date' => 'datetime',
	];
}
