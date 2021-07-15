<?php

namespace App;

use App\Notifications\UserResetPassword;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use SoftDeletes, HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'name', 'phone', 'address', 'image', 'school', 'school_id', 'level_id', 'country_id', 'email', 'password', 'active', 'activation_token', 'provider', 'provider_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'activation_token'
    ];

    public function roles()
    {
        return $this->belongsToMany('App\Role');
    }

    public function school()
    {
        return $this->belongsTo('App\School');
    }

    public function country()
    {
        return $this->belongsTo('App\Country');
    }

    public function level()
    {
        return $this->belongsTo('App\Level');
    }

    public function badges()
    {
        return $this->hasMany('App\Badge');
    }

    public function parent()
    {
        return $this->belongsToMany('App\User', 'student_parent', 'student_id', 'parent_id');
    }

    public function students()
    {
        return $this->belongsToMany('App\User', 'student_parent', 'parent_id', 'student_id');
    }

    public function rewards()
    {
        return $this->belongsToMany('App\Reward', 'user_reward');
    }

    public function classrooms()
    {
        return $this->belongsToMany('App\Classroom');
    }

    public function homeworks()
    {
        return $this->hasMany('App\Homework');
    }

    public function achivement()
    {
        return $this->hasOne('App\Achivement');
    }

    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }

    public function feedbacks()
    {
        return $this->hasMany('App\Feedback');
    }

    public function subscriptions()
    {
        return $this->hasMany('App\Subscription');
    }
    public function usercalls()
    {
        return $this->hasOne('App\UserCall');
    }

    public function tokens()
    {
        return $this->hasMany('App\Token')
            ->whereNull('used')
            ->whereDate('validity_start', '<=', Carbon::now())
            ->whereDate('validity_end', '>=', Carbon::now());
    }

    public function validSubscriptions()
    {
        return $this->subscriptions()->whereHas('pack', function ($q) {
            $q->whereDate('validity_start', '<=', Carbon::now());
            $q->whereDate('validity_end', '>=', Carbon::now());
        })
//            ->orWhere(function ($query) {
//                return $query->where('is_free', 1)
//                    ->whereHas('pack', function ($q) {
//                        $q->whereDate('validity_start', '<=', Carbon::now());
//                        $q->whereDate('validity_end', '>=', Carbon::now());
//                    });
//            })
            ->whereHas('payment', function ($q) {
                $q->whereHas('paymentHistories', function ($q) {
                    $q->where('payment_status_id', 2);
                });
            });
    }

    public function getOrCreateParent($create = true)
    {
        $parent = $this->parent->first();
        if ($parent) {
            return $parent;
        }
        if ($create) {
            $parent = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'image' => $this->image,
                'provider_id' => $this->provider_id,
                'provider' => $this->provider,
            ]);
            $parent
                ->roles()
                ->attach(Role::where('name', 'PARENT')->first());
            $parent->students()->attach($this->id);
        }

        return $parent;
    }

    /**
     * @param string|array $roles
     * @return bool
     */

    public function authorizeRoles($roles)

    {

        if (is_array($roles)) {

            return $this->hasAnyRole($roles) ||
                abort(401, 'This action is unauthorized.');

        }

        return $this->hasRole($roles) ||
            abort(401, 'This action is unauthorized.');

    }

    /**
     * Check multiple roles
     * @param array $roles
     * @return bool
     */

    public function hasAnyRole($roles)

    {

        return null !== $this->roles()->whereIn('name', $roles)->first();

    }

    /**
     * Check one role
     * @param string $role
     * @return bool
     */

    public function hasRole($role)

    {

        return null !== $this->roles()->where('name', $role)->first();

    }


    public function avatar()
    {
        return $this->belongsTo('App\Avatar');
    }


    public function activeImage()
    {
        if ($this->image) {
            return asset('uploads/user/' . $this->image);
        } elseif ($this->provider) {
            return $this->image;
        } elseif ($this->avatar_id) {
            return asset('uploads/avatar/' . $this->avatar->name);
        } else {
            return asset('uploads/avatar/default.png');
        }
    }
    public function adress($id)
    {
        $country = Country::findOrFail($id);

        return $country->name;
    }
}