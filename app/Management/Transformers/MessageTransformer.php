<?php

namespace App\Management\Transformers;

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
            foreach ($data as $date => $value) {
                $result = array_merge($result, $value->toArray());
            }
        }

        return $result;
    }
}
