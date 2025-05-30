<?php
// MUST be first line - no whitespace before!
ob_start();
require '../layouts/header.php'; // Load config FIRST
require '../../config/config.php'; // Load config FIRST

// Check session before any output
if (!isset($_SESSION['adminname'])) {
    header("Location: " . ADMIN_URL . "admins/login-admins.php");
    exit;
}

// Now load header.php (after all header operations)
require '../layouts/header.php';

// Get export parameters
$format = $_POST['format'] ?? 'csv';
$dateRange = $_POST['date_range'] ?? 'all';
$startDate = $_POST['start_date'] ?? null;
$endDate = $_POST['end_date'] ?? null;

// Build query (your existing code)
$query = "SELECT * FROM bookings";
// ... [rest of your query building code] ...

try {
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($bookings)) {
        $_SESSION['error_message'] = "No bookings found for the selected criteria.";
        header("Location: " . ADMIN_URL . "bookings-admins/show-bookings.php");
        exit;
    }
    
    // Process export
    switch ($format) {
        case 'csv':
            exportToCSV($bookings);
            break;
        case 'excel':
            exportToExcel($bookings);
            break;
        case 'pdf':
            exportToPDF($bookings);
            break;
        default:
            exportToCSV($bookings);
    }
    
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Error exporting bookings: " . $e->getMessage();
    header("Location: " . ADMIN_URL . "bookings-admins/show-bookings.php");
    exit;
}

function exportToCSV($data) {
    ob_end_clean();
    
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=bookings_export_' . date('Ymd') . '.csv');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, array_keys($data[0]));
    foreach ($data as $row) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit;
}

function exportToExcel($data) {
    ob_end_clean();
    
    // Simple HTML table as Excel (works without PhpSpreadsheet)
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="bookings_export_' . date('Ymd') . '.xls"');
    
    echo '<table border="1">';
    // Headers
    echo '<tr>';
    foreach (array_keys($data[0]) as $header) {
        echo '<th>'.htmlspecialchars($header).'</th>';
    }
    echo '</tr>';
    // Data
    foreach ($data as $row) {
        echo '<tr>';
        foreach ($row as $cell) {
            echo '<td>'.htmlspecialchars($cell).'</td>';
        }
        echo '</tr>';
    }
    echo '</table>';
    exit;
}

function exportToPDF($data) {
    ob_end_clean();
    
    // Simple HTML as PDF fallback
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="bookings_export_' . date('Ymd') . '.pdf"');
    
    $html = '<h1>Bookings Export</h1><table border="1">';
    // Headers
    $html .= '<tr>';
    foreach (array_keys($data[0]) as $header) {
        $html .= '<th>'.htmlspecialchars($header).'</th>';
    }
    $html .= '</tr>';
    // Data
    foreach ($data as $row) {
        $html .= '<tr>';
        foreach ($row as $cell) {
            $html .= '<td>'.htmlspecialchars($cell).'</td>';
        }
        $html .= '</tr>';
    }
    $html .= '</table>';
    
    // In production, replace this with TCPDF code
    echo $html;
    exit;
}