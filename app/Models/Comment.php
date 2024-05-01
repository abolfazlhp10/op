<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\article;
use App\Models\User;

class Comment extends Model
{
    use HasFactory;
    protected $fillable=['user_id','comment','article_id','parent_id','status'];

    public function article(){
        return $this->belongsTo(article::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
