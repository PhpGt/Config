<?php
namespace Gt\Config;

class IniParser {
	protected $pathName;

	public function __construct(string $pathName) {
		$this->pathName = $pathName;
	}

	public function parse():Config {
		$data = parse_ini_file(
			$this->pathName,
			true,
			INI_SCANNER_TYPED
		);

		$configSectionList = [];
		foreach($data as $sectionName => $sectionData) {
			$configSectionList []= new ConfigSection(
				$sectionName,
				$sectionData
			);
		}

		return new Config(...$configSectionList);
	}
}