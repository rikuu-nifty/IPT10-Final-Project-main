<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Category;
use App\Models\Product;

class PageController extends BaseController
{
    // Session management setup (if not done already)

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // Assuming the session is stored in $_SESSION superglobal
        $this->session = $_SESSION;
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
        $content = $this->render($templateName, $data);
        $layoutData = array_merge($data, [
            'content' => $content,
        ]);
        return $this->render('layout', $layoutData);
    }

    public function dashboard()
    {
        $productModel = new Product();
        $products = $productModel->getAllProducts();

        $data = [
            'products' => $products        
        ];
        echo $this->renderPage('dashboard', $data);
    }


    public function manageProducts()
    {
        $productModel = new Product();
        $products = $productModel->getAllProducts();

        $data = [
            'products' => $products        
        ];
        echo $this->renderPage('managed-products', $data);
    }

    public function categories()
    {
        $categoryModel = new Category();
        $categories = $categoryModel->getAllCategories();
        $message = isset($this->session['msg']) ? $this->session['msg'] : '';
    
        // Debugging message value
        error_log("Session message: " . $message);
    
        $data = [
            'categories' => $categories,
            'message' => $message,  // Pass 'message' from session
        ];
    
        // Clear message from session after rendering it once
        unset($this->session['msg']);
    
        echo $this->renderPage('categories', $data); // Render categories.mustache

    }

    public function addCategory()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category_name'])) {
            $category_name = $_POST['category_name'];

            // Validate input
            if (empty($category_name)) {
                $this->session['msg'] = 'Category name cannot be empty.'; // Storing session message
                $this->redirect('/categories');
            }

            $categoryModel = new Category();
            $result = $categoryModel->save($category_name);

            if ($result > 0) {
                $this->session['msg'] = 'Category added successfully.';
            } else {
                $this->session['msg'] = 'Failed to add category.';
            }
            $this->redirect('/categories');
        }
    }


    public function editCategory()
    {
        // Get the category ID from the query string
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
        if ($id <= 0) {
            // If the ID is invalid, redirect or show an error
            $this->session['msg'] = 'Invalid category ID.';
            $this->redirect('/categories');
        }
    
        // Fetch the category from the database
        $categoryModel = new Category();
        $category = $categoryModel->getCategoryById($id);
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category_name'])) {
            $category_name = $_POST['category_name'];
    
            // Validate input
            if (empty($category_name)) {
                $this->session['msg'] = 'Category name cannot be empty.';
                $this->redirect('/edit-category?id=' . $id);
            }
    
            $result = $categoryModel->update($id, $category_name);
    
            if ($result > 0) {
                $this->session['msg'] = 'Category updated successfully.';
            } else {
                $this->session['msg'] = 'Failed to update category.';
            }
    
            $this->redirect('/categories');
        }
    
        // Prepare the data for the template
        $data = [
            'category' => $category
        ];
        echo $this->renderPage('edit-category', $data); // Assuming your Mustache file is named edit-category.mustache
    }   
    

    public function deleteCategory()
{
    // Get the ID from the query string
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    // Ensure the ID is valid
    if ($id <= 0) {
        $this->session['msg'] = 'Invalid category ID.';
        $this->redirect('/categories');
        return;
    }

    // Proceed with deletion
    $categoryModel = new Category();
    $result = $categoryModel->delete($id);

    // Set success or failure message
    if ($result > 0) {
        $this->session['msg'] = 'Category Deleted Successfully.';
    } else {
        $this->session['msg'] = 'Failed to delete category.';
    }

    // Redirect back to categories page
    $this->redirect('/categories');
}


    



    // Redirect method moved inside class
    public function redirect($url)
    {
        header("Location: " . $url);
        exit;
    }
}
