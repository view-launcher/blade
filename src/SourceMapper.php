<?php

namespace ViewLauncher\Blade;

class SourceMapper
{
    /**
     * @var string
     */
    protected $content;

    /**
     * @var string
     */
    protected $filePath;

    /**
     * SourceMapper constructor.
     * @param string $content
     * @param string $filePath
     */
    public function __construct(string $content, string $filePath)
    {
        $this->content = $content;
        $this->filePath = $this->getLocalFilePath($filePath);
    }

    protected function getLocalFilePath(string $filePath): string
    {
        $viewDir = config('view.paths.0');
        $localViewDir = rtrim(config('view-launcher.local_view_dir'), '/\\');

        return str_replace($viewDir, $localViewDir, $filePath);
    }

    public function compile(): string
    {
        return $this->addDataAttribute();
    }

    protected function addDataAttribute(): string
    {
        $content = preg_replace_callback(
            '@.*(?<!\?|--)>\n@',
            function ($match) {
                $line = rtrim($match[0][0], "\n");
                $offset = $match[0][1];

                if (!$this->isStartTagLine($line)) {
                    return $line . "\n";
                }

                $location = htmlspecialchars(
                    json_encode([
                        'view' => $this->filePath,
                        'line' => $this->getLineNumber($offset),
                    ])
                );
                $attributes = <<<EOT
 data-tag-location="$location"
EOT;

                return substr_replace($line, $attributes, $this->isEmptyTag($line) ? -2 : -1, 0) . "\n";
            },
            $this->content,
            -1,
            $count,
            PREG_OFFSET_CAPTURE
        );

        return $content;
    }

    protected function isStartTagLine(string $line): bool
    {
        return !$this->isEndTag($line) && !$this->isSlotTag($line) && !$this->isDocType($line);
    }

    protected function isDocType(string $line): bool
    {
        return (bool)preg_match('@<!DOCTYPE@', strtoupper($line));
    }

    protected function isEndTag(string $line): bool
    {
        return (bool)preg_match('@</[^>]+>$@', $line);
    }

    protected function isSlotTag(string $line): bool
    {
        return (bool)preg_match('@<x-slot @', $line);
    }

    protected function isEmptyTag(string $line): bool
    {
        return (bool)preg_match('@/>$@', $line);
    }

    protected function getLineNumber(int $offset): int
    {
        $before = substr($this->content, 0, $offset);
        return strlen($before) - strlen(str_replace("\n", '', $before)) + 1;
    }
}
