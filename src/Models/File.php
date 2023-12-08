<?php

namespace Keasy9\Localize\Models;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

use Illuminate\Support\Facades\File as FileFacade;

class File
{
    private Collection $strings;
    private static array $sizeUnits = ['b', 'kb', 'mb', 'gb', 'tb'];

    public readonly string $size;
    public readonly string $lastModify;
    public readonly string $filename;
    public readonly string $basename;

    private function loadStrings(): void
    {
        if (!isset($this->strings)) {
            $this->strings = collect(FileFacade::json(lang_path($this->basename)));
            $this->strings = $this->strings->filter(function(string $value, string $key) {
                return $key !== '';
            });
        }
    }

    public static function getAll(): Collection
    {
        $files = collect();
        foreach (glob(lang_path() . '/??.json') as $filepath) {
            $files->push(new self(pathinfo($filepath)));
        }

        return $files;
    }

    public function __construct($filename)
    {
        if (is_string($filename)) {
            $filename = pathinfo($filename);
        }
        $this->basename = $filename['basename'];
        $this->filename = $filename['filename'];
    }

    public function loadSize(): self
    {
        $size = FileFacade::size(lang_path($this->basename));
        $sizeUnit = 0;

        while ($size >= 1024 && isset(self::$sizeUnits[$sizeUnit+1])) {
            $size = round($size/1024, 2);
            $sizeUnit++;
        }
        $this->size = "{$size} " . self::$sizeUnits[$sizeUnit];

        return $this;
    }

    public function loadTimestamp(): File
    {
        $this->lastModify = Carbon::createFromTimestamp(FileFacade::lastModified(lang_path($this->basename)))
                                  ->format('Y.m.d, H:i');

        return $this;
    }

    public function getStrings($filter = []): Collection
    {
        $this->loadStrings();

        $strings = $this->strings;
        if (!empty($filter)) {
            if(is_array($filter)) {
                $strings = $strings->filter(function (string $key, string $value) use ($filter) {
                    return (empty($filter['key']) || mb_strpos($key, $filter['key']))
                        && (empty($filter['value']) || mb_strpos($value, $filter['value']));
                });
            } elseif (is_string($filter)) {
                $strings = $strings->filter(function (string $key, string $value) use ($filter) {
                    return mb_strpos($key, $filter) || mb_strpos($value, $filter);
                });
            }
        }

        return $strings;
    }

    public function removeString(string $key): File
    {
        $this->loadStrings();
        unset($this->strings[$key]);
        return $this;
    }

    public function saveString(string $key, string $value): File
    {
        $this->loadStrings();
        $this->strings[$key] = $value;
        return $this;
    }

    public function addKeys(array $keys): File
    {
        $this->strings = collect(array_fill_keys($keys, ''))->merge($this->getStrings());
        return $this;
    }

    public function save(): bool
    {
        return FileFacade::put(lang_path($this->basename), json_encode($this->strings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
    }

}