<?php
require_once "config.php";
$pageTitle = "Books";
$q = trim($_GET["q"] ?? "");
$category = trim($_GET["category"] ?? "");
$page = max(1, (int)($_GET["page"] ?? 1));
$perPage = 8;
$offset = ($page-1)*$perPage;

$where = ["p.is_active=1"];
$params = []; $types = "";
if ($q !== "") { $where[] = "(p.title LIKE ? OR p.author LIKE ? OR p.description LIKE ?)"; $like="%$q%"; $params=[$like,$like,$like]; $types="sss"; }
if ($category !== "") { $where[] = "p.category=?"; $params[]=$category; $types.="s"; }
$whereSql = implode(" AND ", $where);

$countSql = "SELECT COUNT(*) c FROM products p WHERE $whereSql";
$stmt=$conn->prepare($countSql); if($types) $stmt->bind_param($types,...$params); $stmt->execute(); $total=(int)$stmt->get_result()->fetch_assoc()["c"]; $stmt->close();
$pages=max(1,(int)ceil($total/$perPage));

$sql="SELECT p.* FROM products p WHERE $whereSql ORDER BY p.created_at DESC LIMIT ? OFFSET ?";
$stmt=$conn->prepare($sql);
$types2=$types."ii"; $params2=$params; $params2[]=$perPage; $params2[]=$offset;
$stmt->bind_param($types2,...$params2); $stmt->execute(); $books=$stmt->get_result()->fetch_all(MYSQLI_ASSOC); $stmt->close();

$cats=[]; $r=$conn->query("SELECT DISTINCT category FROM products WHERE is_active=1 ORDER BY category"); while($row=$r->fetch_assoc()) $cats[]=$row["category"];
require "header.php";
?>
<div class="container py-5">
  <div class="section-heading"><span>Bookstore</span><h1>Browse the collection</h1></div>
  <form class="filter-bar row g-2 mb-4" method="get">
    <div class="col-md-6"><input class="form-control" name="q" value="<?= e($q) ?>" placeholder="Search by title, author or keyword..."></div>
    <div class="col-md-3"><select class="form-select" name="category"><option value="">All categories</option><?php foreach($cats as $c): ?><option <?= $category===$c?"selected":"" ?>><?= e($c) ?></option><?php endforeach; ?></select></div>
    <div class="col-md-2"><button class="btn btn-primary w-100">Search</button></div>
    <div class="col-md-1"><a class="btn btn-outline-secondary w-100" href="products.php"><i class="bi bi-arrow-counterclockwise"></i></a></div>
  </form>
  <div class="row g-4">
  <?php foreach($books as $book): ?>
    <div class="col-sm-6 col-lg-3"><div class="product-card h-100">
      <div class="cover"><?= e(strtoupper(substr($book["title"],0,1))) ?></div>
      <div class="p-3 d-flex flex-column h-auto"><span class="small text-primary"><?= e($book["category"]) ?></span><h5 class="mt-1 mb-1"><?= e($book["title"]) ?></h5><p class="small text-secondary mb-2">by <?= e($book["author"]) ?></p><p class="small flex-grow-1"><?= e($book["description"]) ?></p>
      <div class="d-flex justify-content-between align-items-center"><strong>₹<?= number_format($book["price"],2) ?></strong><a class="btn btn-sm btn-primary" href="product.php?id=<?= $book["id"] ?>">View</a></div></div>
    </div></div>
  <?php endforeach; ?>
  </div>
  <?php if(!$books): ?><div class="empty-state mt-4"><i class="bi bi-search"></i><h4>No books found</h4><p>Try a different search or category.</p></div><?php endif; ?>
  <?php if($pages>1): ?><nav class="mt-5"><ul class="pagination justify-content-center">
    <?php for($i=1;$i<=$pages;$i++): ?><li class="page-item <?= $i===$page?"active":"" ?>"><a class="page-link" href="?q=<?= urlencode($q) ?>&category=<?= urlencode($category) ?>&page=<?= $i ?>"><?= $i ?></a></li><?php endfor; ?>
  </ul></nav><?php endif; ?>
</div>
<?php require "footer.php"; ?>