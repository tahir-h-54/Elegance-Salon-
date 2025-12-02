<?php
session_start();
include '../../Database/connect_to_db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../AD_login.php");
    exit();
}

$success = "";
$error = "";

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $price = floatval($_POST['price']);
    $sale_price = !empty($_POST['sale_price']) ? floatval($_POST['sale_price']) : NULL;
    $stock_quantity = intval($_POST['stock_quantity']);
    $sku = mysqli_real_escape_string($conn, $_POST['sku']);
    $image_path = mysqli_real_escape_string($conn, $_POST['image_path'] ?? '');
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    // Check if SKU already exists
    $check_sku = "SELECT product_id FROM products WHERE sku = '$sku'";
    $sku_result = mysqli_query($conn, $check_sku);
    
    if(mysqli_num_rows($sku_result) > 0) {
        $error = "SKU already exists. Please use a different SKU.";
    } else {
        $insert_query = "INSERT INTO products (name, description, category, price, sale_price, stock_quantity, sku, image_path, status) 
                        VALUES ('$name', '$description', '$category', $price, " . ($sale_price ? $sale_price : 'NULL') . ", $stock_quantity, '$sku', '$image_path', '$status')";
        
        if(mysqli_query($conn, $insert_query)) {
            $success = "Product added successfully!";
        } else {
            $error = "Failed to add product.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Elegance Salon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <?php include '../../Components/AD_DH_sidebar.php'; ?>
        <main class="main-content flex-1 lg:ml-[250px] w-full">
            <?php include '../../Components/DB_Header.php'; ?>
            
            <div class="main p-6">
                <div class="mb-6">
                    <a href="list_products.php" class="text-gray-600 hover:text-gray-800">← Back to Products</a>
                    <h1 class="text-3xl font-bold text-[#1a1333] mt-4">Add New Product</h1>
                </div>

                <?php if($success): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>

                <?php if($error): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <div class="bg-white rounded-lg shadow p-6 max-w-3xl">
                    <form method="POST">
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                                <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d946ef]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">SKU *</label>
                                <input type="text" name="sku" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d946ef]">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                            <textarea name="description" required rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d946ef]"></textarea>
                        </div>
                        <div class="grid grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                                <input type="text" name="category" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d946ef]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Price ($) *</label>
                                <input type="number" name="price" required step="0.01" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d946ef]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Sale Price ($)</label>
                                <input type="number" name="sale_price" step="0.01" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d946ef]">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity *</label>
                                <input type="number" name="stock_quantity" required min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d946ef]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                                <select name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d946ef]">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="out_of_stock">Out of Stock</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Image Path</label>
                            <input type="text" name="image_path" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d946ef]" placeholder="images/products/product.jpg">
                            <p class="text-xs text-gray-500 mt-1">Path to product image</p>
                        </div>
                        <div class="flex gap-4">
                            <button type="submit" class="px-6 py-2 bg-[#d946ef] text-white rounded-lg hover:bg-purple-700 font-semibold">
                                Add Product
                            </button>
                            <a href="list_products.php" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-semibold">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

