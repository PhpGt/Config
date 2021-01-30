<?php
namespace Gt\Config\Test;

use Gt\Config\ConfigSection;
use BadMethodCallException;

class ConfigSectionTest extends TestCase {
	public function testGet() {
		$data = [
			"name" => "unit test",
			"example" => "123",
		];
		$sut = new ConfigSection("example", $data);
		self::assertNull($sut->get("nothing"));
		self::assertEquals($data["name"], $sut->get("name"));
		self::assertEquals($data["example"], $sut->get("example"));
	}

	public function testTypeSafeGetter() {
		$data = [
			"name" => "unit test",
			"number" => "123",
			"decimal" => "123.456",
			"birthday" => "1988-04-05",
		];
		$sut = new ConfigSection("example", $data);
		self::assertSame(123, $sut->getInt("number"));
		self::assertSame(123, $sut->getInt("decimal"));
		self::assertSame(123.456, $sut->getFloat("decimal"));
		self::assertNull($sut->getInt("absolutely-nothing"));
		self::assertNull($sut->getDateTime("nothing"));
		$dateTime = $sut->getDateTime("birthday");
		self::assertEquals("April 5th 1988", $dateTime->format("F jS Y"));
	}

	public function testArrayAccess() {
		$data = [
			"name" => "unit test",
			"number" => "123",
			"decimal" => "123.456",
			"birthday" => "1988-04-05",
		];
		$sut = new ConfigSection("example", $data);
		foreach($data as $key => $value) {
			self::assertEquals($value, $sut->get($key));
		}
	}

	public function testIterator() {
		$data = [
			"name" => "unit test",
			"number" => "123",
			"decimal" => "123.456",
			"birthday" => "1988-04-05",
		];
		$sut = new ConfigSection("example", $data);

		foreach($sut as $key => $value) {
			self::assertEquals($value, $data[$key]);
		}
	}

	public function testImmutableUnset() {
		$sut = new ConfigSection("example", []);
		self::expectException(BadMethodCallException::class);
		unset($sut["something"]);
	}

	public function testImmutableSet() {
		$sut = new ConfigSection("example", []);
		self::expectException(BadMethodCallException::class);
		$sut["something"] = "can not set";
	}
}