<?php

use hok00age\RajaOngkir;

class RajaOngkirTest extends PHPUnit_Framework_Testcase
{	
	
	const APIKEY = "";
	const ACCOUNTTYPE = "";
	
	public function setUp(){
		parent::setUp();
		
		if (self::APIKEY == "" OR ! in_array(self::ACCOUNTTYPE, array("starter","basic","pro") ))
			$this->markTestSkipped('Please fill APIKEY & ACCOUNT.');
			
		$this->rajaongkir = new RajaOngkir(self::APIKEY, self::ACCOUNTTYPE);
	}
	
	/**
     *	@expectedException InvalidArgumentException
     */
	public function testThrowExceptionIfAccountTypeParameterNotValid()
	{
		new RajaOngkir("apikey", "super");
	}
	
	public function testGetProvince()
	{
		$result = $this->rajaongkir->getProvince(); // without parameter
		$this->assertEquals(200, $result->code);
		$this->assertNotCount(0, $result->body->rajaongkir->results);
		
		$province_id = 1;
		
		$result = $this->rajaongkir->getProvince($province_id); // with parameter province
		$this->assertEquals(200, $result->code);
		$this->assertEquals($province_id, $result->body->rajaongkir->results->province_id);
	}
	
	public function testGetCity()
	{
		$result = $this->rajaongkir->getCity(); // without parameter
		$this->assertEquals(200, $result->code);
		$this->assertNotCount(0, $result->body->rajaongkir->results);
		
		$province_id = 1;
		
		$result = $this->rajaongkir->getCity($province_id); // with parameter province
		$this->assertEquals(200, $result->code);
		$this->assertNotCount(0, $result->body->rajaongkir->results);
		$this->assertEquals($province_id, $result->body->rajaongkir->results[0]->province_id);
		
		$city_id = 1;
		
		$result = $this->rajaongkir->getCity(NULL, $city_id); // with parameter city
		$this->assertEquals(200, $result->code);
		$this->assertEquals($city_id, $result->body->rajaongkir->results->city_id);
	}
	
	public function testGetSubdistrict()
	{
		if ( self::ACCOUNTTYPE == "pro" )
		{
			$city_id = 1;
			
			$result = $this->rajaongkir->getSubdistrict($city_id); // with parameter city, must be
			$this->assertEquals(200, $result->code);
			$this->assertNotCount(0, $result->body->rajaongkir->results);
			
			$subdistrict_id = 1;
			
			$result = $this->rajaongkir->getSubdistrict(NULL, $subdistrict_id); // with parameter subdistrict
			$this->assertEquals(200, $result->code);
			$this->assertEquals($subdistrict_id, $result->body->rajaongkir->results->subdistrict_id);
		
		}
	}
	
	public function testGetCost()
	{
		$origin = 1 ; // Aceh Barat
		$destination = 501; // Yogyakarta
		$weight = 100; // 1kg
		$courier = 'jne'; 
		$originType = 'city';
		$destinationType = 'city';
		
		$result = $this->rajaongkir->getCost($origin, $destination, $weight, $courier, $originType, $destinationType);
		
		$this->assertEquals(200, $result->code);
		$this->assertNotCount(0, $result->body->rajaongkir->results);
		$this->assertEquals($origin, $result->body->rajaongkir->origin_details->city_id);
		$this->assertEquals($destination, $result->body->rajaongkir->destination_details->city_id);
	}
	
	public function testGetInternationalDestination()
	{
		if ( self::ACCOUNTTYPE == "pro" )
		{
			$result = $this->rajaongkir->getInternationalDestination(); // without parameter
			$this->assertEquals(200, $result->code);
			$this->assertNotCount(0, $result->body->rajaongkir->results);
			
			$country_id = 1;
			
			$result = $this->rajaongkir->getInternationalDestination($country_id); // with parameter country
			$this->assertEquals(200, $result->code);
			$this->assertEquals($country_id, $result->body->rajaongkir->results->country_id);
		}
	}
	
	public function testGetInternationalOrigin()
	{
		if ( self::ACCOUNTTYPE == "pro" )
		{
			$result = $this->rajaongkir->getInternationalOrigin(); // without parameter
			$this->assertEquals(200, $result->code);
			$this->assertNotCount(0, $result->body->rajaongkir->results);
			
			$city_id = 444; // Surabaya
			
			$result = $this->rajaongkir->getInternationalOrigin($city_id); // with parameter city
			$this->assertEquals(200, $result->code);
			$this->assertEquals($city_id, $result->body->rajaongkir->results->city_id);
			
			$province_id = 11; // Jawa Timur
			
			$result = $this->rajaongkir->getInternationalOrigin(NULL, $province_id); // with parameter province
			$this->assertEquals(200, $result->code);
			$this->assertNotCount(0, $result->body->rajaongkir->results);
			$this->assertEquals($province_id, $result->body->rajaongkir->results[0]->province_id);
		}
	}
	
	public function testGetInternationalCost()
	{
	
		if ( self::ACCOUNTTYPE == "pro" )
		{
			$origin = 444; // Surabaya
			$destination = 108; // Malaysia
			$weight = 1000; // 1kg
			$courier = 'pos';
			
			$result = $this->rajaongkir->getInternationalCost($origin, $destination, $weight, $courier);
		
			$this->assertEquals(200, $result->code);
			$this->assertNotCount(0, $result->body->rajaongkir->results);
			$this->assertEquals($origin, $result->body->rajaongkir->origin_details->city_id);
			$this->assertEquals($destination, $result->body->rajaongkir->destination_details->country_id);
		
		}
	}
	
	public function testGetWaybill()
	{
		$waybill = 'SOCAG00183235715';
		$courier = 'jne';
		$result = $this->rajaongkir->getWaybill($waybill, $courier);
		$this->assertEquals(200, $result->code);
	}
	
}