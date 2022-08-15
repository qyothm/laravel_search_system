<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documents extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'year',
        'upload_date',
        'file',
    ];

    public $timestamps = false;

    public function getAllDocuments($pageNum, $url)
    {
        $doc = Documents::paginate($pageNum, ['*'], $url);
        return $doc;
    }

    public function getDocumentsPaginate($category, $pageNum, $url)
    {
        $doc = Documents::where('category',$category)->paginate($pageNum, ['*'], $url);
        return $doc;
    }

    public function getDocumentsOrderBy($orderBy, $pageNum, $url)
    {
        $doc_year = Documents::orderBy($orderBy)->paginate($pageNum, ['*'], $url);
        return $doc_year;
    }
}
