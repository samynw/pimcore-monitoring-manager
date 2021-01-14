<?php

namespace Samynw\MonitoringManagerBundle\ValueObject\Config;

class Entry
{
    /** @var string */
    private $id;
    /** @var string */
    private $class;
    /** @var bool */
    private $enabled = true;

    /**
     * Entry constructor.
     * @param string $id
     * @param string $class
     */
    public function __construct(string $id, string $class)
    {
        $this->id = $id;
        $this->class = $class;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function enable(): void
    {
        $this->enabled = true;
    }

    public function disable(): void
    {
        $this->enabled = false;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'service' => $this->getClass(),
            'enabled' => $this->isEnabled(),
        ];
    }

    /**
     * @param string $id
     * @param array $config
     * @return Entry
     */
    public static function fromArray(string $id, array $config): Entry
    {
        $instance = new self($id, trim($config['service'], '\\'));
        if (\array_key_exists('enabled', $config)) {
            $instance->setEnabled(filter_var($config['enabled'], \FILTER_VALIDATE_BOOL));
        }

        return $instance;
    }
}
