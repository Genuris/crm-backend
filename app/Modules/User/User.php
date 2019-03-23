<?php

namespace App\Modules\HelperUser;
use App\User;

class HelperUser{

    public static function getUserIds(User $user){
        /*if (!$user) {
            return false;
        }

        $role = $user->UserRole;

        if (!$role) {
            return false;
        }

        switch ($role->name) {
            case 'ROLE_ADMIN':
                return null;
            case 'ROLE_RIELTOR':
                return array($user->id);
            case 'ROLE_AGENCY_DIRECTOR':
                return Task::where('user_id', '=', $user->id)->get();
            case 'ROLE_OFFICE_DIRECTOR':
                return Task::where('user_id', '=', $user->id)->get();
        }*/

    }
}
