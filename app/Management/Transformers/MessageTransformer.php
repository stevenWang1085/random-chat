<?php

namespace App\Management\Transformers;

class MessageTransformer
{
    public function transformMessage($data)
    {
        $result = [];
        foreach ($data as $key => $value) {
            foreach ($value as $chat_message) {
                array_push($result, $chat_message);
            }
        }

        return $result;
    }
}
