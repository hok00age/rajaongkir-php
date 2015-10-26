<?php

/**
 * RajaOngkir PHP Client
 * 
 * Class PHP untuk mengkonsumsi API RajaOngkir
 * Berdasarkan dokumentasi RajaOngkir http://rajaongkir.com/dokumentasi
 * 
 * @author Damar Riyadi <damar@tahutek.net>
 */

namespace hok00age;

class RajaOngkir {

    private static $api_key;
    private static $base_url = "http://rajaongkir.com/api/";
    private static $valid_account_type = ['starter', 'basic', 'pro'];

    /**
     * Constructor
     * @param string $api_key API Key Anda sebagaimana yang tercantum di akun panel RajaOngkir
     * @param string $account_type Tipe Akun RajaOngkir
     * @param array $additional_headers Header tambahan seperti android-key, ios-key, dll
     */
    public function __construct($api_key, $account_type = "starter", $additional_headers = array()) {
        if(!in_array($account_type, self::$valid_account_type)) {
            throw new \InvalidArgumentException("Unknown account type. Please provide the correct one.");
        }

        if ($account_type == "pro")
            self::$base_url = "http://pro.rajaongkir.com/api/";
        else
            self::$base_url .= "{$account_type}/";

        self::$api_key = $api_key;
        \Unirest::defaultHeader("Content-Type", "application/x-www-form-urlencoded");
        \Unirest::defaultHeader("key", self::$api_key);
        foreach ($additional_headers as $key => $value) {
            \Unirest::defaultHeader($key, $value);
        }
    }

    /**
     * Fungsi untuk mendapatkan data propinsi di Indonesia
     * @param integer $province_id ID propinsi, jika NULL tampilkan semua propinsi
     * @return object Object yang berisi informasi response, terdiri dari: code, headers, body, raw_body.
     */
    function getProvince($province_id = NULL) {
        $params = (is_null($province_id)) ? NULL : array('id' => $province_id);
        return \Unirest::get(self::$base_url . "province", array(), $params);
    }

    /**
     * Fungsi untuk mendapatkan data kota di Indonesia
     * @param integer $province_id ID propinsi
     * @param integer $city_id ID kota, jika ID propinsi dan kota NULL maka tampilkan semua kota
     * @return object Object yang berisi informasi response, terdiri dari: code, headers, body, raw_body.
     */
    function getCity($province_id = NULL, $city_id = NULL) {
        $params = (is_null($province_id)) ? NULL : array('province' => $province_id);
        if (!is_null($city_id)) {
            $params['id'] = $city_id;
        }
        return \Unirest::get(self::$base_url . "city", array(), $params);
    }

    /**
     * Fungsi untuk mendapatkan data ongkos kirim
     * @param integer $origin ID kota/kecamatan asal
     * @param integer $destination ID kota/kecamatan tujuan
     * @param integer $weight Berat kiriman dalam gram
     * @param string $courier Kode kurir, jika NULL maka tampilkan semua kurir
     * @param string $originType tipe asal (city / subdistrict)
     * @param string $destinationType tipe tujuan (city / subdistrict)
     * @return object Object yang berisi informasi response, terdiri dari: code, headers, body, raw_body.
     */
    function getCost($origin, $destination, $weight, $courier = NULL, $originType = NULL, $destinationType = NULL) {
        $params = array(
            'origin' => $origin,
            'destination' => $destination,
            'weight' => $weight
        );
        if (!is_null($courier)) {
            $params['courier'] = $courier;
        }
        if (in_array($originType, array("city", "subdistrict"))){
            $params['originType'] = $originType;
        }
        if (in_array($destinationType, array("city", "subdistrict"))){
            $params['destinationType'] = $destinationType;
        }
        return \Unirest::post(self::$base_url . "cost", array(), http_build_query($params));
    }

    /**
    * Fungsi untuk mendapatkan data kecamatan berdasaran kota/kabupaten
    * @param integer $city_id ID kota
    * @param integer $subdistrict_id ID kecamatan
    * @return object Object yang berisi informasi response, terdiri dari: code, headers, body, raw_body.
    */
    function getSubdistrict($city_id, $subdistrict_id = NULL){
        $params = array('city' => $city_id);
        if (!is_null($subdistrict_id)) {
            $params['id'] = $subdistrict_id;
        }
        return \Unirest::get(self::$base_url . "subdistrict", array(), $params);
    }

    /**
    * Fungsi untuk mendapatkan data negara tujuan pengiriman internasional
    * @param integer $country_id ID negara, jika null tampil semua nama negara
    * @return object Object yang berisi informasi response, terdiri dari: code, headers, body, raw_body.
    */
    function getInternationalDestination($country_id = NULL){
        $params['id'] = $country_id;
        return \Unirest::get(self::$base_url . "internationalDestination", array(), $params);
    }

    /**
    * Fungsi untuk mendapatkan data kota asal yang mendukung pengiriman internasional
    * @param integer $city_id
    * @param integer $province_id
    * @return object Object yang berisi informasi response, terdiri dari: code, headers, body, raw_body.
    */
    function getInternationalOrigin($city_id  = NULL, $province_id = NULL){
        $params['id'] = $city_id;
        $params['province'] = $province_id;
        return \Unirest::get(self::$base_url . "internationalOrigin", array(), $params);
    }
	
    /**
     * Fungsi untuk mendapatkan data ongkos kirim internasional
     * @param integer $origin ID kota/kecamatan asal
     * @param integer $destination ID negara tujuan
     * @param integer $weight Berat kiriman dalam gram
     * @param string $courier Kode kurir
     * @return object Object yang berisi informasi response, terdiri dari: code, headers, body, raw_body.
     */
    function getInternationalCost($origin, $destination, $weight, $courier = NULL) {
        $params = array(
            'origin' => $origin,
            'destination' => $destination,
            'weight' => $weight
        );
        if (!is_null($courier)) {
            $params['courier'] = $courier;
        }
        return \Unirest::post(self::$base_url . "internationalCost", array(), http_build_query($params));
    }
	
    /**
     * Fungsi untuk mendapatkan data status pengiriman
     * @param integer $waybill no resi pengiriman 
     * @param string $courier Kode kurir
     * @return object Object yang berisi informasi response, terdiri dari: code, headers, body, raw_body.
     */
	function getWaybill($waybill, $courier){
		$params = array(
            'waybill' => $waybill,
            'courier' => $courier
        );
	    return \Unirest::post(self::$base_url . "waybill", array(), http_build_query($params));
	}
}
