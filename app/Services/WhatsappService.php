<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Promise;

class WhatsappService {

    private static $url;
    private static $apiKey;
    private static $ownerPhoneNumber;
    private static $adminPhoneNumber;

    private static function init() {
        self::$url = config("whatsapp.api_url");
        self::$apiKey = config("whatsapp.api_key");
        self::$ownerPhoneNumber = config("whatsapp.owner_phone_number");
        self::$adminPhoneNumber = config("whatsapp.admin_phone_number");
    }

    private static function send($phoneNumber, $message) {
        self::init();

        $client = new Client();
        $request = $client->postAsync(self::$url, [
            'headers' => [
                'authorization' => self::$apiKey, 
            ],
            'form_params' => [
                'countryCode' => '62',
                'target' => '62' . $phoneNumber,
                'message' => $message,
            ]
            ]);
            
        Promise\Utils::any($request)->wait();
    }

    public static function sendMessage($phoneNumber, $message) {
        self::send($phoneNumber, $message);
    }

    public static function sendToOwner($message) {
        self::send(self::$ownerPhoneNumber, $message);
    }

    public static function sendToAdmin($message) {
        self::send(self::$adminPhoneNumber, $message);
    }

    public static function buildCustomerMessageOrderCreated($trxCode, $name, $paymentTotal) {
        return "Yth bpk/ ibuk\n*$name*\nKode : *$trxCode*\nTotal : *$paymentTotal*\nSilahkan melakukan pembayaran\nJika ada masalah silahkan hub: 082258537337\nHormat : Cafe fianda";
    }

    public static function buildCustomerMessageOrderPaid($trxCode, $name) {
        return "Yth bpk/ ibuk\n*$name*\nKode : *$trxCode*\nTelah dibayar\nJika ada masalah silahkan hub: 082258537337\nHormat : Cafe fianda";
    }

    public static function buildOwnerAndAdminMessageOrderCreated($trxCode, $name, $phone, $address, $totalPayment, $trxItems) {
        $items = $trxItems->map(function($item) {
            return "\n\t• $item[0] [$item[1]]";
        })->join(" ");
        return "Ada pesanan baru\nKode : *$trxCode*\nPemesan : *$name*\nNo hp : *$phone*\nAlamat : *$address*\nOrder : $items\n\nTotal : Rp. $totalPayment\nSilahkan cek dashboard untuk lebih lanjut";
    }
}