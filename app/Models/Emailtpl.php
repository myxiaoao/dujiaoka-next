<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Emailtpl extends Model
{
    use SoftDeletes;

    protected $table = 'emailtpls';

    protected $fillable = [
        'tpl_token',
        'tpl_name',
        'tpl_content',
    ];
}
