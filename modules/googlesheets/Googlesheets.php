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

            $data = $this->splitDataByFirstLetter($data);
            $this->cache->set('googlesheetsData', $data, 5);
        }

        $this->setTableHeaders(array_shift($data));


        return $data;
    }

    public function searchOne(string $search): string
    {
        $result = "<b>{$search}:</b>\r\n";
        $data = $this->readData();
        $firstLetter = mb_substr($search, 0, 1);

        $dataResult = '';

        if (array_key_exists($firstLetter, $data)) {
            foreach ($data[$firstLetter] as $item) {
                if ($item[0] === $search) {
                    $dataResult .= "{$item[1]} - {$item[2]}шт.\r\n";
                }
            }
        }

        if (empty($dataResult)) {
            $dataResult = 'Ничего не найдено';
        }

        $result .= $dataResult;

        return $result;
    }

    private function splitDataByFirstLetter(array $data): array {
        $result = [];

        foreach ($data as $item) {
            $firstLetter = mb_substr($item[0], 0, 1);
            $result[$firstLetter][] = $item;
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
