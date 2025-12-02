<?php

namespace Core\Traits;

trait LoadsModels
{
    protected array $models = [];

    /**
     * Load model
     */
    protected function loadModel(string $modelName): object
    {
        $modelClass = "App\\Models\\{$modelName}";

        if (!isset($this->models[$modelName])) {
            if (!class_exists($modelClass)) {
                throw new \Exception("Model {$modelName} not found");
            }
            $this->models[$modelName] = new $modelClass();
        }

        return $this->models[$modelName];
    }

    /**
     * Load multiple models
     */
    protected function loadModels(array $modelNames): array
    {
        $loaded = [];
        
        foreach ($modelNames as $name) {
            $loaded[$name] = $this->loadModel($name);
        }
        
        return $loaded;
    }
}