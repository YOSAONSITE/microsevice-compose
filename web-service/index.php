<?php
require_once 'db.php';

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, trim($_GET['search'])) : '';
if ($search != '') {
    $query = "SELECT * FROM hardware WHERE device_name LIKE '%$search%' OR asset_tag LIKE '%$search%' OR category LIKE '%$search%' OR ip_address LIKE '%$search%' ORDER BY id DESC";
} else {
    $query = "SELECT * FROM hardware ORDER BY id DESC";
}
$result = mysqli_query($conn, $query);

// Summary Stats
$total_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM hardware");
$total_devices = mysqli_fetch_assoc($total_query)['count'] ?? 0;

$online_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM hardware WHERE status='Online'");
$online_devices = mysqli_fetch_assoc($online_query)['count'] ?? 0;

$maint_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM hardware WHERE status='Maintenance'");
$maint_devices = mysqli_fetch_assoc($maint_query)['count'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NetPulse SaaS - Network Hardware Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            900: '#0c4a6e',
                        }
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-zinc-950 text-zinc-100 min-h-screen antialiased selection:bg-sky-500/20 selection:text-sky-400">

    <!-- Top Navigation Bar -->
    <header class="border-b border-zinc-800 bg-zinc-900/50 backdrop-blur sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="h-9 w-9 rounded-lg bg-sky-500/10 border border-sky-500/20 flex items-center justify-center text-sky-400 font-bold text-lg">
                    <svg class="w-5 h-5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path>
                    </svg>
                </div>
                <div>
                    <span class="font-semibold text-zinc-100 tracking-tight text-base">NetPulse SaaS</span>
                    <span class="ml-2 text-xs text-zinc-400 font-mono">v2.4.0</span>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <div class="hidden sm:flex items-center space-x-2 text-xs font-mono bg-zinc-900 border border-zinc-800 px-3 py-1.5 rounded-md text-zinc-400">
                    <span class="text-zinc-500">Service:</span>
                    <span class="text-sky-400 font-medium">php-apache-yosa</span>
                </div>
                <div class="text-xs text-zinc-400 font-mono bg-zinc-900 border border-zinc-800 px-3 py-1.5 rounded-md">
                    YOSA | 2301010184
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-zinc-100">Network Hardware Assets</h1>
                <p class="text-sm text-zinc-400 mt-1">Manage network infrastructure inventory, active nodes, and maintenance schedules.</p>
            </div>
            <div>
                <a href="create.php" class="inline-flex items-center justify-center rounded-md bg-zinc-100 px-4 py-2 text-sm font-medium text-zinc-900 hover:bg-zinc-200 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-zinc-400">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Register Hardware
                </a>
            </div>
        </div>

        <!-- Metrics Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="rounded-xl border border-zinc-800 bg-zinc-900/60 p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-zinc-400 uppercase tracking-wider">Total Hardware Assets</span>
                    <span class="p-2 rounded-md bg-zinc-800 text-zinc-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </span>
                </div>
                <div class="text-3xl font-bold text-zinc-100 mt-2"><?= $total_devices ?></div>
            </div>

            <div class="rounded-xl border border-zinc-800 bg-zinc-900/60 p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-emerald-400 uppercase tracking-wider">Online Nodes</span>
                    <span class="p-2 rounded-md bg-emerald-500/10 text-emerald-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </span>
                </div>
                <div class="text-3xl font-bold text-emerald-400 mt-2"><?= $online_devices ?></div>
            </div>

            <div class="rounded-xl border border-zinc-800 bg-zinc-900/60 p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-amber-400 uppercase tracking-wider">Under Maintenance</span>
                    <span class="p-2 rounded-md bg-amber-500/10 text-amber-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </span>
                </div>
                <div class="text-3xl font-bold text-amber-400 mt-2"><?= $maint_devices ?></div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="rounded-xl border border-zinc-800 bg-zinc-900/40 overflow-hidden shadow-xl">
            <!-- Search Filter Bar -->
            <div class="p-4 border-b border-zinc-800 flex flex-col sm:flex-row items-center justify-between gap-4">
                <form method="GET" action="index.php" class="w-full sm:w-96 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-zinc-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search Asset Tag, Device, Category, IP..." class="w-full pl-9 pr-4 py-2 text-sm bg-zinc-950 border border-zinc-800 rounded-md text-zinc-100 placeholder-zinc-500 focus:outline-none focus:ring-1 focus:ring-sky-500 focus:border-sky-500">
                </form>

                <?php if($search != ''): ?>
                    <a href="index.php" class="text-xs text-zinc-400 hover:text-zinc-200 underline">Reset Search Filter</a>
                <?php endif; ?>
            </div>

            <!-- Hardware Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-zinc-800 bg-zinc-900/80 text-xs font-semibold text-zinc-400 uppercase tracking-wider">
                            <th class="py-3.5 px-4">#</th>
                            <th class="py-3.5 px-4">Asset Tag</th>
                            <th class="py-3.5 px-4">Device Name</th>
                            <th class="py-3.5 px-4">Category</th>
                            <th class="py-3.5 px-4">IP Address</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800 text-sm">
                        <?php 
                        $no = 1;
                        if(mysqli_num_rows($result) > 0):
                            while($row = mysqli_fetch_assoc($result)): 
                                $status = $row['status'];
                                $badgeStyle = "bg-zinc-800 text-zinc-400 border-zinc-700";
                                if ($status === 'Online') {
                                    $badgeStyle = "bg-emerald-500/10 text-emerald-400 border-emerald-500/20";
                                } elseif ($status === 'Maintenance') {
                                    $badgeStyle = "bg-amber-500/10 text-amber-400 border-amber-500/20";
                                } elseif ($status === 'Offline') {
                                    $badgeStyle = "bg-rose-500/10 text-rose-400 border-rose-500/20";
                                }
                        ?>
                        <tr class="hover:bg-zinc-900/60 transition-colors">
                            <td class="py-3.5 px-4 text-zinc-500 font-mono text-xs"><?= $no++ ?></td>
                            <td class="py-3.5 px-4 font-mono font-medium text-sky-400 text-xs"><?= htmlspecialchars($row['asset_tag']) ?></td>
                            <td class="py-3.5 px-4 font-medium text-zinc-100"><?= htmlspecialchars($row['device_name']) ?></td>
                            <td class="py-3.5 px-4 text-zinc-400"><?= htmlspecialchars($row['category']) ?></td>
                            <td class="py-3.5 px-4 font-mono text-xs text-zinc-300"><?= htmlspecialchars($row['ip_address']) ?></td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border <?= $badgeStyle ?>">
                                    <?= htmlspecialchars($status) ?>
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right space-x-2">
                                <a href="edit.php?id=<?= $row['id'] ?>" class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-zinc-300 hover:text-zinc-100 bg-zinc-800 hover:bg-zinc-700 rounded border border-zinc-700 transition-colors">
                                    Edit
                                </a>
                                <a href="delete.php?id=<?= $row['id'] ?>" class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-rose-400 hover:text-rose-300 bg-rose-950/40 hover:bg-rose-900/50 rounded border border-rose-900/60 transition-colors" onclick="return confirm('Confirm deletion of hardware asset?')">
                                    Delete
                                </a>
                            </td>
                        </tr>
                        <?php 
                            endwhile;
                        else:
                        ?>
                        <tr>
                            <td colspan="7" class="py-8 text-center text-zinc-500">No hardware assets registered yet.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <footer class="pt-6 border-t border-zinc-800 text-center text-xs text-zinc-500 font-mono">
            Podman Container Architecture &bull; Service: php-apache-yosa &bull; Red Hat OpenShift DO188 Lab
        </footer>

    </main>
</body>
</html>
