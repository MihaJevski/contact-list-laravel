<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Contact
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $created_at
 * @property string $updated_at
 *
 * @package App\Models
 */
class Contact extends Model
{
    use HasFactory;

    protected $table = 'contacts';

    protected $fillable = ['name', 'email'];

}
