<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('topbar-title', 'MilkBook 🥛'); ?>

<?php $__env->startSection('topbar-actions'); ?>
    <a href="<?php echo e(route('customers.index')); ?>" class="btn btn-sm btn-outline-white">👥 Customers</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<?php
    $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    $prevMonth = $month == 1 ? 12 : $month - 1;
    $prevYear  = $month == 1 ? $year - 1 : $year;
    $nextMonth = $month == 12 ? 1 : $month + 1;
    $nextYear  = $month == 12 ? $year + 1 : $year;
    $avatarColors = ['#2E7D32','#1565C0','#6A1B9A','#AD1457','#E65100','#00838F','#4527A0','#558B2F'];
?>


<div class="month-picker">
    <a href="<?php echo e(route('dashboard', ['month'=>$prevMonth,'year'=>$prevYear])); ?>" class="month-arrow">‹</a>
    <span class="month-label"><?php echo e($months[$month-1]); ?> <?php echo e($year); ?></span>
    <a href="<?php echo e(route('dashboard', ['month'=>$nextMonth,'year'=>$nextYear])); ?>" class="month-arrow">›</a>
</div>


<div class="summary-bar">
    <div class="summary-item">
        <div class="summary-val"><?php echo e(count($customers)); ?></div>
        <div class="summary-label">Customers</div>
    </div>
    <div class="summary-divider"></div>
    <div class="summary-item">
        <div class="summary-val"><?php echo e(number_format($grandTotalLiter, 1)); ?></div>
        <div class="summary-label">Total Liters</div>
    </div>
    <div class="summary-divider"></div>
    <div class="summary-item">
        <div class="summary-val">₹<?php echo e(number_format($grandTotalAmount, 0)); ?></div>
        <div class="summary-label">Total Amount</div>
    </div>
</div>

<div class="section-label">Customers</div>

<?php if(empty($customers)): ?>
    <div class="empty-state">
        <div class="empty-icon">👥</div>
        <div class="empty-text">No customers yet</div>
        <a href="<?php echo e(route('customers.create')); ?>" class="btn btn-primary">Add Customer</a>
    </div>
<?php else: ?>
    <div class="customer-grid">
        <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $id    = $customer['_id'];
                $s     = $stats[$id] ?? ['totalLiter'=>0,'totalFat'=>0,'totalAmount'=>0,'days'=>0];
                $color = $avatarColors[$i % count($avatarColors)];
            ?>
            <a href="<?php echo e(route('customers.show', ['id'=>$id,'month'=>$month,'year'=>$year])); ?>"
               class="customer-card">
                <div class="ccard-avatar" style="background:<?php echo e($color); ?>">
                    <?php echo e(strtoupper(substr($customer['name'],0,1))); ?>

                </div>
                <div class="ccard-name"><?php echo e($customer['name']); ?></div>
                <?php if(!empty($customer['phone'])): ?>
                    <div class="ccard-phone"><?php echo e($customer['phone']); ?></div>
                <?php endif; ?>
                <div class="ccard-divider"></div>
                <div class="ccard-stats">
                    <div class="ccard-stat">
                        <div class="ccard-stat-val"><?php echo e(number_format($s['totalLiter'],1)); ?></div>
                        <div class="ccard-stat-label">Liters</div>
                    </div>
                    <div class="ccard-stat">
                        <div class="ccard-stat-val"><?php echo e(number_format($s['totalFat'],1)); ?></div>
                        <div class="ccard-stat-label">Fat</div>
                    </div>
                </div>
                <?php if($s['totalAmount'] > 0): ?>
                    <div class="ccard-amount" style="background:<?php echo e($color); ?>22;color:<?php echo e($color); ?>">
                        ₹<?php echo e(number_format($s['totalAmount'],0)); ?>

                    </div>
                <?php endif; ?>
                <?php if($s['days'] > 0): ?>
                    <div class="ccard-days"><?php echo e($s['days']); ?> days</div>
                <?php endif; ?>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\milkbook-laravel\resources\views/dashboard.blade.php ENDPATH**/ ?>