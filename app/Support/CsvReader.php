<?php

namespace App\Support;

use Illuminate\Support\Collection;

/**
 * Class to read CSV files
 * CsvReader
 *
 */
class CsvReader
{

    public static function toCollection(string $path, string $delimiter = ','): Collection
    {
        return collect(self::toArrays($path, $delimiter));
    }

    /**
     * Read CSV file and return array of arrays
     * @param string $path
     * @param string $delimiter
     * @return array
     */
    public static function toArrays(string $path, string $delimiter = ','): array
    {
        $out = [];
        if (!is_file($path)) return $out;

        if (($h = fopen($path, 'r')) !== false) {
            $header = null;
            while (($row = fgetcsv($h, 0, $delimiter)) !== false) {
                if ($header === null) { $header = $row; continue; }
                $out[] = array_combine($header, $row);
            }
            fclose($h);
        }

        return $out;
    }

}
