<?php $__env->startSection('title', 'My Profile'); ?>
<?php $__env->startSection('topbar-title', 'My Profile'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-card" style="max-width:480px;margin:0 auto;">
    <div class="profile-avatar-wrap">
        <div class="profile-avatar"><?php echo e(strtoupper(substr($user->name, 0, 1))); ?></div>
        <div class="profile-name"><?php echo e($user->name); ?></div>
        <div class="profile-phone"><?php echo e($user->phone); ?></div>
    </div>

    <form method="POST" action="<?php echo e(route('profile.update')); ?>">
        <?php echo csrf_field(); ?>
        <div class="form-group">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-input" value="<?php echo e(old('name', $user->name)); ?>" required>
        </div>
        <div class="form-group">
            <label class="form-label">Phone Number</label>
            <input type="tel" name="phone" class="form-input" value="<?php echo e(old('phone', $user->phone)); ?>" required>
        </div>
        <hr class="divider">
        <p class="section-hint">Leave password fields blank to keep current password</p>
        <div class="form-group">
            <label class="form-label">New Password</label>
            <input type="password" name="password" class="form-input" placeholder="New password (optional)" minlength="6">
        </div>
        <div class="form-group">
            <label class="form-label">Confirm New Password</label>
            <input type="password" name="password_confirmation" class="form-input" placeholder="Confirm new password">
        </div>
        <button type="submit" class="btn btn-primary btn-block">Save Changes</button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\milkbook-laravel\resources\views/auth/profile.blade.php ENDPATH**/ ?>