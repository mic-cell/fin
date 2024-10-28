<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit;
}

// End the session and logout if the page is refreshed
session_unset();
session_destroy();

$dataFile = 'data.txt';
$data = ['income' => [], 'expenses' => []];

if (file_exists($dataFile)) {
    $fileContents = file_get_contents($dataFile);
    $data = json_decode($fileContents, true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start(); // Restart session to handle post requests
    if (isset($_POST['add_income'])) {
        $amount = floatval($_POST['amount']);
        $description = $_POST['description'];
        $data['income'][] = ['amount' => $amount, 'description' => $description];
    } elseif (isset($_POST['add_expense'])) {
        $amount = floatval($_POST['amount']);
        $description = $_POST['description'];
        $data['expenses'][] = ['amount' => $amount, 'description' => $description];
    }

    file_put_contents($dataFile, json_encode($data));
    header('Location: dashboard.php');
    exit;
}

$totalIncome = array_sum(array_column($data['income'], 'amount'));
$totalExpenses = array_sum(array_column($data['expenses'], 'amount'));

function formatCurrency($amount) {
    return number_format($amount, 2, '.', ',');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dark-mode">
    <div class="container">
        <h2>Dashboard</h2>
        <p>Total Pemasukan: <span id="totalIncome"><?= formatCurrency($totalIncome) ?></span></p>
        <p>Total Pengeluaran: <span id="totalExpenses"><?= formatCurrency($totalExpenses) ?></span></p>

        <div class="form-container">
            <h3>Tambah Pemasukan</h3>
            <form method="post" action="">
                <input type="number" step="0.01" name="amount" placeholder="Jumlah" required>
                <select name="description" required>
                    <option value="Gaji">Gaji</option>
                    <option value="Kerja sampingan">Kerja sampingan</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
                <button type="submit" name="add_income">Simpan Transaksi</button>
            </form>
        </div>

        <div class="form-container">
            <h3>Tambah Pengeluaran</h3>
            <form method="post" action="">
                <input type="number" step="0.01" name="amount" placeholder="Jumlah" required>
                <select name="description" required>
                    <option value="Makanan">Makanan</option>
                    <option value="Transportasi">Transportasi</option>
                    <option value="Pakaian">Pakaian</option>
                    <option value="Hiburan">Hiburan</option>
                    <option value="Kesehatan">Kesehatan</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
                <button type="submit" name="add_expense">Simpan Transaksi</button>
            </form>
        </div>
    </div>
</body>
</html>
