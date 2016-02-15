<?php

namespace Gregoriohc\Beet\Filesystem;

use Illuminate\Filesystem\Filesystem;
use League\Flysystem\FileExistsException;
use League\Flysystem\FileNotFoundException;

class FileBuilder
{
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    private $filesystem;

    /**
     * The stubs base path.
     *
     * @var string
     */
    private $stubsBasePath;

    /**
     * The indentation string.
     *
     * @var string
     */
    private $indentation;

    /**
     * FileBuilder constructor.
     *
     * @param string|null $stubsPath
     * @param string $indentation
     */
    public function __construct($stubsPath = null, $indentation = '    ')
    {
        $this->filesystem = new Filesystem();
        $this->indentation = $indentation;

        if (is_null($stubsPath)) {
            $this->stubsBasePath = realpath(__DIR__ . '/../../stubs');
        } else {
            $this->stubsBasePath = realpath($stubsPath);
        }
    }

    /**
     * @param string $stub
     * @param array $variables
     * @param string|null $endFile
     * @param bool $overwrite
     * @return string
     * @throws FileExistsException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function create($stub, $variables = [], $endFile = null, $overwrite = false) {
        if (is_null($endFile)) {
            $endFile = $stub;
        }

        $pathStub = $this->stub($stub);
        $pathEndFile = base_path($endFile);

        if (!$overwrite && $this->filesystem->exists($pathEndFile)) {
            throw new FileExistsException($endFile);
        }

        $this->makeDirectory($pathEndFile);

        $stubContent = $this->filesystem->get($pathStub);
        foreach ($variables as $key => $value) {
            $stubContent = $this->replaceVariable($key, $value, $stubContent);
        }

        $this->filesystem->put($pathEndFile, $stubContent);

        return $endFile;
    }

    /**
     * @param string $file
     * @return string
     * @throws FileNotFoundException
     */
    public function delete($file)
    {
        $pathFile = base_path($file);

        if (!$this->filesystem->exists($pathFile)) {
            throw new FileNotFoundException($file);
        }

        $this->filesystem->delete($pathFile);

        return $file;
    }

    /**
     * Check if there is a file in a directory which name contains the given string
     *
     * @param string $name
     * @param string $directory
     * @return bool
     */
    public function existsSimilarInDirectory($name, $directory)
    {
        foreach ($this->filesystem->files($directory) as $file) {
            if (false !== stristr($file, $name)) return true;
        }

        return false;
    }

    /**
     * Build the directory for the end file if necessary.
     *
     * @param  string  $path
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (! $this->filesystem->isDirectory(dirname($path))) {
            $this->filesystem->makeDirectory(dirname($path), 0777, true, true);
        }
    }

    /**
     * Return stub path of given file
     *
     * @param string $path
     * @return string
     */
    private function stub($path = '') {
        return rtrim($this->stubsBasePath . '/' . trim($path, '/'), '/') . '.stub';
    }

    /**
     * @param string $line
     * @param bool|string|false $indents
     * @return string
     */
    private function indentLine($line, $indents = false)
    {
        $indentation = '';

        if (is_int($indents)) {
            $indentation = str_repeat($this->indentation, $indents);
        } elseif (is_string($indents)) {
            $indentation = $indents;
        }

        return $indentation . $line;
    }

    /**
     * @param string $text
     * @param string $key
     * @param string|string[] $value
     * @return string
     */
    private function replaceVariable($text, $key, $value)
    {
        $lines = preg_split ('/$\R?^/m', $text);

        $lines = array_map(function($line) use ($key, $value) {
            $indentation = $this->findIndentation($line);

            if (is_array($value)) {
                $value = array_map(function($value) use ($indentation) {
                    return $this->indentLine($value, $indentation);
                }, $value);
                $value = substr(implode(PHP_EOL, $value), strlen($indentation));
            }

            return str_replace($key, $value, $line);
        }, $lines);

        return implode(PHP_EOL, $lines);
    }

    /**
     * @param $text
     * @return string|bool
     */
    public function findIndentation($text)
    {
        if (preg_match('/^(\s+)/', $text, $matches)) {
            return $matches[0];
        }

        return false;
    }
}