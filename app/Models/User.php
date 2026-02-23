<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    /**
     * Relación Many-to-Many con Skills
     *
     * @return BelongsToMany
     */
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'skill_user')
            ->withPivot('level')
            ->withTimestamps();
    }

    /**
     * Obtener las skills agrupadas por nivel
     *
     * @return array
     */
    public function getSkillsByLevel(): array
    {
        return $this->skills()
            ->get()
            ->groupBy('pivot.level')
            ->map(fn($skills) => $skills->pluck('name')->toArray())
            ->toArray();
    }

    /**
     * Verificar si el usuario tiene una skill específica
     *
     * @param string $skillName
     * @return bool
     */
    public function hasSkill(string $skillName): bool
    {
        return $this->skills()->where('name', $skillName)->exists();
    }

    /**
     * Obtener el nivel del usuario en una skill específica
     *
     * @param string $skillName
     * @return string|null
     */
    public function getSkillLevel(string $skillName): ?string
    {
        $skill = $this->skills()->where('name', $skillName)->first();
        return $skill?->pivot->level;
    }
}
