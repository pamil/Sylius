<?php

declare(strict_types=1);

namespace Sylius\Behat\ApiChecker;

use Symfony\Component\HttpFoundation\Response;

final class ApiResponse implements \ArrayAccess, \Countable, \Iterator
{
    /** @var ApiClientInterface */
    private $client;

    /** @var array */
    private $data;

    public function __construct(ApiClientInterface $client, array $data)
    {
        $this->client = $client;
        $this->data = $data;
    }

    public static function fromJsonResponse(ApiClientInterface $client, Response $response): self
    {
        return new self($client, json_decode($response->getContent(), true, 512, \JSON_THROW_ON_ERROR));
    }

    public static function fromArray(ApiClientInterface $client, array $data): self
    {
        return new self($client, $data);
    }

    /**
     * @param string|int $offset
     */
    public function offsetExists($offset): bool
    {
        return \array_key_exists($offset, $this->data);
    }

    /**
     * @param string|int $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        $value = $this->data[$offset];

        if (is_string($value) && $this->isIri((string) $offset, $value)) {
            return $this->client->get($value);
        }

        if (is_array($value)) {
            return self::fromArray($this->client, $value);
        }

        return $value;
    }

    /**
     * @param string|int $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value): void
    {
        throw new \DomainException('CANT TOUCH THIS');
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset): void
    {
        throw new \DomainException('CANT TOUCH THIS');
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return $this->offsetGet(key($this->data));
    }

    public function next(): void
    {
        next($this->data);
    }

    /**
     * @return string|int
     */
    public function key()
    {
        return key($this->data);
    }

    public function valid(): bool
    {
        return $this->offsetExists(key($this->data));
    }

    public function rewind(): void
    {
        reset($this->data);
    }

    public function count(): int
    {
        return count($this->data);
    }

    private function isIri(string $key, string $string): bool
    {
        return strpos($key, '@') !== 0 && strpos($string, '/api/v2') === 0;
    }
}
