<?php
require_once "../config.php";

// Initialize variables
$searchValue = $_POST['search'] ?? '';
$start = intval($_POST['start'] ?? 0);
$length = intval($_POST['length'] ?? 10);
$draw = intval($_POST['draw'] ?? 1);

// Define the number of months to display
$monthsToDisplay = 1; // Change this to the number of months you want to display

// Get the current month and year
$currentMonth = date('m'); // Current month in numeric format (01-12)
$currentYear = date('Y');  // Current year in numeric format (4 digits)

// Calculate the month and year for the range
$startMonth = (int)$currentMonth - $monthsToDisplay + 1;
$startYear = $currentYear;

if ($startMonth <= 0) {
    $startMonth += 12; // Adjust the month to be within 1-12
    $startYear -= 1; // Adjust the year if needed
}

// Build the search clause to filter data for the specified months
$searchClause = " WHERE (YEAR(tanggal) = ? AND MONTH(tanggal) BETWEEN ? AND ?)";
$params = [$currentYear, $startMonth, $currentMonth];
$types = 'iii'; // 'i' for integer (year and month)

// If there's a search value, add it to the search clause
if ($searchValue) {
    $searchClause .= " AND (guru LIKE ? OR kelas LIKE ? OR pelajaran LIKE ?)";
    $searchValue = "%$searchValue%";
    $params = array_merge($params, array_fill(0, 3, $searchValue));
    $types .= str_repeat('s', 3); // 's' for string
}

// Query for counting total records
$queryCount = "SELECT COUNT(*) AS total FROM tbl_kegiatanpembelajaran" . $searchClause;
$stmt = $koneksi->prepare($queryCount);

if (!$stmt) {
    echo json_encode(['error' => 'Database error: ' . $koneksi->error]);
    exit;
}

$stmt->bind_param($types, ...$params);
$stmt->execute();
$totalDataResult = $stmt->get_result();
$totalData = $totalDataResult->fetch_assoc()['total'];

// Query for fetching the data
$query = "SELECT id, guru, kelas, Pelajaran, materiPembelajaran, kegiatanPembelajaran, hari, tanggal 
          FROM tbl_kegiatanpembelajaran" . $searchClause . " 
          ORDER BY updateData DESC LIMIT ?, ?";
$stmt = $koneksi->prepare($query);

if (!$stmt) {
    echo json_encode(['error' => 'Database error: ' . $koneksi->error]);
    exit;
}

// Add pagination parameters (start and length)
array_push($params, $start, $length);
$types .= 'ii'; // Add integer type for pagination
$stmt->bind_param($types, ...$params);
$stmt->execute();
$dataResult = $stmt->get_result();

$data = [];
$no = $start + 1;
while ($row = $dataResult->fetch_assoc()) {
    $row['no'] = $no++;
    $row['operasi'] = '
        <a href="edit-inputakp.php?id=' . $row['id'] . '" class="btn btn-sm btn-warning" title="Update kegiatan pembelajaran">
            <i class="fa-solid fa-pen"></i>
        </a>
        <button type="button" class="btn btn-sm btn-danger" id="btnDelete" title="Delete kegiatan pembelajaran" data-id="' . $row['id'] . '">
            <i class="fa-solid fa-trash"></i>
        </button>';
    $data[] = $row;
}

// Prepare the response
$response = [
    "draw" => $draw,
    "recordsTotal" => $totalData,
    "recordsFiltered" => $totalData,
    "data" => $data
];

header('Content-Type: application/json');
echo json_encode($response);
?>
