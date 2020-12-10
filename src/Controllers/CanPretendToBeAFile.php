<?php

namespace ViewLauncher\Blade\Controllers;

trait CanPretendToBeAFile
{
    public function pretendResponseIsJs(string $file)
    {
        return $this->pretendResponseIsFile($file, 'application/javascript; charset=utf-8');
    }

    public function pretendResponseIsCss(string $file)
    {
        return $this->pretendResponseIsFile($file, 'text/css; charset=utf-8');
    }

    public function pretendResponseIsFile(string $file, string $contentType)
    {
        $expires = strtotime('+1 year');
        $lastModified = filemtime($file);
        $cacheControl = 'public, max-age=31536000';

        if ($this->matchesCache($lastModified)) {
            return response()->make('', 304, [
                'Expires' => $this->httpDate($expires),
                'Cache-Control' => $cacheControl,
            ]);
        }

        return response()->file($file, [
            'Content-Type' => $contentType,
            'Expires' => $this->httpDate($expires),
            'Cache-Control' => $cacheControl,
            'Last-Modified' => $this->httpDate($lastModified),
        ]);
    }

    protected function matchesCache($lastModified)
    {
        $ifModifiedSince = $_SERVER['HTTP_IF_MODIFIED_SINCE'] ?? '';

        return @strtotime($ifModifiedSince) === $lastModified;
    }

    protected function httpDate($timestamp)
    {
        return sprintf('%s GMT', gmdate('D, d M Y H:i:s', $timestamp));
    }
}
