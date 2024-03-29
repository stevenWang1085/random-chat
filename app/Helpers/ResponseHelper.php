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
    public static function responseMaker($code, $data)
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
            case 204:
                $response = [
                    'http_status_code' => 200,
                    'status_message' => '配對中',
                ];
                break;
            case 205:
                $response = [
                    'http_status_code' => 200,
                    'status_message' => '已配對',
                ];
                break;
            case 206:
                $response = [
                    'http_status_code' => 200,
                    'status_message' => '未配對',
                ];
                break;
            case 207:
                $response = [
                    'http_status_code' => 200,
                    'status_message' => '送出邀請成功',
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
            case 401:
                $response = [
                    'http_status_code' => 200,
                    'status_message' => '離開配對房間',
                ];
                break;
            case 402:
                $response = [
                    'http_status_code' => 200,
                    'status_message' => '取消配對成功',
                ];
                break;
            case 500:
                $response = [
                    'http_status_code' => 400,
                    'status_message' => $data,
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
            case 605:
                $response = [
                    'http_status_code' => 400,
                    'status_message' => '房間用戶錯誤'
                ];
                break;
            case 606:
                $response = [
                    'http_status_code' => 400,
                    'status_message' => '無此房間'
                ];
                break;
            case 700:
                $response = [
                    'http_status_code' => 400,
                    'status_message' => '新增失敗'
                ];
                break;
            case 701:
                $response = [
                    'http_status_code' => 400,
                    'status_message' => '已是好友關係。'
                ];
                break;
            case 702:
                $response = [
                    'http_status_code' => 400,
                    'status_message' => '對方已拒絕。'
                ];
                break;
            case 703:
                $response = [
                    'http_status_code' => 400,
                    'status_message' => '對方已送出邀請。'
                ];
                break;
            case 704:
                $response = [
                    'http_status_code' => 400,
                    'status_message' => '對方審核中。'
                ];
                break;
            case 705:
                $response = [
                    'http_status_code' => 400,
                    'status_message' => '已拒絕加好友。'
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
