<?php

namespace Traits;

use Psr\Http\Message\ServerRequestInterface;

trait Render
{
    /**
     * @var string
     */
    private string $viewsDirectory = ROOT_DIR. '/src/Views';

    /**
     * @param string $viewFile
     * @param array $data
     * @param string|null $layoutFile
     * @return string
     * @throws \Exception
     */
    public function  render(string $viewFile, ?string $layoutFile, array $data = []): string
    {

        $filePath = $this->viewsDirectory . '/' . $viewFile . '.php';

        if (!file_exists($filePath)) {
            throw new \Exception('Template file not found');
        }

        extract($data, EXTR_SKIP);

        ob_start();
        if(!empty($layoutFile) && file_exists($this->viewsDirectory. '/'.$layoutFile.'.php')) {
            require $this->viewsDirectory . '/' . $layoutFile . '.php';
        }

        return (string) ob_get_clean();
    }
}