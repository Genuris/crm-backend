<?php

namespace App\Modules\DataChangeLog;
use App\Models\DataChangeLogs;

class DataChangeLog{

    public static function setLogs($object, $item_id, $user_id, $data){
        $data_change_log = new DataChangeLogs();
        $data_change_log->object = $object;
        $data_change_log->item_id = $item_id;
        $data_change_log->user_id = $user_id;
        $data_change_log->data = ((!empty($data) and is_array($data)) ? json_encode($data) : json_encode([]));
        $data_change_log->save();
    }
}
