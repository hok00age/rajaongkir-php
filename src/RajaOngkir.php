<?php

namespace hok00age;

class RajaOngkir {

    private static $api_key;
    private static $base_url = "http://rajaongkir.com/api/";

    public function __construct($api_key) {
        RajaOngkir::$api_key = $api_key;
        \Unirest::defaultHeader("Content-Type", "application/x-www-form-urlencoded");
        \Unirest::defaultHeader("key", RajaOngkir::$api_key);
    }

    function getProvince($province_id = NULL, $additional_headers = array()) {
        $params = (is_null($province_id)) ? NULL : array('id' => $province_id);
        foreach ($additional_headers as $key => $value) {
            \Unirest::defaultHeader($key, $value);
        }
        return \Unirest::get(RajaOngkir::$base_url . "province", array(), $params);
    }

}
