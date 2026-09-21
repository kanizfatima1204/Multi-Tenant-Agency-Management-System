<?php
namespace App\Models;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
class ProjectUpdate extends Model { use BelongsToTenant; protected $fillable=['tenant_id','project_id','user_id','body']; public function project(){return $this->belongsTo(Project::class);} public function user(){return $this->belongsTo(User::class);} }
