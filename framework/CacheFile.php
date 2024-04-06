<?php
declare(strict_types=1);

class CacheFile implements Cache {

    private array $data = [];
    private string $path = ROOT_PATH.'tmp/cache';

    public function __construct()
    {
        if (file_exists($this->path))  {
            $this->data = json_decode(file_get_contents($this->path), true);
            foreach ($this->data as $key => $value) {
                if ($value['timeout'] < time()) {
                    unset($this->data[$key]);
                }
            }
        }
    }
    public function set(string $key, $value, $lifetime = 5) {
        $this->data[$key] = [
            'value' => $value,
            'timeout' => time() + $lifetime
        ];
    }

    public function get($key) {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key]['value'];
        }

        return null;
    }
    public function delete($key): void {
        if (array_key_exists($key, $this->data)) {
            unset($this->data[$key]);
        }
    }

    public function __destruct() {
        file_put_contents($this->path, json_encode($this->data));
    }

}