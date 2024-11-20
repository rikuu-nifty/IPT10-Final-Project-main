<?php

namespace App\Controllers;

use App\Models\User; // Make sure to include your User model
use App\Controllers\BaseController;

class HomeController extends BaseController
{
    private function renderPage(string $templateName, array $data): string
    {
        // Render the specific content template (e.g., dashboard.mustache, documentation.mustache)
        $content = $this->render($templateName, $data);

        // Merge the content with layout data
        $layoutData = array_merge($data, ['content' => $content]);

        // Render the layout.mustache with the content injected
        return $this->render('layout', $layoutData);
    }

    /**
     * Dashboard page
     */
    public function dashboard()
    {
        $data = [
            'title' => 'Dashboard',
            'username' => 'John Doe', // For navbar welcome text
            'student' => 'Kyle Mathew P. Salvador', // Specific to dashboard
        ];
        return $this->renderPage('dashboard', $data);
    }

    /**
     * Documentation page
     */
    public function documentation()
    {
        $data = [
            'title' => 'Documentation',
            'username' => 'John Doe', // For navbar welcome text
        ];
        return $this->renderPage('documentation', $data);
    }
}
