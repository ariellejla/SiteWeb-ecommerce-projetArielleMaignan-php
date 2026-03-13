<?php
require_once 'config/database.php';

class ProductManager {
    private $conn;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    // Afficher tous les produits
    public function getAllProducts() {
        $query = "SELECT * FROM products ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Afficher un produit spécifique
    public function getProduct($id) {
        $query = "SELECT * FROM products WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

// Interface d'affichage
$productManager = new ProductManager();
$products = $productManager->getAllProducts();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Nos Produits</title>
    <style>
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .product-card {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }
        .product-card img {
            max-width: 100%;
            height: 150px;
            object-fit: cover;
        }
        .price {
            font-size: 1.2em;
            color: #e44d26;
            font-weight: bold;
        }
        .btn-add {
            background: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Catalogue de Produits</h1>
    <div class="product-grid">
        <?php foreach($products as $product): ?>
        <div class="product-card">
            <img src="<?php echo $product['image_url']; ?>" 
                 alt="<?php echo $product['name']; ?>">
            <h3><?php echo $product['name']; ?></h3>
            <p><?php echo substr($product['description'], 0, 50); ?>...</p>
            <p class="price"><?php echo number_format($product['price'], 2); ?> €</p>
            <p>Stock: <?php echo $product['stock']; ?></p>
            <form action="cart.php" method="POST">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <input type="number" name="quantity" value="1" min="1" 
                       max="<?php echo $product['stock']; ?>">
                <button type="submit" name="add_to_cart" class="btn-add">
                    Ajouter au panier
                </button>
            </form>
        </div>
        <?php endforeach; ?>
    </div>
</body>
</html>