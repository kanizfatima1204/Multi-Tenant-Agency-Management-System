<?php
namespace App\Models;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
class Task extends Model { use BelongsToTenant; protected $fillable=['tenant_id','project_id','title','description','status','priority','due_date']; protected $casts=['due_date'=>'date']; public function project(){return $this->belongsTo(Project::class);} }
