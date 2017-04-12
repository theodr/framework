<?php

namespace Modules\Users\Traits;

use Modules\Users\Models\Role;


trait HasRoleTrait
{
    // Cache for associated Role instance.
    private $cachedRole;


    public function role()
    {
        return $this->belongsTo('Modules\Users\Models\Role', 'role_id');
    }

    public function hasRole($roles, $strict = false)
    {
        if (! isset($this->cachedRole)) {
            $this->cachedRole = $this->role()->getResults();
        }

        // Check if the User is a Root account.
        if (! is_null($this->cachedRole) && ($this->cachedRole->slug == 'root') && ! $strict) {
            return true;
        }

        if (! is_array($roles)) {
            return $this->checkUserRole($roles);
        }

        foreach ($roles as $role) {
            if ($this->checkUserRole($role)) {
                return true;
            }
        }

        return false;
    }

    private function checkUserRole($wantedRole)
    {
        if(isset($this->cachedRole) && ($this->cachedRole instanceof Role)) {
            return (strtolower($wantedRole) == strtolower($this->cachedRole->slug));
        }

        return false;
    }

}
