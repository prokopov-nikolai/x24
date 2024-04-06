<?php
declare(strict_types=1);

interface Cache {
    public function set(string $key, $value, int $lifetime);
    public function get(string $key);
    public function delete(string $key);
}