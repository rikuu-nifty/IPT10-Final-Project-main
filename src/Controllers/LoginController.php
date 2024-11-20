<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Category;
use App\Models\Product;

class PageController extends BaseController
{
    // Ensure session is started
    private function startSession()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Render a full page with layout and content
     *
     * @param string $templateName The name of the content template
     * @param array $data The data to pass to the template
     * @return string Rendered page
     */
    private function renderPage(string $templateName, array $data): string
    {
        // Render the specific page content
        $content = $this->render($templateName, $data);

        // Prepare data for the layout
        $layoutData = array_merge($data, [
            'content' => $content,
        ]);

        // Render the layout with the injected content
        return $this->render('layout', $layoutData);
    }

    public function manageProducts()
    {
        // Start session and handle any session-based logic
        $this->startSession();

        // Create an instance of the Product model
        $productModel = new Product();

        // Fetch all products from the database
        $products = $productModel->getAllProducts();

        $data = [
            'products' => $products        // The list of products fetched
        ];

        // Render and output the page with the data
        echo $this->renderPage('managed-products', $data);
    }

    public function categories()
    {
        // Start session
        $this->startSession();

        // Create an instance of the Category model
        $categoryModel = new Category();

        // Fetch all categories from the database
        $categories = $categoryModel->getAllCategories();

        // Prepare data for the view
        $data = [
            'categories' => $categories  // The list of categories fetched
        ];

        // Render and output the page with the data
        echo $this->renderPage('categories', $data);
    }

    public function addCategory()
    {
        // Start session
        $this->startSession();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category_name'])) {
            $category_name = $_POST['category_name'];

            // Validate input
            if (empty($category_name)) {
                $this->session['msg'] = 'Category name cannot be empty.';
                $this->redirect('/categories');
            }

            // Create an instance of the Category model
            $categoryModel = new Category();

            // Save the category to the database
            $result = $categoryModel->save($category_name);

            // Check if category was added successfully
            if ($result > 0) {
                $this->session['msg'] = 'Category added successfully.';
            } else {
                $this->session['msg'] = 'Failed to add category.';
            }

            // Redirect back to the categories page
            $this->redirect('/categories');
        }
    }

    // Redirect helper to handle redirection
    private function redirect($url)
    {
        header("Location: " . $url);
        exit;
    }
}
