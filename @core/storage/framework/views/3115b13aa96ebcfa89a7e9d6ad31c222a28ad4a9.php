<?php $__env->startSection('site-title'); ?>
    <?php echo e(__('License Settings')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12 mt-5">
                <?php echo $__env->make('backend.partials.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.error-msg','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('error-msg'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title"><?php echo e(__("License Settings")); ?>

                        <button class="btn btn-sm btn-info"
                                style="padding: 5px; margin-left: 20px"
                                data-toggle="modal"
                                data-target="#licenseRequestModal"
                        ><?php echo e(__("Get License Key")); ?></button></h4>
                        <?php if('verified' == get_static_option('item_license_status')): ?>
                            <div class="alert alert-success"><?php echo e(__('Your Application is Registered')); ?></div>
                        <?php endif; ?>
                        <form action="<?php echo e(route('admin.general.license.settings')); ?>" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="form-group">
                                <label for="site_license_key"><?php echo e(__('License Key')); ?></label>
                                <input type="text" name="site_license_key"  class="form-control" value="<?php echo e(get_static_option('site_license_key')); ?>" >
                                <small><?php echo e(__("enter license key, which you get in your email after verify your license while install or you can get your license by click on \"Get License Key\", then system will send you a license code into your email, check your email inbox and spam folder as well. ")); ?></small>
                            </div>
                            <div class="form-group">
                                <label for="envato_username"><?php echo e(__('Envato Username')); ?></label>
                                <input type="text" class="form-control"  name="envato_username" value="<?php echo e(get_static_option("license_username")); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4"><?php echo e(__('Submit Information')); ?></button>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="licenseRequestModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo e(__('Request for license key...')); ?></h5>
                    <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                </div>
                <form action="<?php echo e(route("admin.general.license.key.generate")); ?>" id="user_password_change_modal_form" method="post" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="email"><?php echo e(__('Your Email')); ?></label>
                            <input type="email" class="form-control" name="email" value="<?php echo e(get_static_option("license_email")); ?>">
                            <small><?php echo e(__("Make sure you have given valid email, we will send you license key for enable one click update, We'll email you script updates - no spam, just the good stuff!")); ?> 🌟✉️</small>
                        </div>
                        <div class="form-group">
                            <label for="envato_username"><?php echo e(__('Envato Username')); ?></label>
                            <input type="text" class="form-control"  name="envato_username" value="<?php echo e(get_static_option("license_username")); ?>">
                        </div>
                        <div class="form-group">
                            <label for="envato_purchase_code"><?php echo e(__('Envato Purchase code')); ?></label>
                            <input type="text" class="form-control" name="envato_purchase_code" value="<?php echo e(get_static_option("license_purchase_code")); ?>">
                            <small><?php echo e(__('follow this article to know how you will get your envato purchase code for this script')); ?>

                                <a href="https://xgenious.com/where-can-i-find-my-purchase-code-at-codecanyon/" target="_blank"><?php echo e(__('how to get envato purchase code')); ?></a></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo e(__('Close')); ?></button>
                        <button id="update" type="submit" class="btn btn-primary"><?php echo e(__('Submit')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.admin-master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/apsx2353/public_html/@core/resources/views/backend/general-settings/license-settings.blade.php ENDPATH**/ ?>