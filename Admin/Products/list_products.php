<?php
session_start();
include '../../Database/connect_to_db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../AD_login.php");
    exit();
}

$page_title = "Manage Products";
$success = "";
$error = "";

// Handle delete
if(isset($_GET['delete'])) {
    $product_id = intval($_GET['delete']);
    $delete_query = "DELETE FROM products WHERE product_id = $product_id";
    if(mysqli_query($conn, $delete_query)) {
        $success = "Product deleted successfully.";
    } else {
        $error = "Failed to delete product.";
    }
}

// Fetch all products
$products_query = "SELECT * FROM products ORDER BY created_at DESC";
$products_result = mysqli_query($conn, $products_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Elegance Salon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <?php include '../../Components/AD_DH_sidebar.php'; ?>
        <main class="main-content flex-1 lg:ml-[250px] w-full">
            <?php include '../../Components/DB_Header.php'; ?>
            
            <div class="main p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold text-[#1a1333]">Manage Products</h1>
                    <a href="add_product.php" class="px-6 py-2 bg-[#CFF752] text-white rounded-lg hover:bg-[#c8f73c] font-semibold">
                        + Add Product
                    </a>
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

                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php if(mysqli_num_rows($products_result) > 0): ?>
                                <?php while($product = mysqli_fetch_assoc($products_result)): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div class="font-medium"><?php echo htmlspecialchars($product['name']); ?></div>
                                            <div class="text-sm text-gray-500">SKU: <?php echo htmlspecialchars($product['sku']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($product['category']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php if($product['sale_price']): ?>
                                                <span class="text-gray-400 line-through">$<?php echo number_format($product['price'], 2); ?></span>
                                                <span class="ml-2 font-semibold text-[#CFF752]">$<?php echo number_format($product['sale_price'], 2); ?></span>
                                            <?php else: ?>
                                                <span class="font-semibold">$<?php echo number_format($product['price'], 2); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="<?php echo $product['stock_quantity'] > 0 ? 'text-green-600' : 'text-red-600'; ?> font-semibold">
                                                <?php echo $product['stock_quantity']; ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                                <?php 
                                                echo $product['status'] == 'active' ? 'bg-green-100 text-green-800' : 
                                                    ($product['status'] == 'out_of_stock' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'); 
                                                ?>">
                                                <?php echo ucfirst(str_replace('_', ' ', $product['status'])); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="edit_product.php?id=<?php echo $product['product_id']; ?>" class="text-blue-600 hover:text-blue-800 mr-3">Edit</a>
                                            <a href="?delete=<?php echo $product['product_id']; ?>" onclick="return confirm('Are you sure?')" class="text-red-600 hover:text-red-800">Delete</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No products found. <a href="add_product.php" class="text-[#d946ef] hover:underline">Add one now</a></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

