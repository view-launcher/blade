<?php

namespace ViewLauncher\Blade;

class BladeCompiler extends \Illuminate\View\Compilers\BladeCompiler
{
    /**
     * Compile the given Blade template contents.
     *
     * @param string $value
     * @return string
     */
    public function compileString($value)
    {
        $sourceMapper = new SourceMapper($value, $this->getPath());
        return parent::compileString($sourceMapper->compile());
    }
}
