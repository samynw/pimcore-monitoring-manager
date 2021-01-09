<?php

namespace MonitoringManagerBundle\Config;

use Pimcore\Config;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Yaml;

class Dao
{
    /**
     * Return the location of where the config file is located (or should be located)
     * If not found, use the default config directory
     *
     * @param string $file
     * @return string
     */
    public static function getConfigFilePath(string $file): string
    {
        $path = Config::locateConfigFile($file);
        return !empty($path) ? $path : \PIMCORE_CONFIGURATION_DIRECTORY . '/' . $file;
    }

    /**
     * Read and parse the config file
     *
     * @param string $file
     * @return array
     * @throws ParseException
     */
    public function load(string $file): array
    {
        $config = self::getConfigFilePath($file);

        $parser = new Parser();
        $values = $parser->parseFile($config);

        return $values['monitoring-manager'] ?? [];
    }

    /**
     * Write config array to file
     *
     * @param string $file
     * @param $data
     * @throws IOException if the file cannot be written to
     */
    public function save(string $file, $data): void
    {
        $yaml = Yaml::dump(['monitoring-manager' => $data], 5);
        $fileSystem = new Filesystem();
        $fileSystem->dumpFile(self::getConfigFilePath($file), $yaml);
    }
}
