<!DOCTYPE html>
<html lang="<?php echo e(get_default_language()); ?>" dir="<?php echo e(get_user_lang_direction()); ?>">
<head>
<?php if(!empty(get_static_option('site_google_analytics'))): ?>
    <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async
                src="https://www.googletagmanager.com/gtag/js?id=<?php echo e(get_static_option('site_google_analytics')); ?>"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());
            gtag('config', "<?php echo e(get_static_option('site_google_analytics')); ?>");
        </script>
 <?php endif; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta property="og:title"  content="<?php echo e(filter_static_option_value('site_'.$user_select_lang_slug.'_title',$global_static_field_data)); ?>" />
    <?php echo render_og_meta_image_by_attachment_id(filter_static_option_value('og_meta_image_for_site',$global_static_field_data)); ?>

    <title><?php echo e(filter_static_option_value('site_'.$user_select_lang_slug.'_title',$global_static_field_data)); ?> - <?php echo e(filter_static_option_value('site_'.$user_select_lang_slug.'_tag_line',$global_static_field_data)); ?></title>
    <meta name="description" content="<?php echo e(filter_static_option_value('site_meta_'.$user_select_lang_slug.'_description',$global_static_field_data)); ?>">
    <meta name="tags" content="<?php echo e(filter_static_option_value('site_meta_'.$user_select_lang_slug.'_tags',$global_static_field_data)); ?>">

    <?php echo render_favicon_by_id(filter_static_option_value('site_favicon',$global_static_field_data)); ?>

    <?php echo load_google_fonts(); ?>

    <link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/bootstrap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/fontawesome.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/dynamic-style.css')); ?>">
    <style>
        :root {
            --main-color-one: <?php echo e(get_static_option('site_color')); ?>;
            --secondary-color: <?php echo e(get_static_option('site_main_color_two')); ?>;
            --heading-color: <?php echo e(get_static_option('site_heading_color')); ?>;
            --paragraph-color: <?php echo e(get_static_option('site_paragraph_color')); ?>;
            <?php $heading_font_family = !empty(get_static_option('heading_font')) ? get_static_option('heading_font_family') :  get_static_option('body_font_family') ?>
             --heading-font: "<?php echo e($heading_font_family); ?>", sans-serif;
            --body-font: "<?php echo e(get_static_option('body_font_family')); ?>", sans-serif;
        }
    </style>
    <style>
        .maintenance-page-content-area {
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            z-index: 0;
            background-size: cover;
            background-position: center;
        }

        .maintenance-page-content-area:after {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            z-index: -1;
            content: '';
        }

        .page-content-wrap {
            text-align: center;
        }

        .page-content-wrap .logo-wrap {
            margin-bottom: 30px;
        }

        .page-content-wrap .maintain-title {
            font-size: 45px;
            font-weight: 700;
            color: #fff;
            line-height: 50px;
            margin-bottom: 20px;
        }

        .page-content-wrap p {
            font-size: 16px;
            line-height: 28px;
            color: rgba(255, 255, 255, .7);
            font-weight: 400;
        }

        .page-content-wrap .subscriber-form {
            position: relative;
            z-index: 0;
            max-width: 500px;
            margin: 0 auto;
            margin-top: 40px;
        }

        .page-content-wrap .subscriber-form .submit-btn {
            position: absolute;
            right: 0;
            bottom: 0;
            width: 60px;
            height: 50px;
            text-align: center;
            border: none;
            background-color: var(--main-color-one);
            color: #fff;
            border-top-right-radius: 5px;
            border-bottom-right-radius: 5px;
        }

        .page-content-wrap .subscriber-form .form-group .form-control {
            height: 50px;
            padding: 0 20px;
            padding-right: 80px;
        }
        .counterdown-wrap.event-page #event_countdown .wrapper{
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-height: 120px;
        }
        .counterdown-wrap.event-page #event_countdown {
            display: flex;
            margin-bottom: 30px
        }

        .counterdown-wrap.event-page #event_countdown > div {
            width: calc(100% / 4);
            margin: 5px;
            text-align: center;
            padding: 10px 10px;
            border: 2px dashed rgba(255,255,255,.5);
        }

        .counterdown-wrap.event-page #event_countdown > div .label {
            display: block;
            text-transform: capitalize;
            font-size: 14px;
            color: rgba(255, 255, 255, .8);
            font-weight: 500;
            line-height: 20px
        }

        .counterdown-wrap.event-page #event_countdown > div .time {
            font-size: 30px;
            font-weight: 700;
            color: #fff
        }
    </style>
    <?php echo $__env->yieldContent('style'); ?>
    <?php if(!empty(get_static_option('site_rtl_enabled')) || get_user_lang_direction() === 'rtl'): ?>
        <link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/rtl.css')); ?>">
    <?php endif; ?>
    <?php if(request()->is('blog/*') || request()->is('work/*') || request()->is('service/*')): ?>
        <?php echo $__env->yieldContent('og-meta'); ?>
        <title><?php echo $__env->yieldContent('site-title'); ?></title>
    <?php elseif(request()->is('about') || request()->is('service') || request()->is('work') || request()->is('team') || request()->is('faq') || request()->is('blog') || request()->is('contact') || request()->is('p/*') || request()->is('blog/*') || request()->is('services/*')): ?>
        <title><?php echo $__env->yieldContent('site-title'); ?> - <?php echo e(get_static_option('site_'.$user_select_lang_slug.'_title')); ?> </title>
    <?php else: ?>
        <title><?php echo e(get_static_option('site_'.$user_select_lang_slug.'_title')); ?>

            - <?php echo e(get_static_option('site_'.$user_select_lang_slug.'_tag_line')); ?></title>
    <?php endif; ?>
