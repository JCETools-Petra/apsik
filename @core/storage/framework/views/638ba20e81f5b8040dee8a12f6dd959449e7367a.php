<div class="preloader" id="preloader">
    <div class="preloader-inner">
        <?php if(!empty(get_static_option('preloader_cacncel_button'))): ?>
        <button class="cancel-preloader" onclick="disablePreloader()"><?php echo e(__('Cancel Preloader')); ?></button>
        <?php endif; ?>
        <?php $site_preloader = get_static_option('preloader_default') ?>

        <?php if($site_preloader == '1'): ?>
        <div class="lds-circle"><div></div></div>
        <?php elseif($site_preloader == '2'): ?>
            <div class="lds-dual-ring"></div>
        <?php elseif($site_preloader == '3'): ?>
            <div class="lds-facebook"><div></div><div></div><div></div></div>
        <?php elseif($site_preloader == '4'): ?>
            <div class="lds-heart"><div></div></div>
        <?php elseif($site_preloader == '5'): ?>
            <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
        <?php elseif($site_preloader == '6'): ?>
            <div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
        <?php elseif($site_preloader == '7'): ?>
            <div class="lds-default"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
        <?php elseif($site_preloader == '8'): ?>
            <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
        <?php elseif($site_preloader == '9'): ?>
            <div class="lds-grid"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
        <?php elseif($site_preloader == '10'): ?>
            <div class="lds-hourglass"></div>
        <?php elseif($site_preloader == '11'): ?>
            <div class="lds-ripple"><div></div><div></div></div>
        <?php elseif($site_preloader == '12'): ?>
            <div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
        <?php endif; ?>
    </div>
</div>
<script>
    function disablePreloader() {
        document.querySelector('#preloader').remove();
    }
</script>
<?php /**PATH /home/apsx2353/public_html/@core/resources/views/frontend/partials/preloader/preloader-dynamic.blade.php ENDPATH**/ ?>