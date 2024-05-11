<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function scopeFilter($query, $filters) {
        if (!empty($filters['title'])) {
            $query->where('title', 'LIKE', '%'.request('title').'%');
        }
        if (!empty($filters['weight'])) {
            $query->where('weight', 'LIKE', '%'.request('weight').'%');
        }
        if (!empty($filters['category'])) {
            $query->where('category', 'LIKE', '%'.request('category').'%');
        }
        if (!empty($filters['description'])) {
            $query->where('description', 'LIKE', '%'.request('description').'%');
        }
        if (!empty($filters['sort'])) {
            if ($filters['dir'] == 'desc') {
                $strOrderFunction = 'orderByDesc';
            } else {
                $strOrderFunction = 'orderBy';
            }
            $query->$strOrderFunction(request('sort'));
        }
    }
}
