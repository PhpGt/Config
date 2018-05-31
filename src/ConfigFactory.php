<?php
namespace Gt\Config;

class ConfigFactory {
	const EXTENSION = "ini";
	const FILE_DEFAULT = "default";
	const FILE_OVERRIDE_ORDER = [
		"dev",
		"deploy",
		"production",
	];

	public static function createForProject(string $projectRoot):Config {
		$order = array_merge(
			[self::FILE_DEFAULT, ""],
			self::FILE_OVERRIDE_ORDER
		);

		$previousConfig = null;

		foreach($order as $file) {
			$fileName = "config";
			$fileName .= ".";

			if(!empty($file)) {
				$fileName .= $file;
				$fileName .= ".";
			}

			$fileName .= self::EXTENSION;
			$config = self::createFromPathName(
				implode(DIRECTORY_SEPARATOR,[
					$projectRoot,
					$fileName,
				])
			);

			if($previousConfig) {
				$config->merge($previousConfig);
			}

			$previousConfig = $config;
		}
	}

	public static function createFromPathName(string $pathName):Config {
		$parser = new IniParser($pathName);
		return $parser->parse();
	}
}