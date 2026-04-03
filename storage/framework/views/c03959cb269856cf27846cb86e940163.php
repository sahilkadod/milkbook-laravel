<?php $__env->startSection('title', 'Edit Customer'); ?>
<?php $__env->startSection('topbar-title', 'Edit Customer'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-card" style="max-width:480px;margin:0 auto;">
    <form method="POST" action="<?php echo e(route('customers.update', $customer['_id'])); ?>">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
        <div class="form-group">
            <label class="form-label">Full Name <span class="required">*</span></label>
            <input type="text" name="name" class="form-input"
                   value="<?php echo e(old('name', $customer['name'])); ?>" required>
        </div>
        <div class="form-group">
            <label class="form-label">Phone Number</label>
            <input type="tel" name="phone" class="form-input"
                   value="<?php echo e(old('phone', $customer['phone'] ?? '')); ?>">
        </div>
        <div class="form-group">
            <label class="form-label">Address</label>
            <input type="text" name="address" class="form-input"
                   value="<?php echo e(old('address', $customer['address'] ?? '')); ?>">
        </div>
        <div style="display:flex;gap:10px;">
            <button type="submit" class="btn btn-primary" style="flex:1;">Save Changes</button>
            <a href="<?php echo e(route('customers.show', $customer['_id'])); ?>" class="btn btn-outline" style="flex:1;text-align:center;">Cancel</a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\milkbook-laravel\resources\views/customers/edit.blade.php ENDPATH**/ ?>