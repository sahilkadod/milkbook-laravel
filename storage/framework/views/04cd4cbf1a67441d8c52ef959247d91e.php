<?php $__env->startSection('title', 'Login'); ?>
<?php $__env->startSection('subtitle', 'Sign in to your account'); ?>

<?php $__env->startSection('content'); ?>
<form method="POST" action="<?php echo e(route('login.post')); ?>">
    <?php echo csrf_field(); ?>
    <div class="form-group">
        <label class="form-label">Phone Number</label>
        <input type="tel" name="phone" class="form-input" placeholder="Enter your phone number"
               value="<?php echo e(old('phone')); ?>" required autofocus>
    </div>
    <div class="form-group">
        <label class="form-label">Password</label>
        <div class="input-password">
            <input type="password" name="password" class="form-input" id="passwordInput"
                   placeholder="Enter your password" required>
            <button type="button" class="btn-eye" onclick="togglePwd()">👁</button>
        </div>
    </div>
    <button type="submit" class="btn btn-primary btn-block">Login</button>
    <div class="auth-links">
        <a href="<?php echo e(route('forgot')); ?>">Forgot Password?</a>
        <span>·</span>
        <a href="<?php echo e(route('register')); ?>">Create account</a>
    </div>
</form>
<script>
function togglePwd() {
    const i = document.getElementById('passwordInput');
    i.type = i.type === 'password' ? 'text' : 'password';
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\milkbook-laravel\resources\views/auth/login.blade.php ENDPATH**/ ?>