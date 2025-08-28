<?php $__env->startSection('site-title'); ?>
    <?php echo e(__('Check Update')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12 mt-5">
                <?php echo $__env->make('backend.partials.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title"> <?php echo e(__("Check Update")); ?></h4>
                        <button type="button" class="btn btn-primary mt-4 pr-4 pl-4" id="click_for_check_update"> <i class="fas fa-spinner fa-spin d-none"></i> <?php echo e(__('Click to check For Update')); ?></button>

                        <div id="update_notice_wrapper" class="d-none text-center">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.media.markup','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('media.markup'); ?>
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
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
        (function($){
            "use strict";
            $(document).ready(function() {
                //todo write code
                $("body").on("click","#update_download_and_run_update",function (e){
                    e.preventDefault();

                    var el = $(this);
                    el.children().removeClass('d-none');

                    if(el.attr("disabled") != undefined && el.attr("disabled") === "disabled"){
                        return;
                    }
                    el.attr("disabled",true);
                    $.ajax({
                        url: el.attr("data-action"),
                        type: "POST",
                        data: {
                            _token : "<?php echo e(csrf_token()); ?>",
                            version: el.attr("data-version")
                        },
                        success: function (data){
                            console.log(data);
                            el.children().addClass('d-none');
                            if(data.msg != undefined && data.msg != ""){
                                el.text(data.msg).removeClass("btn-warning").addClass("btn-"+data.type);
                            }
                        },
                        error: function (error) {
                            console.log(error)
                        }
                    });

                });


                $(document).on("click","#click_for_check_update",function (e){
                    e.preventDefault();
                    var el = $(this);
                    el.children().removeClass('d-none');
                    el.attr("disabled",true);
                    $.ajax({
                        url: "<?php echo e(route('admin.general.update.version.check')); ?>",
                        type: "GET",
                        success: function (data){
                            el.children().addClass('d-none');
                            if(data.markup != ""){
                                $("#update_notice_wrapper").append(data.markup);
                            }else if(data.msg != ""){
                                $("#update_notice_wrapper").append("<div class='alert alert-"+data.type+"'>"+data.msg+"</div>");
                            }
                            $("#update_notice_wrapper").removeClass('d-none');
                            el.hide();
                        },
                        error: function (error) {
                            console.log(error)
                        }
                    });
                });



            });


        }(jQuery));
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.admin-master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/apsx2353/public_html/@core/resources/views/backend/general-settings/check-update.blade.php ENDPATH**/ ?>