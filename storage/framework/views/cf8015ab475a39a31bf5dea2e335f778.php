<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'MilkBook'); ?> — MilkBook</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>


<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <span class="sidebar-logo">🥛</span>
        <span class="sidebar-brand">MilkBook</span>
        <button class="sidebar-close" onclick="toggleSidebar()">✕</button>
    </div>

    <div class="sidebar-user">
        <div class="sidebar-avatar"><?php echo e(strtoupper(substr(session('user')['name'] ?? '?', 0, 1))); ?></div>
        <div>
            <div class="sidebar-username"><?php echo e(session('user')['name'] ?? '?'); ?></div>
            <div class="sidebar-phone"><?php echo e(session('user')['phone'] ?? ''); ?></div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <a href="<?php echo e(route('dashboard')); ?>"   class="nav-item <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
            <span class="nav-icon">🏠</span> Dashboard
        </a>
        <a href="<?php echo e(route('customers.index')); ?>" class="nav-item <?php echo e(request()->routeIs('customers.*') ? 'active' : ''); ?>">
            <span class="nav-icon">👥</span> Customers
        </a>
        <a href="<?php echo e(route('profile')); ?>" class="nav-item <?php echo e(request()->routeIs('profile') ? 'active' : ''); ?>">
            <span class="nav-icon">👤</span> My Profile
        </a>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn-logout">🚪 Logout</button>
        </form>
    </div>
</aside>
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>


<div class="main-wrap">
    <header class="topbar">
        <button class="topbar-menu" onclick="toggleSidebar()">☰</button>
        <span class="topbar-title"><?php echo $__env->yieldContent('topbar-title', 'MilkBook'); ?></span>
        <?php echo $__env->yieldContent('topbar-actions'); ?>
    </header>

    <main class="content">
        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
        <?php endif; ?>
        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div><?php echo e($error); ?></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </main>
</div>

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('show');
}
</script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\milkbook-laravel\resources\views/layouts/app.blade.php ENDPATH**/ ?>