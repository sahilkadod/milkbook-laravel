<?php $__env->startSection('title', 'Reset Password'); ?>
<?php $__env->startSection('subtitle', 'Reset your password using your phone & date of birth'); ?>

<?php $__env->startSection('content'); ?>
<form method="POST" action="<?php echo e(route('forgot.post')); ?>">
    <?php echo csrf_field(); ?>
    <div class="form-group">
        <label class="form-label">Phone Number</label>
        <input type="tel" name="phone" class="form-input" placeholder="Registered phone number"
               value="<?php echo e(old('phone')); ?>" required>
    </div>
    <div class="form-group">
        <label class="form-label">Date of Birth</label>
        <input type="date" name="dob" class="form-input" value="<?php echo e(old('dob')); ?>" required>
    </div>
    <div class="form-group">
        <label class="form-label">New Password</label>
        <div class="input-password">
            <input type="password" name="password" id="pwd" class="form-input"
                   placeholder="Minimum 6 characters" required minlength="6">
            <button type="button" class="btn-eye" onclick="togglePwd()">👁</button>
        </div>
    </div>
    <div class="form-group">
        <label class="form-label">Confirm New Password</label>
        <input type="password" name="password_confirmation" class="form-input"
               placeholder="Re-enter new password" required>
    </div>
    <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
    <div class="auth-links">
        <a href="<?php echo e(route('login')); ?>">← Back to Login</a>
    </div>
</form>
<script>
function togglePwd() {
    const i = document.getElementById('pwd');
    i.type = i.type === 'password' ? 'text' : 'password';
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\milkbook-laravel\resources\views/auth/forgot.blade.php ENDPATH**/ ?>