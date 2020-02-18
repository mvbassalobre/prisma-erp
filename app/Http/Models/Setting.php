<?php
namespace App\Http\Models;
use marcusvbda\vstack\Models\DefaultModel;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\Services\SlugService;

class Setting extends DefaultModel
{
    use Sluggable;
    protected $table = "settings";
    // public $cascadeDeletes = [];
    // public $restrictDeletes = [];
    protected $appends = ['f_created_at', 'last_update','examples'];

    public static function hasTenant() 
    {
        return false;
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = SlugService::createSlug($this, 'slug', $value);
    }

    public function getLastUpdateAttribute()
    {
        if (!$this->created_at) return;
        return $this->created_at->diffForHumans();
    }

    public function getFCreatedAtAttribute()
    {
        if (!$this->created_at) return;
        return @$this->created_at->format("d/m/Y - H:i:s");
    }

    public function getExamplesAttribute()
    {
        return '<code>$user->getSettings(["'.$this->slug.'"]);</code><br><code>$user->getSettings("'.$this->slug.'");';
    }

}