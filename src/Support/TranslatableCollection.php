<?php

namespace Keasy9\Localize\Support;

use Illuminate\Support\Collection;

class TranslatableCollection extends Collection
{
    public function translate(?string $locale = null): void
    {
        foreach ($this->items as $item) {
            $item->translate($locale);
        }
    }
}
