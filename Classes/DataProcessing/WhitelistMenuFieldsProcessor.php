<?php
declare(strict_types=1);

namespace FourViewture\HeadlessMenu\DataProcessing;

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

final class WhitelistMenuFieldsProcessor implements DataProcessorInterface
{
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ): array {
        $as = (string)($processorConfiguration['as'] ?? 'menu');
        $fields = $this->csvToArray((string)($processorConfiguration['fields'] ?? 'title,link,active,current,children'));
        $dataFields = $this->csvToArray((string)($processorConfiguration['dataFields'] ?? ''));
        $childrenKey = (string)($processorConfiguration['childrenKey'] ?? 'children');

        $menu = $processedData[$as] ?? null;
        if (!is_array($menu)) {
            return $processedData;
        }

        $processedData[$as] = $this->whitelistRecursive($menu, $fields, $dataFields, $childrenKey);
        return $processedData;
    }

    private function whitelistRecursive(array $items, array $fields, array $dataFields, string $childrenKey): array
    {
        $out = [];

        foreach ($items as $item) {
            if (!is_array($item)) {
                continue;
            }

            $newItem = [];
            foreach ($fields as $key) {
                if ($key === $childrenKey) {
                    // handled below
                    continue;
                }
                if (array_key_exists($key, $item)) {
                    $newItem[$key] = $item[$key];
                }
            }

            // optionally slim down the raw record array
            if (!empty($dataFields) && isset($item['data']) && is_array($item['data'])) {
                $newItem['data'] = array_intersect_key($item['data'], array_flip($dataFields));
            }

            // recurse into children
            if (isset($item[$childrenKey]) && is_array($item[$childrenKey]) && $item[$childrenKey] !== []) {
                $newItem[$childrenKey] = $this->whitelistRecursive($item[$childrenKey], $fields, $dataFields, $childrenKey);
            }

            $out[] = $newItem;
        }

        return $out;
    }

    private function csvToArray(string $csv): array
    {
        $csv = trim($csv);
        if ($csv === '') {
            return [];
        }
        return array_values(array_filter(array_map('trim', explode(',', $csv)), static fn($v) => $v !== ''));
    }
}
