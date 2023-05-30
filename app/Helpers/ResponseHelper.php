<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/10/15
 * Time: 下午3:48
 */

namespace App\Helpers;

trait ResponseHelper
{
    public static function responseMaker($code, $message, $data = null)
    {
        $project_code = 001;

        /**
         * 1 查詢成功
         * 2 新增成功
         * 3 修改成功
         * 4 刪除成功
         * 5 系統未預期錯誤
         * 6 查詢失敗
         * 7 新增失敗
         * 8 修改失敗
         * 9 刪除失敗
         *
         */

        switch ($code) {
            case 1:
                $response = [
                    'http_status_code' => 400,
                    'status_message' => $message,
                ];
                break;
            case 100:
                $response = [
                    'http_status_code' => 200,
                    'status_message' => '查詢成功',
                ];
                break;
            case 101:
                $response = [
                    'http_status_code' => 200,
                    'status_message' => '查無資料',
                ];
                break;
            case 102:
                $response = [
                    'http_status_code' => 200,
                    'status_message' => '登入成功',
                ];
                break;
            case 103:
                $response = [
                    'http_status_code' => 200,
                    'status_message' => '登出成功',
                ];
                break;
            case 200:
                $response = [
                    'http_status_code' => 200,
                    'status_message' => '新增成功',
                ];
                break;
            case 201:
                $response = [
                    'http_status_code' => 200,
                    'status_message' => '註冊成功',
                ];
                break;
            case 202:
                $response = [
                    'http_status_code' => 200,
                    'status_message' => '發送訊息成功',
                ];
                break;
            case 203:
                $response = [
                    'http_status_code' => 200,
                    'status_message' => '開始隨機配對',
                ];
                break;
            case 300:
                $response = [
                    'http_status_code' => 200,
                    'status_message' => '更新成功',
                ];
                break;
            case 400:
                $response = [
                    'http_status_code' => 200,
                    'status_message' => '刪除成功',
                ];
                break;
            case 500:
                $response = [
                    'http_status_code' => 400,
                    'status_message' => '程式異常:'.$message,
                ];
                break;
            case 501:
                $response = [
                    'http_status_code' => 400,
                    'status_message' => '此方法不允許',
                ];
                break;
            case 502:
                $response = [
                    'http_status_code' => 403,
                    'status_message' => '無權限訪問',
                ];
                break;
            case 503:
                $response = [
                    'http_status_code' => 403,
                    'status_message' => '尚未登入',
                ];
                break;
            case 600:
                $response = [
                    'http_status_code' => 400,
                    'status_message' => '查詢失敗'
                ];
                break;
            case 601:
                $response = [
                    'http_status_code' => 400,
                    'status_message' => '登入失敗，帳號或密碼錯誤。'
                ];
                break;
            case 602:
                $response = [
                    'http_status_code' => 400,
                    'status_message' => '登出失敗'
                ];
                break;
            case 603:
                $response = [
                    'http_status_code' => 400,
                    'status_message' => '註冊失敗，帳號已有人使用'
                ];
                break;
            case 700:
                $response = [
                    'http_status_code' => 400,
                    'status_message' => '新增失敗'
                ];
                break;
            case 800:
                $response = [
                    'http_status_code' => 400,
                    'status_message' => '更新失敗'
                ];
                break;
            case 900:
                $response = [
                    'http_status_code' => 400,
                    'status_message' => '刪除失敗'
                ];
                break;
            default:
                $response = [
                    'http_status_code' => 400,
                    'code' => 'undefined_code',
                    'status_message' => '錯誤的Request',
                ];
        }
        $response['return_data'] = $data;
        $response['code'] = $project_code.$code;

        return $response;
    }
}
