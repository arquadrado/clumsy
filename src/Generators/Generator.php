<?php

namespace Clumsy\CMS\Generators;

use Illuminate\Console\AppNamespaceDetectorTrait;
use Illuminate\Support\Facades\File as Filesystem;
use Clumsy\CMS\Generators\Filesystem\File;

abstract class Generator
{
    use AppNamespaceDetectorTrait;

    protected $psr4 = true;

    protected $templateData;

    public function getSlug()
    {
        return str_slug(snake_case(class_basename($this)));
    }

    public function getConfigNamespace($slug = null)
    {
        if (is_null($slug)) {
            $slug = $this->getSlug();
        }

        return config("clumsy.cms.{$slug}-namespace");
    }

    public function getNamespace($slug = null)
    {
        // Trim initial slashes -- they're unnecessary
        $namespace = ltrim($this->getConfigNamespace($slug), '\\');

        return $namespace;
    }

    public function origin()
    {
        return __DIR__.DIRECTORY_SEPARATOR.'stubs'.DIRECTORY_SEPARATOR.$this->getSlug().'.stub';
    }

    public function normalizeDirectory($path)
    {
        return preg_replace('/(\\+|\/+)/', DIRECTORY_SEPARATOR, $path);
    }

    public function data(array $data)
    {
        $this->templateData = $data;
    }

    public function setData($key, $value)
    {
        array_set($this->templateData, $key, $value);

        return $this;
    }

    public function getData($key = false, $default = null)
    {
        if (!$key) {
            return $this->templateData;
        }

        return array_get($this->templateData, $key, $default);
    }

    public function targetBase()
    {
        if ($this->psr4) {
            // Trim App Namespace
            $namespace = ltrim(ltrim($this->getNamespace(), $this->getAppNamespace()), '\\');
            return app_path(str_replace('\\', DIRECTORY_SEPARATOR, $namespace));
        }

        $base = config('clumsy.cms.'.$this->getSlug().'-path');
        return $this->normalizeDirectory($base);
    }

    public function targetName()
    {
        return $this->getData('object_name');
    }

    public function targetFileName()
    {
        return $this->targetName().'.php';
    }

    public function targetFile()
    {
        return $this->targetBase().DIRECTORY_SEPARATOR.$this->targetFileName();
    }

    public function newFileObject($filename, $copy = null, $data = null)
    {
        $file = app()->make(File::class);
        $file->name($filename);

        if (!is_null($copy)) {
            $file->copyContentsFrom($copy, $data);
        }

        return $file;
    }

    protected function beforeMakeFile()
    {
        if ($this->getData('namespace', false) === false) {
            $this->setData('namespace', $this->getNamespace());
        }

        if ($this->getData('model_with_namespace', false) === false) {
            $this->setData('model_with_namespace', $this->getNamespace('model').'\\'.$this->getData('object_name'));
        }
    }

    protected function makeFile(File $file = null)
    {
        $this->beforeMakeFile();

        if (is_null($file)) {
            $file = $this->newFileObject($this->targetFile(), $this->origin(), $this->templateData);
        }

        $file->save();
    }

    protected function makeFolder($path)
    {
        $path = $this->targetBase().DIRECTORY_SEPARATOR.$this->normalizeDirectory($path);

        if (!Filesystem::exists($path)) {
            Filesystem::makeDirectory($path, 0777, true);
        }
    }

    public function make()
    {
        return $this->makeFile();
    }
}
