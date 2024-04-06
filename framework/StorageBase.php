<?php
declare(strict_types=1);

class StorageBase {
    protected ?Cache $cache = null;

    public function setCache(Cache $cache): void
    {
        $this->cache = $cache;
    }

}