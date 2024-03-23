<?php

namespace Webkul\Dropship\Repositories;

use Webkul\Core\Repositories\LocaleRepository as BaseLocaleRepository;

class LocaleRepository extends BaseLocaleRepository
{
    /**
     * Return all locales
     *
     * @return mixed
     */
    public function getLocales()
    {
        $locales = [];

        foreach ($this->all() as $locale) {
            $locales[$locale->code] = $locale->name;
        }

        return $locales;
    }
}