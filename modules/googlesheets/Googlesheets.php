<?php
declare(strict_types=1);

class Googlesheets extends StorageBase implements Storage {
    private string $url = '';

    private array $tableHeaders = [];

    public function __construct(string $url, Cache $cache) {
        $this->setUrl($url);
        $this->setCache($cache);
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function readData()
    {
        $data = $this->cache->get('googlesheetsData');

        if (!$data) {
            $csv = file_get_contents($this->getUrl());
            $csv = explode("\r\n", $csv);
            $data = array_map('str_getcsv', $csv);
            $this->cache->set('googlesheetsData', $data, 60);
        }

        $this->setTableHeaders(array_shift($data));


        return $data;
    }

    public function search(string $search): array
    {
        $parts = explode(" ", mb_strtolower($search));

        $result = [];
        $findItems = [];

        $data = $this->readData();
        foreach($data as $index => $row) {
            $text = mb_strtolower($row[0]);
            foreach($parts as $part) {
                if (!array_key_exists($index, $findItems)) {
                    $findItems[$index] = 0;
                }

                if (mb_strpos($text, $part) !== false) {
                    $findItems[$index]++;
                }
            }
        }

        $maxValue = max($findItems);

        if ($maxValue > 0) {
            $indexes = array_keys($findItems, $maxValue);

            foreach($indexes as $index) {
                $result[] = $data[$index];
            }
        }

        return $result;
    }

    public function getTableHeaders(): array
    {
        return $this->tableHeaders;
    }

    public function setTableHeaders(array $tableHeaders): void
    {
        $this->tableHeaders = $tableHeaders;
    }
}
