<?php

namespace hok00age;

class RajaOngkir {

    private static $api_key;
    private static $base_url = "http://rajaongkir.com/api/";

    public function __construct($api_key, $additional_headers = array()) {
        RajaOngkir::$api_key = $api_key;
        \Unirest::defaultHeader("Content-Type", "application/x-www-form-urlencoded");
        \Unirest::defaultHeader("key", RajaOngkir::$api_key);
        foreach ($additional_headers as $key => $value) {
            \Unirest::defaultHeader($key, $value);
        }
    }

    function getProvince($province_id = NULL) {
        $params = (is_null($province_id)) ? NULL : array('id' => $province_id);
        return \Unirest::get(RajaOngkir::$base_url . "province", array(), $params);
    }

    function getCity($city_id = NULL, $province_id = NULL) {
        $params = (is_null($city_id)) ? NULL : array('id' => $city_id);
        if (!is_null($province_id)) {
            $params['province'] = $province_id;
        }
        return \Unirest::get(RajaOngkir::$base_url . "city", array(), $params);
    }

    function getCost($origin, $destination, $weight, $courier = NULL) {
        $params = array(
            'origin' => $origin,
            'destination' => $destination,
            'weight' => $weight
        );
        if (!is_null($courier)) {
            $params['courier'] = $courier;
        }
        return \Unirest::post(RajaOngkir::$base_url . "cost", array(), $params);
    }

}
