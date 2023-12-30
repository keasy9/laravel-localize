<?php

namespace Keasy9\Localize\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Keasy9\Localize\Models\Translation;
use Keasy9\Localize\Support\TranslatableCollection;

trait HasTranslations
{
    public function __get($key)
    {
        if (self::$preventAutoTranslation ?? false) {
            return $this->getAttribute($key);
        }

        return $this->getTranslatedAttribute($key);
    }

    public function translations(): MorphMany
    {
        return $this->morphMany(Translation::class, 'model');
    }

    public function getTranslatedAttribute(string $attribute, ?string $locale = null): mixed
    {
        $locale ??= app()->getLocale();

        if (
            ! ($locale === config('localize.default_locale', '') && ! config('localize.translate_default_locale', true))
            && in_array($attribute, array_keys($this->attributes))
        ) {
            if (! $this->relationLoaded('translations')) {
                $this->load(['translations' => function ($query) use ($locale) {
                    $query->where('locale', '=', $locale);
                }]);
            }

            foreach ($this->translations as $translation) {
                if ($translation->model_field === $attribute) {
                    return $translation->translation;
                }
            }
        }

        return $this->getAttribute($attribute);
    }

    public function translate(?string $locale = null): self
    {
        foreach (self::$translated as $translated) {
            $this->$translated = $this->getTranslatedAttribute($translated, $locale);
        }

        return $this;
    }

    public function newCollection(array $models = []): TranslatableCollection
    {
        return new TranslatableCollection($models);
    }
}
