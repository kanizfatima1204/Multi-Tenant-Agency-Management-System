<?php
namespace App\Models;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Project extends Model { use BelongsToTenant; protected $fillable=['tenant_id','name','description','status','due_date']; protected $casts=['due_date'=>'date']; public function tasks():HasMany{return $this->hasMany(Task::class);} public function files():HasMany{return $this->hasMany(ProjectFile::class);} public function updates():HasMany{return $this->hasMany(ProjectUpdate::class);} }
