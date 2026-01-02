<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'branch_id',
        'seller_id',
        'name',
        'email',
        'phone',
        'avatar',
        'password',
        'role',
        'status',
        'privacy_accepted',
        'newsletter_opt_in',
        'duetto_id',
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
            'privacy_accepted' => 'boolean',
            'newsletter_opt_in' => 'boolean',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(self::class, 'seller_id');
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(self::class, 'seller_id');
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'trainer_id');
    }

    public function workoutPlans(): HasMany
    {
        return $this->hasMany(WorkoutPlan::class, 'trainer_id');
    }

    public function courseBookings(): HasMany
    {
        return $this->hasMany(CourseBooking::class);
    }

    public function courseWaitlist(): HasMany
    {
        return $this->hasMany(CourseWaitlist::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(Log::class);
    }

    public function userData(): HasOne
    {
        return $this->hasOne(UserData::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(UserMedia::class);
    }

    public function supportConversations(): HasMany
    {
        return $this->hasMany(SupportConversation::class);
    }

    public function supportMessages(): HasMany
    {
        return $this->hasMany(SupportMessage::class);
    }

    public function wallets(): HasMany
    {
        return $this->hasMany(Wallet::class);
    }
}
