<?php
include('includes/config.php');

// --- Column list used by DataTables (MUST match table header) ---
$column_order = [
    '',
    'booknumber',
    'title',
    'itemtype',
    'isbn',
    'author',
    'classificationNo',
    'publisher',
    'status',
    ''
];

// --- DataTables variables ---
$limit  = intval($_GET['length']);
$start  = intval($_GET['start']);
$search = $_GET['search']['value'];
$draw   = intval($_GET['draw']);

// --- Base query ---
$sql = "SELECT * FROM catalog WHERE 1";

// --- Search filter ---
if (!empty($search)) {
    $sql .= " AND (
        booknumber LIKE '%$search%' OR
        title LIKE '%$search%' OR
        author LIKE '%$search%' OR
        isbn LIKE '%$search%'
    )";
}

// --- Total filtered count ---
$totalQuery = mysqli_query($dbcon, $sql);
$totalFiltered = mysqli_num_rows($totalQuery);

// --- Pagination ---
$sql .= " ORDER BY booknumber ASC LIMIT $start, $limit";
$query = mysqli_query($dbcon, $sql);

$data = [];
$cnt = $start + 1;

// --- Build output rows ---
while ($row = mysqli_fetch_assoc($query)) {

    // Status formatting
    if ($row['status'] == 'A' && $row['checkedin'] == '1') {
        $status = "<span class='text-success'><strong>Available</strong></span>";
    } elseif ($row['status'] == 'A' && $row['checkedin'] == '0') {
        $status = "<span class='text-danger'><strong>Not Available</strong></span>";
    } elseif ($row['status'] == 'N') {
        $status = "<span class='text-danger'><strong>Not Available</strong></span>";
    } elseif ($row['status'] == 'L') {
        $status = "Lost";
    } else {
        $status = "Damage";
    }

    // ---- EXACTLY 10 columns here ----
    $data[] = [
        $cnt++,                                                // 1. Ser
        $row['booknumber'],                                   // 2. Book ID
        $row['title'],                                        // 3. Name
        $row['itemtype'],                                     // 4. Item Type
        $row['isbn'],                                         // 5. ISBN
        $row['author'],                                       // 6. Author
        $row['classificationNo'] . ' ' . $row['ItemNo'],      // 7. DDC No
        $row['publisher'],                                    // 8. Publisher
        $status,                                              // 9. Status
        '<a href="edit_cataloging.php?id='.$row['booknumber'].'" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i></a>' // 10. Action
    ];
}

// --- JSON output ---
echo json_encode([
    "draw" => $draw,
    "recordsTotal" => $totalFiltered,
    "recordsFiltered" => $totalFiltered,
    "data" => $data
]);
?>
