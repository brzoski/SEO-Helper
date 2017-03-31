<?php namespace Arcanedev\SeoHelper\Entities;

use Arcanedev\SeoHelper\Contracts\Entities\AlternateLanguages as AlternateLanguagesContract;
use Arcanedev\Support\Traits\Configurable;
use Illuminate\Support\Collection;

class AlternateLanguages implements AlternateLanguagesContract
{
    use Configurable;

    protected $alternate;
    protected $defaultLanguage;

    public function __construct(array $configs = [])
    {
        $this->setConfigs($configs);
        $this->alternate = new Collection();
        $this->defaultLanguage = array_first(\LaravelLocalization::getSupportedLanguagesKeys());
    }

    public function add($locale, $url)
    {
        $this->alternate->push(['lang' => $locale, 'url' => $url]);
        return $this;
    }

    public function addMany(array $locales)
    {
        if(is_array($locales) && count($locales)>0)
        {
            foreach ($locales as $locale)
            {
                $this->alternate->push(['lang'=>$locale['lang'], 'url' => $locale['url']]);
            }
        }
        return $this;
    }

    public function reset()
    {
        $this->alternate = collect([]);
        return $this;
    }

    public function render()
    {
        if($this->alternate->isEmpty())
        {
            return '';
        }

        $html = '';
        $defaultUrl = \LaravelLocalization::getLocalizedURL();
        $html .= "<link rel=\"alternate\" href=\"{$defaultUrl}\" hreflang=\"x-default\" />".PHP_EOL;
        foreach ($this->alternate->all() as $alternate)
        {
            $html .= "<link rel=\"alternate\" href=\"{$alternate['url']}\" hreflang=\"{$alternate['lang']}\" />";
            $html .= $this->alternate->last() == $alternate ? '' : PHP_EOL;
        }
        return $html;
    }

    public function __toString()
    {
        return $this->render();
    }
}