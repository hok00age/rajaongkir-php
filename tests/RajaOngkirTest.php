<?php

use hok00age\RajaOngkir;

class RajaOngkirTest extends PHPUnit_Framework_Testcase
{	

	/**
     *	@expectedException InvalidArgumentException
     */
	public function testThrowExceptionIfAccountTypeParameterNotValid()
	{
		new RajaOngkir("apikey", "super");
	}
}