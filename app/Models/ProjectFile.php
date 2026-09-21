<?php
namespace App\Models;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
class ProjectFile extends Model { use BelongsToTenant; protected $fillable=['tenant_id','project_id','name','path','mime_type','size']; public function project(){return $this->belongsTo(Project::class);} }
