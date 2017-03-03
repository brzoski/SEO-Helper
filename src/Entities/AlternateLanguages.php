<?php namespace Arcanedev\SeoHelper\Entities;

use Arcanedev\SeoHelper\Contracts\Entities\AlternateLanguages as AlternateLanguagesContract;
use Arcanedev\Support\Traits\Configurable;
use Illuminate\Support\Collection;

class AlternateLanguages implements AlternateLanguagesContract
{
    use Configurable;

    protected $alternate;

    public function __construct(array $configs = [])
    {
        $this->setConfigs($configs);
        $this->alternate = new Collection();
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
        foreach ($this->alternate->all() as $alternate)
        {
            $html .= "<link rel=\"alternate\" hreflang=\"{$alternate['lang']}\" href=\"{$alternate['url']}\"/>";
            $html .= $this->alternate->last() == $alternate ? '' : PHP_EOL;
        }
        return $html;
    }

    public function __toString()
    {
        return $this->render();
    }
}