<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\SecretariaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Secretaria extends Model
{
    /** @use HasFactory<SecretariaFactory> */
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    protected $fillable = ['nome', 'sigla'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }
}
