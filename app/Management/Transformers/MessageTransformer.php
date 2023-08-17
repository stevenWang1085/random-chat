<?php

namespace App\Management\Transformers;

use Illuminate\Support\Collection;

class MessageTransformer
{
    /**
     * 轉換訊息
     *
     * @param $data
     * @param $room_type
     * @return array|mixed
     */
    public function transformMessage($data, $room_type)
    {
        $result = [];
        if ($room_type == 'random') {
            $result = $data->sortBy('created_at')->values()->all();
        } else {
            #personal
            foreach (collect($data)->sortKeys()->values() as $date => $value) {
                $value = $value->sortBy('created_at')->values()->all();
                $result = array_merge($result, $value);
            }
        }

        return $result;
    }
}
