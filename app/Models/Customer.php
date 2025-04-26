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
}
