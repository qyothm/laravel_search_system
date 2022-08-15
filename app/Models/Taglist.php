<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taglist extends Model
{
    use HasFactory;

    protected $table = 'taglist';

    protected $fillable = [
       'tag',
    ];

    public function showAllTag()
    {
        $result = DB::select('select * from taglist');
        return $result;
    }
}
