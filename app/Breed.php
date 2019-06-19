<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Breed extends Model
{
    protected $fillable = ['id','name','description','temperament','life_span','alt_names','wikipedia_url','origin','weight_imperial'];
    public $timestamps = false;
}
