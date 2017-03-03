<?php namespace Arcanedev\SeoHelper\Contracts\Entities;

use Arcanedev\SeoHelper\Contracts\Renderable;

interface AlternateLanguages extends Renderable
{
    public function add($locale, $url);

    public function addMany(array $locales);

    public function reset();
}