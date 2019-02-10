<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $table = "roles";

    public function checkAction($path, $method, $permissions, $object)
    {

        $flag = false;
        if (!empty($this->actions) && $this->actions != '') {

            $actions = json_decode($this->actions, true);

            $matches = array();
            preg_match('/api\/\w+/', $path, $matches);

            if (isset($matches[0]) && isset($permissions[$method])) {
                foreach ($actions as $action) {

                    if (isset($action[$object->table])) {

                        foreach ($permissions[$method] as $key => $permission) {
                            if (in_array($matches[0], $permission) && in_array($key, $action[$object->table])) {
                                $flag = true;
                                break;
                            }
                        }
                    }

                    if ($flag) {
                        break;
                    }
                }
            }
        }

        return $flag;
    }
}