</head>
<body>

<div class="maintenance-page-content-area"
     <?php echo render_background_image_markup_by_attachment_id(get_static_option('maintain_page_background_image')); ?>

>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="maintenance-page-inner-content">
                    <div class="page-content-wrap">
                        <div class="logo-wrap">
                            <?php echo render_image_markup_by_attachment_id(get_static_option('maintain_page_logo')); ?>

                        </div>
                        <h2 class="maintain-title"><?php echo e(get_static_option('maintain_page_'.$user_select_lang_slug.'_title')); ?></h2>
                        <p><?php echo e(get_static_option('maintain_page_'.$user_select_lang_slug.'_description')); ?></p>

                        <div class="counterdown-wrap event-page">
                            <div id="event_countdown"></div>
                        </div>
                        <div class="subscriber-form">
                            <?php echo $__env->make('backend.partials.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php echo $__env->make('backend.partials.error', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <div class="newsletter-form-wrap">
                                <div class="form-message-show"></div>
                                <form action="<?php echo e(route('frontend.subscribe.newsletter')); ?>" method="post"
                                      enctype="multipart/form-data">
                                    <?php echo csrf_field(); ?>
                                    <div class="form-group">
                                        <input type="email" name="email" placeholder="<?php echo e(__('Enter your email')); ?>"
                                               class="form-control">
                                    </div>
                                    <button type="submit" class="submit-btn"><i class="fas fa-paper-plane"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo e(asset('assets/frontend/js/jquery.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/frontend/js/jquery-migrate.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/frontend/js/bootstrap.bundle.min.js')); ?>"></script>

<?php echo $__env->make('frontend.partials.twakto', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script src="<?php echo e(asset('assets/common/js/countdown.jquery.js')); ?>"></script>
<script>
    var ev_offerTime = "<?php echo e(get_static_option('maintain_page_countdown')); ?>";
    var ev_year = ev_offerTime.substr(0, 4);
    var ev_month = ev_offerTime.substr(5, 2);
    var ev_day = ev_offerTime.substr(8, 2);

    if (ev_offerTime) {
        $('#event_countdown').countdown({
            year: ev_year,
            month: ev_month,
            day: ev_day,
            labels: true,
            labelText: {
                'days': "<?php echo e(__('days')); ?>",
                'hours': "<?php echo e(__('hours')); ?>",
                'minutes': "<?php echo e(__('min')); ?>",
                'seconds': "<?php echo e(__('sec')); ?>",
            }
        });
    }
</script>
<!--Start of Tawk.to Script-->
<script>
    $(document).ready(function (){
        "use strict";

        $(document).on('click', '.newsletter-form-wrap .submit-btn', function (e) {
            e.preventDefault();
            var email = $('.newsletter-form-wrap input[type="email"]').val();
            var newsCont = $('.newsletter-widget .form-message-show,.newsletter-form-wrap .form-message-show')

            newsCont.html('');

            $.ajax({
                url: "<?php echo e(route('frontend.subscribe.newsletter')); ?>",
                type: "POST",
                data: {
                    _token: "<?php echo e(csrf_token()); ?>",
                    email: email
                },
                success: function (data) {
                    newsCont.html('<div class="alert alert-success">' + data + '</div>');
                },
                error: function (data) {
                    var errors = data.responseJSON.errors;
                    newsCont.html('<div class="alert alert-danger">' + errors.email[0] + '</div>');
                }
            });
        });

    });
</script>


</body>

</html>
<?php /**PATH /home/apsx2353/public_html/@core/resources/views/frontend/maintain.blade.php ENDPATH**/ ?>