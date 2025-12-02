<?php

namespace Core\Traits;

trait RendersViews
{
    protected string $viewPath = '';
    protected array $data = [];
    protected ?string $layout = null;
    protected string $content = '';

    /**
     * Initialize view path
     */
    protected function initViewPath(): void
    {
        $this->viewPath = dirname(__DIR__) . '/app/views/';
    }

    /**
     * Render view file
     */
    public function render(string $view, array $data = []): void
    {
        $this->data = array_merge($this->data, $data);

        $filePath = $this->viewPath . str_replace('.', '/', $view) . '.php';

        if (!file_exists($filePath)) {
            throw new \Exception("View file not found: {$filePath}");
        }

        extract($this->data);
        ob_start();
        include $filePath;
        $content = ob_get_clean();

        if ($this->layout) {
            $this->content = $content;
            $layoutPath = $this->viewPath . 'layouts/' . $this->layout . '.php';

            if (file_exists($layoutPath)) {
                ob_start();
                include $layoutPath;
                $content = ob_get_clean();
            }
            $this->layout = null;
        }

        echo $content;
    }

    /**
     * Set layout
     */
    public function setLayout(string $layout): self
    {
        $this->layout = $layout;
        return $this;
    }

    /**
     * Get content for layout
     */
    public function content(): string
    {
        return $this->content;
    }

    /**
     * Include partial/component
     */
    public function partial(string $view, array $data = []): void
    {
        $filePath = $this->viewPath . 'partials/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($filePath)) {
            throw new \Exception("Partial not found: {$filePath}");
        }

        extract(array_merge($this->data, $data));
        include $filePath;
    }
}