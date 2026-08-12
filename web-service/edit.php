<?php
require_once 'db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

$query = "SELECT * FROM hardware WHERE id = $id";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $asset_tag   = mysqli_real_escape_string($conn, trim($_POST['asset_tag']));
    $device_name = mysqli_real_escape_string($conn, trim($_POST['device_name']));
    $category    = mysqli_real_escape_string($conn, trim($_POST['category']));
    $ip_address  = mysqli_real_escape_string($conn, trim($_POST['ip_address']));
    $status      = mysqli_real_escape_string($conn, trim($_POST['status']));

    if (empty($asset_tag) || empty($device_name) || empty($category) || empty($ip_address) || empty($status)) {
        $error = 'All form fields are mandatory!';
    } else {
        $updateQuery = "UPDATE hardware SET asset_tag='$asset_tag', device_name='$device_name', category='$category', ip_address='$ip_address', status='$status' WHERE id=$id";
        if (mysqli_query($conn, $updateQuery)) {
            header('Location: index.php');
            exit;
        } else {
            $error = 'Failed to update hardware asset: ' . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Hardware Asset - NetPulse SaaS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-zinc-950 text-zinc-100 min-h-screen antialiased flex items-center justify-center p-4">
    <div class="w-full max-w-lg">
        <div class="rounded-xl border border-zinc-800 bg-zinc-900/60 p-6 sm:p-8 shadow-2xl space-y-6">
            
            <div class="space-y-1">
                <h2 class="text-xl font-bold text-zinc-100 tracking-tight">Edit Hardware Asset</h2>
                <p class="text-xs text-zinc-400">Update configuration, location, or status of network node.</p>
            </div>

            <?php if($error): ?>
                <div class="p-3.5 rounded-md bg-rose-500/10 border border-rose-500/20 text-xs text-rose-400">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="edit.php?id=<?= $id ?>" class="space-y-4">
                <div class="space-y-1.5">
                    <label for="asset_tag" class="block text-xs font-medium text-zinc-300 uppercase tracking-wider">Asset Tag ID</label>
                    <input type="text" id="asset_tag" name="asset_tag" value="<?= htmlspecialchars($data['asset_tag']) ?>" required class="w-full px-3 py-2 text-sm bg-zinc-950 border border-zinc-800 rounded-md text-zinc-100 placeholder-zinc-500 focus:outline-none focus:ring-1 focus:ring-sky-500 focus:border-sky-500 font-mono">
                </div>

                <div class="space-y-1.5">
                    <label for="device_name" class="block text-xs font-medium text-zinc-300 uppercase tracking-wider">Device Name / Model</label>
                    <input type="text" id="device_name" name="device_name" value="<?= htmlspecialchars($data['device_name']) ?>" required class="w-full px-3 py-2 text-sm bg-zinc-950 border border-zinc-800 rounded-md text-zinc-100 focus:outline-none focus:ring-1 focus:ring-sky-500 focus:border-sky-500">
                </div>

                <div class="space-y-1.5">
                    <label for="category" class="block text-xs font-medium text-zinc-300 uppercase tracking-wider">Category</label>
                    <select id="category" name="category" required class="w-full px-3 py-2 text-sm bg-zinc-950 border border-zinc-800 rounded-md text-zinc-100 focus:outline-none focus:ring-1 focus:ring-sky-500 focus:border-sky-500">
                        <?php 
                        $categories = ['Router', 'Switch', 'Firewall', 'Access Point', 'Server'];
                        foreach ($categories as $cat):
                            $selected = ($data['category'] === $cat) ? 'selected' : '';
                        ?>
                            <option value="<?= $cat ?>" <?= $selected ?>><?= $cat ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label for="ip_address" class="block text-xs font-medium text-zinc-300 uppercase tracking-wider">IP Address</label>
                    <input type="text" id="ip_address" name="ip_address" value="<?= htmlspecialchars($data['ip_address']) ?>" required class="w-full px-3 py-2 text-sm bg-zinc-950 border border-zinc-800 rounded-md text-zinc-100 focus:outline-none focus:ring-1 focus:ring-sky-500 focus:border-sky-500 font-mono">
                </div>

                <div class="space-y-1.5">
                    <label for="status" class="block text-xs font-medium text-zinc-300 uppercase tracking-wider">Operational Status</label>
                    <select id="status" name="status" required class="w-full px-3 py-2 text-sm bg-zinc-950 border border-zinc-800 rounded-md text-zinc-100 focus:outline-none focus:ring-1 focus:ring-sky-500 focus:border-sky-500">
                        <?php 
                        $statuses = ['Online', 'Maintenance', 'Offline'];
                        foreach ($statuses as $st):
                            $selected = ($data['status'] === $st) ? 'selected' : '';
                        ?>
                            <option value="<?= $st ?>" <?= $selected ?>><?= $st ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="pt-4 flex items-center justify-end space-x-3">
                    <a href="index.php" class="px-4 py-2 text-xs font-medium text-zinc-400 hover:text-zinc-200 bg-zinc-800 hover:bg-zinc-700 rounded-md border border-zinc-700 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 text-xs font-medium text-zinc-900 bg-zinc-100 hover:bg-zinc-200 rounded-md transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-zinc-400">
                        Update Asset
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
