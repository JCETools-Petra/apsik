<?php $__env->startSection('content'); ?>
    <div class="login-area">
        <div class="container">
            <div class="login-box ptb--100">

                <form method="POST" action="<?php echo e(route('admin.reset.password.change')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="login-form-head">
                        <h4><?php echo e(__('Reset Password')); ?></h4>
                        <p><?php echo e(__('Hello there, Here you can change your password')); ?></p>
                    </div>
                    <?php echo $__env->make('backend.partials.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <div class="login-form-body">

                        <input type="hidden" name="token" value="<?php echo e($token); ?>">
                        <div class="form-gp focused">
                            <label for="username"><?php echo e(__('Username')); ?></label>
                            <input type="text" id="username" readonly value="<?php echo e($username); ?>" name="username">
                            <i class="ti-email"></i>
                        </div>
                        <div class="form-gp">
                            <label for="password"><?php echo e(__('Password')); ?></label>
                            <input type="password" id="password" name="password">
                            <i class="ti-lock"></i>
                        </div>
                        <div class="form-gp">
                            <label for="password_confirmation"><?php echo e(__('Confirm Password')); ?></label>
                            <input type="password" id="password_confirmation" name="password_confirmation">
                            <i class="ti-lock"></i>
                        </div>
                        <div class="submit-btn-area">
                            <button id="form_submit" type="submit"><?php echo e(__('Reset Password')); ?> <i class="ti-arrow-right"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.login-screens', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/apsx2353/public_html/@core/resources/views/auth/admin/reset-password.blade.php ENDPATH**/ ?>