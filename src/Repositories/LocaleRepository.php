<?php

namespace Webkul\Dropship\Repositories;

use Webkul\Core\Repositories\LocaleRepository as BaseLocaleRepository;

/**
 * Locale Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
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