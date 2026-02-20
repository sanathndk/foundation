<?php
include('includes/config.php');

$columns = [
    0 => 'borrowernumber',
    1 => 'cardnumber',
    2 => 'title',
    3 => 'surname',
    4 => 'mobile',
    5 => 'email',
    6 => 'categorycode',
    7 => 'service',
    8 => 'status'
];

$limit = $_GET['length'];
$start = $_GET['start'];
$orderColumn = $columns[$_GET['order'][0]['column']];
$orderDir = $_GET['order'][0]['dir'];
$searchValue = $_GET['search']['value'];

$query = "SELECT * FROM member WHERE 1 ";
if (!empty($searchValue)) {
    $query .= " AND (
        cardnumber LIKE '%$searchValue%' OR
        surname LIKE '%$searchValue%' OR
        mobile LIKE '%$searchValue%' OR
        email LIKE '%$searchValue%'
    )";
}

$totalQuery = mysqli_query($dbcon, $query);
$totalFiltered = mysqli_num_rows($totalQuery);

$query .= " ORDER BY $orderColumn $orderDir LIMIT $start, $limit";
$dataQuery = mysqli_query($dbcon, $query);

$data = [];
$cnt = $start + 1;

while ($row = mysqli_fetch_assoc($dataQuery)) {

    $status = ($row['status'] == 1)
        ? "<span class='text-success'>Active</span>"
        : "<span class='text-danger'>Inactive</span>";

    $toggleBtn = ($row['status'] == 1)
        ? "<a href='manage-member.php?inactive={$row['borrowernumber']}' 
            onclick=\"return confirm('Block this member?');\" 
            class='btn btn-danger btn-sm'><i class='bi bi-toggle-off'></i></a>"
        : "<a href='manage-member.php?active={$row['borrowernumber']}' 
            onclick=\"return confirm('Activate this member?');\" 
            class='btn btn-primary btn-sm'><i class='bi bi-toggle2-off'></i></a>";

    $nested = [];
    $nested[] = $cnt++;
    $nested[] = "<a href='edit_member.php?id={$row['borrowernumber']}'>{$row['cardnumber']}</a>";
    $nested[] = $row['title'];
    $nested[] = $row['surname'];
    $nested[] = $row['mobile'];
    $nested[] = $row['email'];
    $nested[] = $row['categorycode'];
    $nested[] = $row['service'];
    $nested[] = $status;
    $nested[] = $toggleBtn;

    $data[] = $nested;
}

$json = [
    "draw" => intval($_GET['draw']),
    "recordsTotal" => $totalFiltered,
    "recordsFiltered" => $totalFiltered,
    "data" => $data
];

echo json_encode($json);
?>
