<?php $__env->startSection('title', 'Customers'); ?>
<?php $__env->startSection('topbar-title', 'Customers'); ?>
<?php $__env->startSection('topbar-actions'); ?>
    <a href="<?php echo e(route('customers.create')); ?>" class="btn btn-sm btn-outline-white">+ Add</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div style="margin-bottom:12px;">
    <a href="<?php echo e(route('customers.create')); ?>" class="btn btn-primary btn-block">➕ Add Customer</a>
</div>

<?php if(empty($customers)): ?>
    <div class="empty-state">
        <div class="empty-icon">👥</div>
        <div class="empty-text">No customers yet</div>
        <a href="<?php echo e(route('customers.create')); ?>" class="btn btn-primary">Add First Customer</a>
    </div>
<?php else: ?>
    <div class="list-cards">
        <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $id = $customer['_id']; ?>
        <div class="list-card">
            <a href="<?php echo e(route('customers.show', $id)); ?>" class="list-card-main">
                <div class="list-card-name"><?php echo e($customer['name']); ?></div>
                <?php if(!empty($customer['phone'])): ?>
                    <div class="list-card-sub">📞 <?php echo e($customer['phone']); ?></div>
                <?php endif; ?>
                <?php if(!empty($customer['address'])): ?>
                    <div class="list-card-sub">📍 <?php echo e($customer['address']); ?></div>
                <?php endif; ?>
            </a>
            <div class="list-card-actions">
                <a href="<?php echo e(route('customers.edit', $id)); ?>" class="btn btn-sm btn-outline">Edit</a>
                <form method="POST" action="<?php echo e(route('customers.destroy', $id)); ?>"
                      onsubmit="return confirm('Delete <?php echo e(addslashes($customer['name'])); ?>?')">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\milkbook-laravel\resources\views/customers/index.blade.php ENDPATH**/ ?>