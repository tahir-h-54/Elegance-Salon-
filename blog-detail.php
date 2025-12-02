<?php
include 'Database/connect_to_db.php';

$slug = isset($_GET['slug']) ? mysqli_real_escape_string($conn, $_GET['slug']) : '';

// Fetch blog post
$post_query = "SELECT bp.*, u.name as author_name FROM blog_posts bp LEFT JOIN users u ON bp.author_id = u.user_id WHERE bp.slug = '$slug' AND bp.status = 'published'";
$post_result = mysqli_query($conn, $post_query);
$post = mysqli_fetch_assoc($post_result);

if(!$post) {
    header("Location: blog.php");
    exit;
}

// Update views
$update_views = "UPDATE blog_posts SET views = views + 1 WHERE post_id = " . $post['post_id'];
mysqli_query($conn, $update_views);

// Fetch related posts
$related_query = "SELECT * FROM blog_posts WHERE status = 'published' AND post_id != " . $post['post_id'] . " AND (category = '" . mysqli_real_escape_string($conn, $post['category']) . "' OR tags LIKE '%" . mysqli_real_escape_string($conn, $post['tags']) . "%') ORDER BY created_at DESC LIMIT 3";
$related_result = mysqli_query($conn, $related_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title><?php echo htmlspecialchars($post['title']); ?> - Elegance Salon</title>
    <link rel="shortcut icon" href="images/elegance-saloon-short-logo.png" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body>
    <header class="flex justify-around lg:gap-40 fixed w-[100%] z-30 bg-white/80 backdrop-blur-sm">
        <div class="sm:flex hidden">
            <a href="blog.php" class="p-2 my-10 lg:mr-40 w-25 text-[15px] justify-center items-center flex bg-none text-[#000] rounded-full border-[#000] border-1 hover:bg-[#000] hover:text-[#fff] cursor-pointer">← Back</a>
        </div>
        <div class="sm:w-50 w-40 flex select-none">
            <a href="src/Elegance_Salon.php"><img src="images/elegance-saloon-logo-no-bg.png" alt="Elegance logo"></a>
        </div>
        <div class="sm:hidden w-10 flex my-10">
            <a href="blog.php"><span class="text-2xl">←</span></a>
        </div>
        <div class="sm:flex hidden gap-2">
            <a href="book-appointment.php" class="p-2 my-10 w-30 text-[15px] justify-center items-center flex bg-none text-[#000] rounded-full bg-[#CFF752] cursor-pointer">Book Now</a>
        </div>
    </header>

    <main class="pt-32 pb-20 px-8">
        <article class="max-w-4xl mx-auto">
            <div class="mb-8">
                <div class="flex items-center gap-4 mb-4 text-sm text-gray-500">
                    <span><?php echo date('M d, Y', strtotime($post['published_at'] ?: $post['created_at'])); ?></span>
                    <?php if($post['category']): ?>
                        <span class="px-3 py-1 bg-[#CFF752] text-black rounded-full"><?php echo htmlspecialchars($post['category']); ?></span>
                    <?php endif; ?>
                    <span><i class="far fa-eye"></i> <?php echo $post['views']; ?> views</span>
                </div>
                <h1 class="text-5xl font-bold mb-4" style="font-family: 'ivymode';"><?php echo htmlspecialchars($post['title']); ?></h1>
                <?php if($post['author_name']): ?>
                    <p class="text-gray-600">By <?php echo htmlspecialchars($post['author_name']); ?></p>
                <?php endif; ?>
            </div>

            <?php if($post['featured_image']): ?>
                <div class="mb-8">
                    <img src="<?php echo htmlspecialchars($post['featured_image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="w-full h-96 object-cover rounded-lg">
                </div>
            <?php endif; ?>

            <div class="prose max-w-none">
                <?php echo nl2br(htmlspecialchars($post['content'])); ?>
            </div>

            <?php if($post['tags']): ?>
                <div class="mt-8 pt-8 border-t">
                    <h3 class="font-semibold mb-3">Tags:</h3>
                    <div class="flex flex-wrap gap-2">
                        <?php 
                        $tags = explode(',', $post['tags']);
                        foreach($tags as $tag): 
                            $tag = trim($tag);
                            if($tag):
                        ?>
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm"><?php echo htmlspecialchars($tag); ?></span>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </div>
                </div>
            <?php endif; ?>
        </article>

        <!-- Related Posts -->
        <?php if(mysqli_num_rows($related_result) > 0): ?>
            <div class="max-w-7xl mx-auto mt-16">
                <h2 class="text-3xl font-bold mb-8" style="font-family: 'ivymode';">Related Articles</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <?php while($related = mysqli_fetch_assoc($related_result)): ?>
                        <article class="bg-white rounded-lg overflow-hidden shadow-lg">
                            <a href="blog-detail.php?slug=<?php echo htmlspecialchars($related['slug']); ?>">
                                <img src="<?php echo htmlspecialchars($related['featured_image'] ?: 'images/Blogs/top-trending-hair-cuts.jpg'); ?>" alt="<?php echo htmlspecialchars($related['title']); ?>" class="w-full h-48 object-cover">
                                <div class="p-6">
                                    <h3 class="text-xl font-bold mb-2" style="font-family: 'ivymode';"><?php echo htmlspecialchars($related['title']); ?></h3>
                                    <p class="text-gray-600 text-sm mb-4"><?php echo htmlspecialchars(substr($related['excerpt'] ?: strip_tags($related['content']), 0, 100)); ?>...</p>
                                    <a href="blog-detail.php?slug=<?php echo htmlspecialchars($related['slug']); ?>" class="text-[#CFF752] font-semibold text-sm">Read More →</a>
                                </div>
                            </a>
                        </article>
                    <?php endwhile; ?>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <footer class="bg-black text-white py-12 px-8">
        <div class="max-w-7xl mx-auto text-center">
            <p class="text-gray-400">&copy; <?php echo date('Y'); ?> Elegance Salon. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

