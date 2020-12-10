<?php

namespace ViewLauncher\Blade\Controllers;

class AssetController
{
    use CanPretendToBeAFile;

    public function css()
    {
        return $this->pretendResponseIsCss(__DIR__ . '/../../assets/view-launcher.css');
    }

    public function js()
    {
        return $this->pretendResponseIsJs(__DIR__ . '/../../assets/view-launcher.js');
    }
}
