<?php

namespace AmbitionWorks\ModelSchema;

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\Filesystem;

class DiscoverModels
{
    /**
     * Path to models.
     *
     * @var string
     */
    private $path = '';

    /**
     * Model namespace.
     *
     * @var string
     */
    private $namespace = '';

    /**
     * Initialize the model discoverer.
     *
     * @param string $path
     * @param string $namespace
     */
    public function __construct(string $path = null, string $namespace = null)
    {
        $this->path = $path ? $path : app_path('Models');
        if ($namespace) {
            $this->namespace = $namespace;
        } else {
            $namespace = Container::getInstance()->getNamespace().'Models\\';
        }
    }

    /**
     * Set the path to models.
     *
     * @param string $path
     * @return self
     */
    public function withPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Set the model namespace.
     *
     * @param string $namespace
     * @return self
     */
    public function withNamespace(string $namespace): self
    {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * Reads all models in the path and returns a FQCN.
     *
     * @return array
     */
    public function discover(): array
    {
        return collect((new Filesystem)->allFiles($this->path))
            ->map(function ($item) {
                list($model,) = explode('.', $item->getBasename());
                return $this->namespace.$model;
            })->filter(function ($model) {
                return is_subclass_of($model, Model::class);
            })->toArray();
    }
}
