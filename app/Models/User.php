<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
class User extends Authenticatable { use HasApiTokens,Notifiable; protected $fillable=['tenant_id','name','email','password','role']; protected $hidden=['password','remember_token']; protected function casts():array{return ['password'=>'hashed'];} public function tenant(){return $this->belongsTo(Tenant::class);} public function isAdmin():bool{return $this->role==='admin';} public function isClient():bool{return $this->role==='client';} public function isTeam():bool{return $this->role==='team';} }
