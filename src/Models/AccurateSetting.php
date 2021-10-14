<?php

namespace Buatin\Accurate\Models;

use Illuminate\Database\Eloquent\Model;

class AccurateSetting extends Model
{
    protected $primaryKey = 'key';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'key',
        'value',
    ];

    public function getRouteKeyName(): string
    {
        return 'key';
    }
}
