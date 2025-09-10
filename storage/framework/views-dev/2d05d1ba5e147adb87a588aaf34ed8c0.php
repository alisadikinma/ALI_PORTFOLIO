<?php $__env->startSection('isi'); ?>
<!-- Hero Section -->

<?php if(session('success')): ?>
<div id="successAlert"
    class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 max-w-md w-full mx-auto px-4 py-3 bg-green-600 text-white font-medium rounded-xl shadow-lg flex items-center justify-between gap-4 animate-fade-in">
    <span><?php echo e(session('success')); ?></span>
    <button id="closeAlertBtn" class="text-white hover:text-gray-200 focus:outline-none" aria-label="Close alert">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const alert = document.getElementById('successAlert');
        const closeBtn = document.getElementById('closeAlertBtn');

        if (alert && closeBtn) {
            closeBtn.addEventListener('click', () => {
                alert.classList.add('animate-fade-out');
                setTimeout(() => {
                    alert.remove();
                }, 300);
            });

            setTimeout(() => {
                if (alert) {
                    alert.classList.add('animate-fade-out');
                    setTimeout(() => {
                        alert.remove();
                    }, 300);
                }
            }, 5000);
        }
    });
</script>

<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translate(-50%, -20px);
        }
        to {
            opacity: 1;
            transform: translate(-50%, 0);
        }
    }

    @keyframes fade-out {
        from {
            opacity: 1;
            transform: translate(-50%, 0);
        }
        to {
            opacity: 0;
            transform: translate(-50%, -20px);
        }
    }

    .animate-fade-in {
        animation: fade-in 0.3s ease-in-out;
    }

    .animate-fade-out {
        animation: fade-out 0.3s ease-in-out;
    }
<style>
/* Custom CSS untuk hover effect social media icons */
.social-icon:hover svg {
    color: #000000 !important;
}
</style>

<section id="home"
    class="w-full max-w-screen-2xl mx-auto px-4 sm:px-6 py-8 sm:py-14 flex flex-col items-center gap-8 sm:gap-16">
    <div class="w-full flex flex-col sm:flex-row items-center gap-8 sm:gap-32">
        <img src="<?php echo e(asset('favicon/' . $konf->favicon_setting)); ?>" alt="Profile image"
            class="w-full max-w-[300px] sm:max-w-[536px] h-auto rounded-2xl" />
        <div class="flex flex-col gap-6 sm:gap-8">
            <div class="flex flex-col gap-4 sm:gap-6">
                <div class="flex items-center gap-4 sm:gap-6">
                    <div class="w-12 sm:w-20 h-0.5 bg-yellow-400"></div>
                    <div class="text-yellow-400 text-sm sm:text-base font-semibold uppercase leading-normal">
                        <?php echo e($konf->profile_title); ?>

                    </div>
                </div>
                <h1 class="text-4xl sm:text-7xl font-bold leading-tight sm:leading-[80px] max-w-full sm:max-w-[648px]">
                    Hello bro, I'm<br />
                </h1>
            </div>
            <p class="text-gray-500 text-lg sm:text-2xl font-normal leading-7 sm:leading-9 max-w-full sm:max-w-[648px]">
                <?php echo $konf->tentang_setting; ?>

            </p>

            <div class="flex flex-col sm:flex-row gap-4">
                <a href="#contact" class="px-6 sm:px-8 py-3 sm:py-4 bg-yellow-400 rounded-lg flex items-center gap-3">
                    <span class="text-neutral-900 text-base sm:text-lg font-semibold capitalize leading-[40px] sm:leading-[64px]">
                        Say Hello
                    </span>
                </a>

                <a href="<?php echo e(url('portfolio')); ?>" target="_blank"
                    class="px-6 sm:px-8 py-3 sm:py-4 bg-slate-800/60 rounded-lg outline outline-1 outline-slate-500 flex items-center gap-3">
                    <span class="text-white text-base sm:text-lg font-semibold capitalize leading-[40px] sm:leading-[64px]">
                        View Portfolio
                    </span>
                    <svg class="w-5 sm:w-6 h-5 sm:h-6" fill="white" viewBox="0 0 24 24">
                        <path d="M12 4v16m8-8H4" />
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="w-full bg-neutral-900/40 flex flex-col items-center gap-4 sm:gap-6 md:gap-8 lg:gap-11">
        <div class="w-full h-0.5 outline outline-1 outline-neutral-900 outline-offset--1"></div>

        <div class="w-full px-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 sm:gap-8">
                <div class="flex flex-col items-center gap-2 sm:gap-3 md:gap-4 p-4 rounded-2xl">
                    <!-- Trade-up Icon -->
                    <svg class="w-8 sm:w-10 md:w-12 h-8 sm:h-10 md:h-12" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M36.6667 23.8332V14.6665H27.5" stroke="#FFD300" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M36.6666 14.6665L27.5 23.8332C25.8811 25.452 25.0726 26.2605 24.0808 26.3503C23.9158 26.365 23.7508 26.365 23.5858 26.3503C22.594 26.2587 21.7855 25.452 20.1666 23.8332C18.5478 22.2143 17.7393 21.4058 16.7475 21.316C16.5828 21.3011 16.4171 21.3011 16.2525 21.316C15.2606 21.4077 14.4521 22.2143 12.8333 23.8332L7.33331 29.3332" stroke="#FFD300" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <div class="text-yellow-400 text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-center">
                        <?php echo e($konf->years_experience); ?>

                    </div>
                    <div class="text-neutral-400 text-base sm:text-lg md:text-xl lg:text-2xl font-medium text-center">
                        Years Experience
                    </div>
                </div>

                <div class="flex flex-col items-center gap-2 sm:gap-3 md:gap-4 p-4 rounded-2xl">
                    <!-- People Icon -->
                    <svg class="w-8 sm:w-10 md:w-12 h-8 sm:h-10 md:h-12" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.89999 14.8502C9.89999 13.5374 10.4215 12.2783 11.3498 11.35C12.2781 10.4217 13.5372 9.9002 14.85 9.9002C16.1628 9.9002 17.4219 10.4217 18.3502 11.35C19.2785 12.2783 19.8 13.5374 19.8 14.8502C19.8 16.163 19.2785 17.4221 18.3502 18.3504C17.4219 19.2787 16.1628 19.8002 14.85 19.8002C13.5372 19.8002 12.2781 19.2787 11.3498 18.3504C10.4215 17.4221 9.89999 16.163 9.89999 14.8502ZM14.85 7.7002C12.9537 7.7002 11.1351 8.4535 9.79417 9.79438C8.45329 11.1353 7.69999 12.9539 7.69999 14.8502C7.69999 16.7465 8.45329 18.5651 9.79417 19.906C11.1351 21.2469 12.9537 22.0002 14.85 22.0002C16.7463 22.0002 18.5649 21.2469 19.9058 19.906C21.2467 18.5651 22 16.7465 22 14.8502C22 12.9539 21.2467 11.1353 19.9058 9.79438C18.5649 8.4535 16.7463 7.7002 14.85 7.7002ZM27.3614 33.3192C28.545 33.8032 30.0344 34.1002 31.9 34.1002C36.0382 34.1002 38.3262 32.6306 39.5318 30.9454C40.1621 30.0618 40.5566 29.032 40.678 27.9534C40.6888 27.8532 40.6961 27.7527 40.7 27.652V27.5002C40.7 27.0668 40.6146 26.6377 40.4488 26.2373C40.2829 25.837 40.0399 25.4732 39.7334 25.1667C39.427 24.8603 39.0632 24.6172 38.6628 24.4514C38.2625 24.2856 37.8334 24.2002 37.4 24.2002H27.214C27.742 24.8382 28.138 25.584 28.369 26.4002H37.4C37.6917 26.4002 37.9715 26.5161 38.1778 26.7224C38.3841 26.9287 38.5 27.2085 38.5 27.5002V27.619L38.489 27.729C38.4064 28.4269 38.1491 29.0928 37.741 29.665C37.0216 30.6748 35.4596 31.9002 31.9 31.9002C30.2896 31.9002 29.0884 31.6494 28.1886 31.282C28.0082 31.898 27.7464 32.5932 27.3614 33.3192ZM3.29999 28.6002C3.29999 27.4332 3.76356 26.3141 4.58872 25.4889C5.41388 24.6638 6.53304 24.2002 7.69999 24.2002H22C23.1669 24.2002 24.2861 24.6638 25.1113 25.4889C25.9364 26.3141 26.4 27.4332 26.4 28.6002V28.785L26.3956 28.873L26.3736 29.17C26.2177 30.5957 25.7113 31.9607 24.8996 33.1432C23.3574 35.3762 20.3786 37.4002 14.85 37.4002C9.32139 37.4002 6.34259 35.3762 4.80039 33.1454C3.98836 31.9623 3.48196 30.5965 3.32639 29.17C3.31384 29.0419 3.30503 28.9136 3.29999 28.785V28.6002ZM5.49999 28.7322V28.7718L5.51539 28.9544C5.63477 30.009 6.01096 31.0182 6.61099 31.8936C7.68239 33.4424 9.92859 35.2002 14.85 35.2002C19.7714 35.2002 22.0176 33.4424 23.089 31.8936C23.689 31.0182 24.0652 30.009 24.1846 28.9544C24.1934 28.8708 24.1978 28.8099 24.1978 28.7718L24.2 28.7344V28.6002C24.2 28.0167 23.9682 27.4571 23.5556 27.0446C23.143 26.632 22.5835 26.4002 22 26.4002H7.69999C7.11651 26.4002 6.55693 26.632 6.14435 27.0446C5.73177 27.4571 5.49999 28.0167 5.49999 28.6002V28.7322ZM28.6 16.5002C28.6 15.625 28.9477 14.7856 29.5665 14.1667C30.1854 13.5479 31.0248 13.2002 31.9 13.2002C32.7752 13.2002 33.6146 13.5479 34.2334 14.1667C34.8523 14.7856 35.2 15.625 35.2 16.5002C35.2 17.3754 34.8523 18.2148 34.2334 18.8336C33.6146 19.4525 32.7752 19.8002 31.9 19.8002C31.0248 19.8002 30.1854 19.4525 29.5665 18.8336C28.9477 18.2148 28.6 17.3754 28.6 16.5002ZM31.9 11.0002C30.4413 11.0002 29.0424 11.5797 28.0109 12.6111C26.9795 13.6426 26.4 15.0415 26.4 16.5002C26.4 17.9589 26.9795 19.3578 28.0109 20.3893C29.0424 21.4207 30.4413 22.0002 31.9 22.0002C33.3587 22.0002 34.7576 21.4207 35.7891 20.3893C36.8205 19.3578 37.4 17.9589 37.4 16.5002C37.4 15.0415 36.8205 13.6426 35.7891 12.6111C34.7576 11.5797 33.3587 11.0002 31.9 11.0002Z" fill="#FFD300"/>
                    </svg>
                    <div class="text-yellow-400 text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-center">
                        <?php echo e($konf->followers_count); ?>

                    </div>
                    <div class="text-neutral-400 text-base sm:text-lg md:text-xl lg:text-2xl font-medium text-center">
                        Followers
                    </div>
                </div>

                <div class="flex flex-col items-center gap-2 sm:gap-3 md:gap-4 p-4 rounded-2xl">
                    <!-- Briefcase Icon -->
                    <svg class="w-8 sm:w-10 md:w-12 h-8 sm:h-10 md:h-12" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M37.125 12.375H6.875C6.11561 12.375 5.5 12.9906 5.5 13.75V35.75C5.5 36.5094 6.11561 37.125 6.875 37.125H37.125C37.8844 37.125 38.5 36.5094 38.5 35.75V13.75C38.5 12.9906 37.8844 12.375 37.125 12.375Z" stroke="#FFD300" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M28.875 12.375V9.625C28.875 8.89565 28.5853 8.19618 28.0695 7.68046C27.5538 7.16473 26.8543 6.875 26.125 6.875H17.875C17.1457 6.875 16.4462 7.16473 15.9305 7.68046C15.4147 8.19618 15.125 8.89565 15.125 9.625V12.375" stroke="#FFD300" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M38.5 21.708C33.4852 24.6083 27.7931 26.1321 22 26.1252C16.2058 26.1403 10.5117 24.6159 5.5 21.708" stroke="#FFD300" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M19.9375 20.625H24.0625" stroke="#FFD300" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <div class="text-yellow-400 text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-center">
                        <?php echo e($konf->project_delivered); ?>

                    </div>
                    <div class="text-neutral-400 text-base sm:text-lg md:text-xl lg:text-2xl font-medium text-center">
                        Projects Delivered
                    </div>
                </div>

                <div class="flex flex-col items-center gap-2 sm:gap-3 md:gap-4 p-4 rounded-2xl">
                    <!-- Dollar Icon -->
                    <svg class="w-8 sm:w-10 md:w-12 h-8 sm:h-10 md:h-12" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22 38.0418C21.6368 38.0371 21.2898 37.8907 21.033 37.6338C20.7761 37.377 20.6297 37.03 20.625 36.6668V7.3335C20.625 6.96882 20.7699 6.61909 21.0277 6.36122C21.2856 6.10336 21.6353 5.9585 22 5.9585C22.3647 5.9585 22.7144 6.10336 22.9723 6.36122C23.2301 6.61909 23.375 6.96882 23.375 7.3335V36.6668C23.3703 37.03 23.2239 37.377 22.967 37.6338C22.7102 37.8907 22.3632 38.0371 22 38.0418Z" fill="#FFD300"/>
                        <path d="M24.75 34.3751H12.8334C12.4687 34.3751 12.119 34.2302 11.8611 33.9723C11.6032 33.7145 11.4584 33.3647 11.4584 33.0001C11.4584 32.6354 11.6032 32.2856 11.8611 32.0278C12.119 31.7699 12.4687 31.6251 12.8334 31.6251H24.75C25.9629 31.7331 27.1698 31.3619 28.1122 30.5908C29.0546 29.8198 29.6575 28.7103 29.7917 27.5001C29.6575 26.2898 29.0546 25.1803 28.1122 24.4093C27.1698 23.6382 25.9629 23.267 24.75 23.3751H19.25C18.2866 23.4372 17.3204 23.3084 16.4068 22.9962C15.4933 22.684 14.6503 22.1945 13.9264 21.5557C13.2025 20.917 12.6118 20.1416 12.1883 19.274C11.7648 18.4064 11.5167 17.4637 11.4584 16.5001C11.5167 15.5364 11.7648 14.5937 12.1883 13.7261C12.6118 12.8586 13.2025 12.0831 13.9264 11.4444C14.6503 10.8056 15.4933 10.3161 16.4068 10.0039C17.3204 9.69169 18.2866 9.56295 19.25 9.62505H29.3334C29.698 9.62505 30.0478 9.76992 30.3056 10.0278C30.5635 10.2856 30.7084 10.6354 30.7084 11.0001C30.7084 11.3647 30.5635 11.7145 30.3056 11.9723C30.0478 12.2302 29.698 12.3751 29.3334 12.3751H19.25C18.0372 12.267 16.8303 12.6382 15.8879 13.4093C14.9455 14.1803 14.3426 15.2898 14.2084 16.5001C14.3426 17.7103 14.9455 18.8198 15.8879 19.5908C16.8303 20.3619 18.0372 20.7331 19.25 20.6251H24.75C25.7135 20.5629 26.6797 20.6917 27.5933 21.0039C28.5068 21.3161 29.3497 21.8056 30.0737 22.4444C30.7976 23.0831 31.3883 23.8586 31.8118 24.7261C32.2353 25.5937 32.4834 26.5364 32.5417 27.5001C32.4834 28.4637 32.2353 29.4064 31.8118 30.274C31.3883 31.1416 30.7976 31.917 30.0737 32.5557C29.3497 33.1945 28.5068 33.684 27.5933 33.9962C26.6797 34.3084 25.7135 34.4372 24.75 34.3751Z" fill="#FFD300"/>
                    </svg>
                    <div class="text-yellow-400 text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-center">
                        <?php echo e($konf->cost_savings); ?>

                    </div>
                    <div class="text-neutral-400 text-base sm:text-lg md:text-xl lg:text-2xl font-medium text-center">
                        Cost Savings
                    </div>
                </div>

                <div class="flex flex-col items-center gap-2 sm:gap-3 md:gap-4 p-4 rounded-2xl">
                    <!-- Target Icon -->
                    <svg class="w-8 sm:w-10 md:w-12 h-8 sm:h-10 md:h-12" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22 40.3332C19.4639 40.3332 17.0805 39.8516 14.85 38.8885C12.6194 37.9254 10.6791 36.6194 9.02913 34.9707C7.37913 33.3219 6.07318 31.3816 5.1113 29.1498C4.14941 26.9181 3.66785 24.5347 3.66663 21.9998C3.66541 19.4649 4.14696 17.0816 5.1113 14.8498C6.07563 12.6181 7.38157 10.6778 9.02913 9.029C10.6767 7.38023 12.617 6.07428 14.85 5.11117C17.083 4.14806 19.4663 3.6665 22 3.6665C24.5336 3.6665 26.917 4.14806 29.15 5.11117C31.383 6.07428 33.3232 7.38023 34.9708 9.029C36.6184 10.6778 37.9249 12.6181 38.8905 14.8498C39.856 17.0816 40.337 19.4649 40.3333 21.9998C40.3296 24.5347 39.8481 26.9181 38.8886 29.1498C37.9292 31.3816 36.6232 33.3219 34.9708 34.9707C33.3184 36.6194 31.3781 37.926 29.15 38.8903C26.9219 39.8547 24.5385 40.3356 22 40.3332ZM22 36.6665C26.0944 36.6665 29.5625 35.2457 32.4041 32.404C35.2458 29.5623 36.6666 26.0943 36.6666 21.9998C36.6666 17.9054 35.2458 14.4373 32.4041 11.5957C29.5625 8.754 26.0944 7.33317 22 7.33317C17.9055 7.33317 14.4375 8.754 11.5958 11.5957C8.75413 14.4373 7.33329 17.9054 7.33329 21.9998C7.33329 26.0943 8.75413 29.5623 11.5958 32.404C14.4375 35.2457 17.9055 36.6665 22 36.6665ZM22 32.9998C18.9444 32.9998 16.3472 31.9304 14.2083 29.7915C12.0694 27.6526 11 25.0554 11 21.9998C11 18.9443 12.0694 16.3471 14.2083 14.2082C16.3472 12.0693 18.9444 10.9998 22 10.9998C25.0555 10.9998 27.6527 12.0693 29.7916 14.2082C31.9305 16.3471 33 18.9443 33 21.9998C33 25.0554 31.9305 27.6526 29.7916 29.7915C27.6527 31.9304 25.0555 32.9998 22 32.9998ZM22 29.3332C24.0166 29.3332 25.743 28.6151 27.1791 27.179C28.6152 25.7429 29.3333 24.0165 29.3333 21.9998C29.3333 19.9832 28.6152 18.2568 27.1791 16.8207C25.743 15.3846 24.0166 14.6665 22 14.6665C19.9833 14.6665 18.2569 15.3846 16.8208 16.8207C15.3847 18.2568 14.6666 19.9832 14.6666 21.9998C14.6666 24.0165 15.3847 25.7429 16.8208 27.179C18.2569 28.6151 19.9833 29.3332 22 29.3332ZM22 25.6665C20.9916 25.6665 20.1287 25.3078 19.4113 24.5903C18.6939 23.8729 18.3345 23.0094 18.3333 21.9998C18.3321 20.9903 18.6914 20.1274 19.4113 19.4112C20.1312 18.6949 20.9941 18.3356 22 18.3332C23.0058 18.3307 23.8694 18.6901 24.5905 19.4112C25.3116 20.1323 25.6703 20.9952 25.6666 21.9998C25.663 23.0045 25.3042 23.868 24.5905 24.5903C23.8767 25.3127 23.0132 25.6714 22 25.6665Z" fill="#FFD300"/>
                    </svg>
                    <div class="text-yellow-400 text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-center">
                        <?php echo e($konf->success_rate); ?>

                    </div>
                    <div class="text-neutral-400 text-base sm:text-lg md:text-xl lg:text-2xl font-medium text-center">
                        Success Rate
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full h-0.5 outline outline-1 outline-neutral-900 outline-offset--1"></div>
    </div>
</section>

<!-- About Section - FIXED -->
<?php if($konf->about_section_active ?? true): ?>
<section id="about" class="w-full max-w-screen-2xl mx-auto px-6 py-12">
    <div class="flex flex-col lg:flex-row justify-between items-center gap-12">
        
        <!-- Left Content -->
        <div class="flex flex-col gap-8 max-w-2xl flex-1 order-2 lg:order-1">
            <div class="flex flex-col gap-6">
                <h2 class="text-3xl lg:text-4xl font-bold text-white leading-snug">
                    <?php echo e($konf->about_section_title ?? 'With over 16+ years of experience in manufacturing and technology'); ?>

                </h2>
                <?php if(isset($konf->about_section_subtitle) && $konf->about_section_subtitle): ?>
                <h3 class="text-xl lg:text-2xl font-semibold text-yellow-400">
                    <?php echo e($konf->about_section_subtitle); ?>

                </h3>
                <?php endif; ?>
                <div class="text-gray-400 text-lg leading-relaxed">
                    <?php echo $konf->about_section_description ?? "I've dedicated my career to bridging the gap between traditional manufacturing and cutting-edge AI solutions.<br><br>From my early days as a Production Engineer to becoming an AI Generalist, I've consistently focused on delivering measurable business impact through innovative technology solutions."; ?>

                </div>
            </div>
        </div>

        <!-- Right Image - FIXED POSITION & BALANCED SIZE -->
        <div class="flex-1 flex items-stretch justify-center order-1 lg:order-2">
            <div class="w-full max-w-lg lg:max-w-xl xl:max-w-2xl p-6 bg-slate-800 rounded-2xl outline outline-2 outline-orange-400">
                <?php if(isset($konf->about_section_image) && $konf->about_section_image && file_exists(public_path('images/about/' . $konf->about_section_image))): ?>
                    <img src="<?php echo e(asset('images/about/' . $konf->about_section_image)); ?>" 
                         alt="About Section Image" 
                         class="w-full h-full object-cover rounded-xl" />
                <?php elseif(isset($award) && $award->count() > 0): ?>
                    <!-- Company Logos Grid from Awards -->
                    <div class="grid grid-cols-2 gap-6 w-full h-full place-content-center">
                        <?php $__currentLoopData = $award->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $award_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="p-6 bg-slate-700/60 rounded-xl outline outline-1 outline-slate-600 flex items-center justify-center aspect-square min-h-[120px]">
                            <img src="<?php echo e(asset('file/award/' . $award_item->gambar_award)); ?>" 
                                 alt="<?php echo e($award_item->nama_award); ?>" 
                                 class="max-w-full max-h-full object-contain opacity-80" />
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <!-- Default placeholder -->
                    <div class="text-orange-400 text-4xl text-center flex flex-col items-center justify-center h-full">
                        Put image here
                        <div class="mt-6">
                            <svg class="w-20 h-20 mx-auto" fill="currentColor" viewBox="0 0 100 100">
                                <path d="M50 10 L90 50 L50 90 Z" stroke="currentColor" stroke-width="2"/>
                            </svg>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Awards & Recognition Section - REDESIGNED -->
<?php if($konf->awards_section_active ?? true): ?>
<section id="awards" class="w-full py-16 bg-gradient-to-b from-slate-900 via-slate-800 to-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <!-- Header -->
        <div class="text-center mb-12">
            <h2 class="text-yellow-400 text-5xl sm:text-6xl font-bold mb-4">
                Awards & Recognition
            </h2>
            <p class="text-gray-400 text-lg sm:text-xl max-w-3xl mx-auto">
                Innovative solutions that drive real business impact and transformation
            </p>
        </div>

        <?php if(isset($award) && $award->count() > 0): ?>
        <!-- Awards Grid - NEW DESIGN -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__currentLoopData = $award; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
            // Logo colors based on company
            $logoConfigs = [
                'nextdev' => ['bg' => '#4A90E2', 'text' => 'NextDev', 'subtitle' => 'Telkomsel • 2018', 'color' => 'blue'],
                'alibaba' => ['bg' => '#FF6A00', 'text' => 'Alibaba', 'subtitle' => 'ALIBABA UNCTAD • 2019', 'color' => 'orange'],
                'google' => ['bg' => '#4285F4', 'text' => 'Google', 'subtitle' => 'GOOGLE • 2018', 'color' => 'blue'],
                'wild' => ['bg' => '#00C853', 'text' => 'Wild Card', 'subtitle' => 'FENOX • 2017', 'color' => 'green'],
                'fenox' => ['bg' => '#FF4444', 'text' => 'Fenox', 'subtitle' => 'FENOX • 2017', 'color' => 'red'],
                'bubu' => ['bg' => '#00D25B', 'text' => 'BUBU', 'subtitle' => 'BUBU.com • 2017', 'color' => 'green'],
                'grind' => ['bg' => '#4285F4', 'text' => 'Startup Grind', 'subtitle' => 'GOOGLE • 2024', 'color' => 'blue'],
                'default' => ['bg' => '#FFC107', 'text' => 'Award', 'subtitle' => date('Y'), 'color' => 'yellow']
            ];
            
            $logoKey = 'default';
            foreach(array_keys($logoConfigs) as $key) {
                if(stripos($row->nama_award, $key) !== false) {
                    $logoKey = $key;
                    break;
                }
            }
            
            $logoConfig = $logoConfigs[$logoKey];
            ?>
            
            <div class="award-card-modern group relative bg-slate-800/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 hover:border-slate-600 transition-all duration-300 p-8 cursor-pointer hover:transform hover:-translate-y-1" 
                 onclick="openAwardGallery(<?php echo e($row->id_award); ?>, '<?php echo e(addslashes($row->nama_award)); ?>')">
                
                <!-- Logo Icon -->
                <div class="mb-6">
                    <div class="w-20 h-20 rounded-2xl flex items-center justify-center shadow-xl" 
                         style="background: <?php echo e($logoConfig['bg']); ?>;">
                        <?php if($row->gambar_award && file_exists(public_path('file/award/' . $row->gambar_award))): ?>
                            <img src="<?php echo e(asset('file/award/' . $row->gambar_award)); ?>" 
                                 alt="<?php echo e($row->nama_award); ?>" 
                                 class="w-12 h-12 object-contain filter brightness-0 invert" />
                        <?php else: ?>
                            <!-- Default icons based on company -->
                            <?php if(stripos($row->nama_award, 'nextdev') !== false): ?>
                                <span class="text-white text-2xl font-bold">N</span>
                            <?php elseif(stripos($row->nama_award, 'google') !== false || stripos($row->nama_award, 'grind') !== false): ?>
                                <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                </svg>
                            <?php elseif(stripos($row->nama_award, 'alibaba') !== false): ?>
                                <span class="text-white text-3xl font-bold">Ali</span>
                            <?php elseif(stripos($row->nama_award, 'wild') !== false || stripos($row->nama_award, 'fenox') !== false): ?>
                                <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M21 6h-7.59l1.29-1.29a1 1 0 0 0-1.42-1.42l-3 3a1 1 0 0 0 0 1.42l3 3a1 1 0 0 0 1.42 0 1 1 0 0 0 0-1.42L13.41 8H21a1 1 0 0 0 0-2zM3 12a1 1 0 0 0 0 2h7.59l-1.29 1.29a1 1 0 0 0 0 1.42 1 1 0 0 0 1.42 0l3-3a1 1 0 0 0 0-1.42l-3-3a1 1 0 0 0-1.42 1.42L10.59 12z"/>
                                </svg>
                            <?php elseif(stripos($row->nama_award, 'bubu') !== false): ?>
                                <span class="text-white text-2xl font-bold">BUBU</span>
                            <?php else: ?>
                                <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
                                </svg>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Award Title -->
                <h3 class="text-white text-xl font-bold mb-2 leading-tight">
                    <?php echo e($row->nama_award); ?>

                </h3>
                
                <!-- Company & Year -->
                <p class="text-<?php echo e($logoConfig['color']); ?>-400 text-sm font-semibold mb-4 uppercase tracking-wide">
                    <?php echo e($logoConfig['subtitle']); ?>

                </p>
                
                <!-- Description -->
                <p class="text-gray-400 text-sm leading-relaxed mb-6">
                    <?php echo Str::limit(strip_tags($row->keterangan_award), 150, '...'); ?>

                </p>
                
                <!-- View Gallery Button -->
                <button class="flex items-center gap-2 text-gray-400 text-sm font-medium group-hover:text-white transition-colors">
                    <span>VIEW GALLERY</span>
                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        
        <?php else: ?>
        <!-- No Data State -->
        <div class="flex flex-col items-center justify-center py-16">
            <div class="text-yellow-400 text-6xl mb-4">🏆</div>
            <h3 class="text-white text-xl font-semibold mb-2">No Awards Yet</h3>
            <p class="text-gray-400 text-center max-w-md">
                We're building our track record of achievements and recognition. Stay tuned to see our upcoming awards and accomplishments!
            </p>
        </div>
        <?php endif; ?>
    </div>
</section>

<style>
/* Awards Section Custom Styles */
.award-card-modern {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.6) 0%, rgba(15, 23, 42, 0.8) 100%);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

.award-card-modern:hover {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8) 0%, rgba(15, 23, 42, 0.9) 100%);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
}

/* Custom color classes for companies */
.text-orange-400 { color: #fb923c; }
.text-blue-400 { color: #60a5fa; }
.text-green-400 { color: #4ade80; }
.text-red-400 { color: #f87171; }
.text-yellow-400 { color: #fbbf24; }
</style>

<!-- Award Gallery Modal -->
<div id="awardGalleryModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-slate-800 rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
        <div class="flex justify-between items-center p-6 border-b border-slate-600">
            <h3 id="awardGalleryTitle" class="text-xl font-bold text-white">Award Gallery</h3>
            <button onclick="closeAwardGallery()" class="text-gray-400 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div id="awardGalleryContent" class="p-6 overflow-y-auto max-h-[calc(90vh-100px)]">
            <!-- Gallery content will be loaded here -->
        </div>
    </div>
</div>

<script>
function openAwardGallery(awardId, awardName) {
    document.getElementById('awardGalleryTitle').textContent = `${awardName} - Gallery`;
    document.getElementById('awardGalleryModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Show loading
    document.getElementById('awardGalleryContent').innerHTML = `
        <div class="flex items-center justify-center py-12">
            <div class="text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-yellow-400 mx-auto mb-4"></div>
                <p class="text-gray-400">Loading gallery...</p>
            </div>
        </div>
    `;
    
    // Fetch gallery items
    fetch(`/gallery/${awardId}/items`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.items.length > 0) {
                let content = '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">';
                
                data.items.forEach(item => {
                    content += `
                        <div class="bg-slate-700 rounded-xl overflow-hidden hover:transform hover:scale-105 transition-all duration-300">
                            <div class="aspect-square relative">
                                ${item.type === 'image' ? 
                                    `<img src="${item.file_url}" alt="Gallery item" class="w-full h-full object-cover cursor-pointer" onclick="openImageModal('${item.file_url}', 'Gallery item')">` :
                                    `<iframe class="w-full h-full" src="https://www.youtube.com/embed/${extractYouTubeId(item.youtube_url)}" frameborder="0" allowfullscreen></iframe>`
                                }
                                <div class="absolute top-2 right-2">
                                    <span class="bg-black bg-opacity-60 text-white text-xs px-2 py-1 rounded">${item.type}</span>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                content += '</div>';
                document.getElementById('awardGalleryContent').innerHTML = content;
            } else {
                document.getElementById('awardGalleryContent').innerHTML = `
                    <div class="text-center py-12">
                        <div class="text-yellow-400 text-6xl mb-4">🖼️</div>
                        <h3 class="text-white text-xl font-semibold mb-2">No Gallery Items</h3>
                        <p class="text-gray-400">This award doesn't have any gallery items yet.</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('awardGalleryContent').innerHTML = `
                <div class="text-center py-12">
                    <div class="text-red-400 text-6xl mb-4">⚠️</div>
                    <h3 class="text-white text-xl font-semibold mb-2">Error Loading Gallery</h3>
                    <p class="text-gray-400">Failed to load gallery items. Please try again.</p>
                </div>
            `;
        });
}

function closeAwardGallery() {
    document.getElementById('awardGalleryModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function extractYouTubeId(url) {
    if (!url) return null;
    const regex = /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([^&\n?#]+)/;
    const match = url.match(regex);
    return match ? match[1] : null;
}

function openImageModal(imageUrl, title) {
    // Create image modal for full view
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-90 z-60 flex items-center justify-center p-4';
    modal.innerHTML = `
        <div class="relative max-w-full max-h-full">
            <img src="${imageUrl}" alt="${title}" class="max-w-full max-h-full object-contain">
            <button onclick="this.parentElement.parentElement.remove(); document.body.style.overflow = '';" class="absolute top-4 right-4 text-white bg-black bg-opacity-50 rounded-full p-2 hover:bg-opacity-75 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;
    document.body.appendChild(modal);
    document.body.style.overflow = 'hidden';
}

// Close modal when clicking outside
document.getElementById('awardGalleryModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAwardGallery();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAwardGallery();
    }
});
</script>
<?php endif; ?>
<!-- Services Section -->
<?php if(($konf->services_section_active ?? true) && isset($layanan) && $layanan->count() > 0): ?>
<section id="services" class="w-full max-w-screen-2xl mx-auto px-4 sm:px-6 py-8 sm:py-16">
    <!-- Header -->
    <div class="text-center mb-12">
        <h2 class="text-yellow-400 text-4xl sm:text-6xl font-bold mb-4">
            Services
        </h2>
        <p class="text-gray-400 text-lg sm:text-xl max-w-3xl mx-auto">
            Comprehensive AI and automation solutions for your business transformation
        </p>
    </div>

    <!-- Services Layout -->
    <div class="flex flex-col lg:flex-row gap-4 lg:gap-4 items-start">
        <!-- Left Side - Service Cards (30%) -->
        <div class="lg:w-3/10 xl:w-3/10 service-left-panel flex flex-col pl-4">
            <?php $__currentLoopData = $layanan->where('status', 'Active')->sortBy('sequence'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="service-card <?php echo e($index == 0 ? 'active' : ''); ?>" 
                 data-service-id="<?php echo e($row->id_layanan ?? $index); ?>"
                 data-service-type="<?php echo e(Str::slug($row->nama_layanan)); ?>"
                 data-image="<?php echo e(asset('file/layanan/' . $row->gambar_layanan)); ?>"
                 data-description="<?php echo htmlspecialchars($row->keterangan_layanan ?? '', ENT_QUOTES); ?>">
                <div class="service-icon">
                    <?php if($row->icon_layanan): ?>
                        <img src="<?php echo e(asset('file/layanan/icons/' . $row->icon_layanan)); ?>" alt="<?php echo e($row->nama_layanan); ?> icon" style="width: 28px; height: 28px; object-fit: contain;">
                    <?php else: ?>
                        <?php if(str_contains(strtolower($row->nama_layanan), 'gpt') || str_contains(strtolower($row->nama_layanan), 'custom')): ?>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                                <path d="M2 17l10 5 10-5"/>
                                <path d="M2 12l10 5 10-5"/>
                            </svg>
                        <?php elseif(str_contains(strtolower($row->nama_layanan), 'video')): ?>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polygon points="23 7 16 12 23 17 23 7"/>
                                <rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>
                            </svg>
                        <?php elseif(str_contains(strtolower($row->nama_layanan), 'visual') || str_contains(strtolower($row->nama_layanan), 'inspection')): ?>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        <?php elseif(str_contains(strtolower($row->nama_layanan), 'consultation') || str_contains(strtolower($row->nama_layanan), 'speaking')): ?>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                            </svg>
                        <?php else: ?>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                                <line x1="12" y1="22.08" x2="12" y2="12"/>
                            </svg>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <div class="service-content">
                    <h3 class="service-title"><?php echo e($row->nama_layanan); ?></h3>
                    <?php if($row->sub_nama_layanan): ?>
                    <p class="service-subtitle-main"><?php echo e($row->sub_nama_layanan); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Right Side - Content Display (70%) -->
        <div class="lg:w-7/10 xl:w-7/10 service-right-panel flex flex-col">
            <div class="service-display">
                <!-- Service Image -->
                <div class="service-image-container">
                    <img id="currentServiceImage" 
                         src="<?php echo e(asset('file/layanan/' . ($layanan->where('status', 'Active')->sortBy('sequence')->first()->gambar_layanan ?? 'default.jpg'))); ?>" 
                         alt="Service Image" 
                         class="service-main-image">
                </div>
                
                <!-- Service Description -->
                <!--div class="service-description">
                    <div id="currentServiceDescription" class="description-text">
                        Loading service description...
                    </div>
                </div-->
                
                <div class="service-action">
                    <a href="<?php echo e(url('/#contact')); ?>" class="request-quote-btn-services">
                       REQUEST QUOTE →
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>

<style>
/* Services Section Styles */
#services {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
    min-height: 800px;
}

/* Responsive Services Layout */
@media (min-width: 1024px) {
    .service-left-panel {
        width: 30% !important;
        flex: 0 0 30%;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        align-items: stretch;
        min-height: 500px;
        gap: 0.75rem;
        margin-top: 0;
        padding-top: 0;
        transform: translateY(32px);
    }
    
    .service-left-panel > .service-card {
        margin-bottom: 0.75rem;
    }
    
    .service-left-panel > .service-card:last-child {
        margin-bottom: 0;
    }
    
    .service-right-panel {
        width: 70% !important;
        flex: 0 0 70%;
        display: flex;
        flex-direction: column;
        justify-content: stretch;
        align-items: stretch;
        min-height: 500px;
    }
    
    .service-display {
        flex: 1;
        display: flex;
        flex-direction: column;
        height: 100%;
    }
}

.service-card {
    background: linear-gradient(145deg, rgba(30, 41, 59, 0.8), rgba(15, 23, 42, 0.6));
    border: 1px solid rgba(59, 130, 246, 0.2);
    border-radius: 16px;
    padding: 2rem 1.8rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    backdrop-filter: blur(10px);
    min-height: 150px;
    max-height: 180px;
    margin-right: 0.1rem;
}

.service-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(145deg, rgba(59, 130, 246, 0.1), rgba(16, 185, 129, 0.1));
    opacity: 0;
    transition: opacity 0.3s ease;
}

.service-card:hover,
.service-card.active {
    border-color: #fbbf24;
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(251, 191, 36, 0.3);
    background: linear-gradient(145deg, rgba(30, 41, 59, 0.9), rgba(15, 23, 42, 0.8));
}

.service-card:hover::before,
.service-card.active::before {
    opacity: 1;
}

.service-icon {
    width: 44px;
    height: 44px;
    background: linear-gradient(135deg, #fbbf24, #f59e0b);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    position: relative;
    z-index: 1;
    box-shadow: 0 4px 16px rgba(251, 191, 36, 0.4);
    margin-left: 0.5rem;
    margin-right: 0.5rem;
}

.service-icon svg {
    width: 28px;
    height: 28px;
    color: #1f2937;
}

.service-content {
    flex: 1;
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    min-height: 60px;
}

.service-title {
    color: #fbbf24;
    font-size: 1.5rem;
    font-weight: 800;
    margin-bottom: 0.375rem;
    line-height: 1.25;
}

.service-subtitle-main {
    color: #e2e8f0;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    line-height: 1.3;
    opacity: 0.9;
}

.service-subtitle {
    color: #94a3b8;
    font-size: 1rem;
    line-height: 1.4;
    margin: 0;
    opacity: 0.9;
}

.service-display {
    background: linear-gradient(145deg, rgba(30, 41, 59, 0.8), rgba(15, 23, 42, 0.6));
    border-radius: 20px;
    padding: 1.75rem;
    border: 1px solid rgba(59, 130, 246, 0.2);
    backdrop-filter: blur(10px);
    min-height: 450px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
}

.service-image-container {
    margin-bottom: 1.2rem;
    border-radius: 16px;
    overflow: hidden;
    background: #0f172a;
    position: relative;
    margin-top: 0;
}

.service-main-image {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Back to cover for full frame fit */
    transition: all 0.5s ease;
    border-radius: 16px;
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%);
}

.service-description {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 0.6rem;
    font-size: 1.2rem;
}

.description-text {
    color: #94a3b8;
    font-size: 1rem;
    line-height: 1.6;
    margin-bottom: 0.7rem;
    text-align: left;
}

.service-action {
    display: flex;
    justify-content: flex-start;
    margin-top: 1.5rem;
}

.request-quote-btn-services {
    background: linear-gradient(135deg, #fbbf24, #f59e0b);
    color: #1f2937;
    padding: 14px 28px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    box-shadow: 0 4px 16px rgba(251, 191, 36, 0.3);
}

.request-quote-btn-services:hover {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    transform: translateY(-2px);
    box-shadow: 0 8px 32px rgba(251, 191, 36, 0.4);
}

/* Responsive Design */
@media (max-width: 1024px) {
    /* Tablet and mobile: full width stacked */
    .service-left-panel,
    .service-right-panel {
        width: 100% !important;
        flex: 0 0 100%;
        transform: none;
        margin-top: 0;
        padding-top: 0;
    }
    
    .service-card {
        padding: 1.25rem;
    }
    
    .service-icon {
        width: 40px;
        height: 40px;
    }
    
    .service-icon svg {
        width: 20px;
        height: 20px;
    }
    
    .service-title {
        font-size: 1.125rem;
    }
    
    .service-main-image {
        height: 250px; /* Mobile optimization for 1080x608 */
    }
}

@media (max-width: 480px) {
    .service-main-image {
        height: 200px; /* Small mobile screens */
    }
}

@media (max-width: 768px) {
    #services {
        padding: 2rem 1rem;
    }
    
    .service-display {
        padding: 1.5rem;
    }
    
    .service-main-image {
        height: 200px;
    }
    
    .service-card {
        padding: 1rem;
    }
    
    .flex.flex-col.lg\:flex-row {
        flex-direction: column;
        gap: 2rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceCards = document.querySelectorAll('.service-card');
    const serviceImage = document.getElementById('currentServiceImage');
    const serviceDescription = document.getElementById('currentServiceDescription');
    
    // Add null checks to prevent errors
    if (!serviceCards.length) {
        return;
    }
    
    serviceCards.forEach((card, index) => {
        card.addEventListener('click', function() {
            // Remove active class from all cards
            serviceCards.forEach(c => c.classList.remove('active'));
            
            // Add active class to clicked card
            this.classList.add('active');
            
            // Get data attributes with fallback
            const newImage = this.dataset.image;
            let newDescription = this.dataset.description;
            const serviceId = this.dataset.serviceId;
            
            // Update image with fade effect - ADD NULL CHECKS
            if (newImage && serviceImage && newImage !== serviceImage.src) {
                serviceImage.style.opacity = '0';
                setTimeout(() => {
                    serviceImage.src = newImage;
                    serviceImage.onload = () => {
                        if (serviceImage) serviceImage.style.opacity = '1';
                    };
                    // Fallback in case onload doesn't fire
                    setTimeout(() => {
                        if (serviceImage) serviceImage.style.opacity = '1';
                    }, 200);
                }, 150);
            }
            
            // Update description - ADD NULL CHECKS
            if (serviceDescription) {
                if (newDescription && newDescription.trim() !== '') {
                    serviceDescription.style.opacity = '0';
                    setTimeout(() => {
                        // Decode HTML entities and render as HTML
                        const decodedDescription = newDescription
                            .replace(/&quot;/g, '"')
                            .replace(/&#039;/g, "'")
                            .replace(/&lt;/g, '<')
                            .replace(/&gt;/g, '>')
                            .replace(/&amp;/g, '&');
                        if (serviceDescription) {
                            serviceDescription.innerHTML = decodedDescription;
                            serviceDescription.style.opacity = '1';
                        }
                    }, 150);
                } else {
                    // Fallback descriptions only if database has no content
                    const fallbackDescriptions = [
                        'I provide tailored AI solutions and custom GPT models designed to meet your business needs and industry requirements. From understanding your challenges to delivering a solution, I make sure the AI tools we create truly support your goals and make your processes smarter.',
                        'Work smarter, not harder with intelligent automation solutions that streamline your business processes and eliminate repetitive tasks.',
                        'Turn AI into your creative weapon with custom GPT models and intelligent content generation systems tailored for your specific needs.',
                        'Where Strategy Meets Creativity - comprehensive content creation services that blend strategic thinking with creative execution.'
                    ];
                    
                    serviceDescription.style.opacity = '0';
                    setTimeout(() => {
                        if (serviceDescription) {
                            serviceDescription.innerHTML = fallbackDescriptions[index] || fallbackDescriptions[0];
                            serviceDescription.style.opacity = '1';
                        }
                    }, 150);
                }
            }
        });
    });
    
    // Initialize first service
    if (serviceCards.length > 0) {
        // Add a small delay to ensure DOM is ready
        setTimeout(() => {
            if (serviceCards[0]) {
                serviceCards[0].click();
            }
        }, 100);
    } else {
        // Service cards found, continue initialization
    }
});
</script>
<?php endif; ?>


<!-- Image Error Handling Script -->
<script>
// Global image error handler for missing project images
document.addEventListener('DOMContentLoaded', function() {
    // Handle image loading errors
    const images = document.querySelectorAll('img');
    
    images.forEach(img => {
        img.addEventListener('error', function() {
            
            // Create placeholder for missing project images
            if (this.src.includes('file/project/')) {
                const canvas = document.createElement('canvas');
                canvas.width = 400;
                canvas.height = 300;
                const ctx = canvas.getContext('2d');
                
                // Create gradient background
                const gradient = ctx.createLinearGradient(0, 0, 400, 300);
                gradient.addColorStop(0, '#1e293b');
                gradient.addColorStop(1, '#0f172a');
                ctx.fillStyle = gradient;
                ctx.fillRect(0, 0, 400, 300);
                
                // Add text
                ctx.fillStyle = '#fbbf24';
                ctx.font = 'bold 24px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('Project Image', 200, 130);
                
                ctx.fillStyle = '#94a3b8';
                ctx.font = '16px Arial';
                ctx.fillText('Image Not Available', 200, 160);
                
                ctx.fillStyle = '#64748b';
                ctx.font = '12px Arial';
                ctx.fillText('Please update project image', 200, 180);
                
                // Replace image with canvas
                this.src = canvas.toDataURL();
            }
            // For other images, use a simple placeholder
            else if (!this.src.includes('data:image')) {
                this.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0nNDAwJyBoZWlnaHQ9JzMwMCcgdmlld0JveD0nMCAwIDQwMCAzMDAnIGZpbGw9J25vbmUnIHhtbG5zPSdodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2Zyc+PHJlY3Qgd2lkdGg9JzQwMCcgaGVpZ2h0PSczMDAnIGZpbGw9JyMxZTI5M2InLz48dGV4dCB4PScyMDAnIHk9JzE1MCcgZmlsbD0nI2ZiYmYyNCcgZm9udC1zaXplPScyNCcgZm9udC1mYW1pbHk9J0FyaWFsJyB0ZXh0LWFuY2hvcj0nbWlkZGxlJz5JbWFnZSBOb3QgRm91bmQ8L3RleHQ+PC9zdmc+';
            }
        });
    });
});
</script>

<!-- Portfolio Section -->
<?php if(($konf->portfolio_section_active ?? true) && isset($projects) && $projects->count() > 0): ?>
<section id="portfolio"
    class="w-full max-w-screen-2xl mx-auto px-4 sm:px-6 py-8 flex flex-col items-center gap-8 sm:gap-10">

    <div class="flex flex-col gap-3 text-center">
        <h2 class="text-yellow-400 text-4xl sm:text-6xl font-bold leading-tight sm:leading-[67.2px] tracking-tight">
            Portfolio
        </h2>
        <p class="text-neutral-400 text-lg sm:text-2xl font-normal leading-6 sm:leading-7 tracking-tight">
            AI Technopreneur with 16+ years of expertise in driving innovation and digital transformation across
        </p>
    </div>

    <?php if(isset($jenis_projects) && count($jenis_projects) > 0): ?>
    <div class="w-full max-w-full sm:max-w-5xl flex flex-wrap justify-center gap-4 sm:gap-6" id="portfolio-filters">
        <button class="filter-btn px-6 sm:px-8 py-3 bg-yellow-400 rounded-lg outline outline-[1.5px] outline-yellow-400 flex items-center gap-3 transition-all duration-300 ease-in-out" data-filter="all">
            <span class="text-neutral-900 text-base sm:text-lg font-semibold leading-[64px]">All</span>
        </button>
        <?php $__currentLoopData = $jenis_projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jenis): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <button class="filter-btn px-6 sm:px-8 py-3 bg-slate-800/60 rounded-lg outline outline-[0.5px] outline-slate-500 flex items-center gap-3 transition-all duration-300 ease-in-out" data-filter="<?php echo e($jenis); ?>">
            <span class="text-white text-base sm:text-lg font-semibold capitalize leading-[64px]"><?php echo e($jenis); ?></span>
        </button>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>

    <div class="w-full max-w-full sm:max-w-5xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8" id="portfolio-grid">
        <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="portfolio-item p-6 sm:p-8 bg-slate-800/60 rounded-2xl outline outline-1 <?php echo e($loop->iteration == 3 ? 'outline-yellow-400' : 'outline-neutral-900/40'); ?> backdrop-blur-xl flex flex-col gap-6 transition-opacity duration-300 ease-in-out" data-jenis="<?php echo e($project->jenis_project); ?>">
            <a href="<?php echo e(route('portfolio.detail', $project->slug_project)); ?>">
                <img class="w-full max-w-[300px] sm:max-w-[400px] h-auto rounded-2xl aspect-[5/4] object-cover project-image" 
                     src="<?php echo e(asset('file/project/' . $project->gambar_project)); ?>" 
                     alt="<?php echo e($project->nama_project); ?>"
                     data-project-name="<?php echo e($project->nama_project); ?>"
                     data-image-file="<?php echo e($project->gambar_project); ?>"
                     onerror="this.style.opacity='0.7';" />
            </a>
            <div class="flex flex-col gap-4">
                <div class="flex flex-col gap-3">
                    <div class="flex gap-2">
                        <span class="text-gray-500 text-xs font-normal leading-none"><?php echo e($project->jenis_project); ?></span>
                    </div>
                    <h3 class="<?php echo e($loop->iteration == 3 ? 'text-yellow-400' : 'text-white'); ?> text-xl sm:text-3xl font-bold leading-loose tracking-tight">
                        <?php echo e($project->nama_project); ?>

                    </h3>
                    <p class="text-neutral-400 text-base font-normal leading-normal max-w-full sm:max-w-[400px]">
                        <?php echo Str::limit(strip_tags($project->keterangan_project), 120); ?>

                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="<?php echo e(route('portfolio.detail', $project->slug_project)); ?>" class="px-4 py-2 bg-yellow-400 rounded-lg text-neutral-900 text-base font-semibold leading-normal tracking-tight transition-all duration-300 ease-in-out hover:bg-yellow-500">Read More</a>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const filterBtns = document.querySelectorAll(".filter-btn");
    const items = document.querySelectorAll(".portfolio-item");

    filterBtns.forEach(btn => {
        btn.addEventListener("click", () => {
            filterBtns.forEach(b => b.classList.remove("bg-yellow-400", "outline-yellow-400"));
            filterBtns.forEach(b => b.querySelector("span").classList.remove("text-neutral-900"));
            filterBtns.forEach(b => b.classList.add("bg-slate-800/60", "outline-slate-500"));
            filterBtns.forEach(b => b.querySelector("span").classList.add("text-white"));

            btn.classList.remove("bg-slate-800/60", "outline-slate-500");
            btn.classList.add("bg-yellow-400", "outline-yellow-400");
            btn.querySelector("span").classList.remove("text-white");
            btn.querySelector("span").classList.add("text-neutral-900");

            const filter = btn.getAttribute("data-filter");

            items.forEach(item => {
                if (filter === "all" || item.getAttribute("data-jenis") === filter) {
                    item.classList.remove("hidden");
                    item.classList.add("flex");
                } else {
                    item.classList.add("hidden");
                    item.classList.remove("flex");
                }
            });
        });
    });
});
</script>
<?php endif; ?>

<!-- Testimonials Section -->
<?php if($konf->testimonials_section_active ?? true): ?>
<section class="testimonials-section" id="testimonials">
    <div class="content-wrapper">
        <h2 class="testimonials-title">Testimonials</h2>
        <p class="about-content">
            Real stories from clients who transformed their business with AI and automation.
        </p>

        <?php if(isset($testimonial) && $testimonial->count() > 0): ?>
        <div class="testimonials-wrapper relative overflow-hidden">
            <div class="testimonials-grid flex transition-transform duration-500 ease-in-out" id="testimonialSlider">
                <?php $__currentLoopData = $testimonial; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="testimonial-item flex-shrink-0 w-full sm:w-1/2 lg:w-1/3 px-4 text-center">
                    <img src="<?php echo e(asset('file/testimonial/' . $row->gambar_testimonial)); ?>" alt="<?php echo e($row->judul_testimonial); ?>" class="testimonial-image mx-auto rounded-full w-20 h-20 object-cover border-4 border-yellow-400">
                    <div class="testimonial-text mt-4 text-white">"<?php echo $row->deskripsi_testimonial; ?>"</div>
                    <div class="testimonial-author mt-2 font-semibold text-yellow-400">
                        <?php echo e($row->judul_testimonial ?? 'Testimonial'); ?>

                    </div>
                    <p class="text-gray-400 text-sm"><?php echo e($row->jabatan); ?></p>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="flex justify-center mt-6 gap-2" id="testimonialDots"></div>
        </div>
        <?php else: ?>
        <!-- No Data State -->
        <div class="flex flex-col items-center justify-center py-16">
            <div class="text-yellow-400 text-6xl mb-4">💬</div>
            <h3 class="text-white text-xl font-semibold mb-2">No Testimonials Yet</h3>
            <p class="text-gray-400 text-center max-w-md">
                We're working on collecting testimonials from our clients. Check back soon to see what our customers are saying about our AI solutions!
            </p>
        </div>
        <?php endif; ?>
    </div>
</section>

<style>
.testimonials-section {
    width: 100%;
    padding: 40px 0;
    text-align: center;
    background: #1e2b44;
    overflow: hidden;
}

.testimonials-title {
    font-size: 32px;
    font-weight: 700;
    color: #ffd300;
    margin-bottom: 16px;
}

.testimonials-wrapper {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scroll-behavior: smooth;
    padding: 20px 0;
}

.testimonials-grid {
    display: flex;
    gap: 20px;
    padding-bottom: 10px;
    scroll-snap-type: x mandatory;
}

.testimonial-item {
    background: #132138;
    border-radius: 12px;
    padding: 20px;
    text-align: left;
    transition: transform 0.4s ease, box-shadow 0.4s ease;
    min-width: 300px;
    flex-shrink: 0;
    scroll-snap-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.testimonial-image {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 50%;
    margin-bottom: 15px;
    border: 2px solid #ffd300;
    transition: transform 0.4s ease;
}

.testimonial-text {
    font-size: 16px;
    color: #ffffff;
    margin-bottom: 10px;
    line-height: 1.4;
    text-align: center;
}

.testimonial-author {
    font-size: 14px;
    color: #989898;
    font-style: italic;
    text-align: center;
}

.testimonial-item:hover {
    transform: translateY(-10px) scale(1.05) rotate(2deg);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
}

.testimonial-item:hover .testimonial-image {
    transform: scale(1.1);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const slider = document.getElementById("testimonialSlider");
    const dotsContainer = document.getElementById("testimonialDots");
    let currentIndex = 0;
    let slideInterval;

    function getTotalPages() {
        const itemsPerPage = window.innerWidth >= 1024 ? 3 : window.innerWidth >= 640 ? 2 : 1;
        return Math.ceil(slider.children.length / itemsPerPage);
    }

    function renderDots() {
        dotsContainer.innerHTML = "";
        const totalPages = getTotalPages();
        for (let i = 0; i < totalPages; i++) {
            const dot = document.createElement("span");
            dot.className = "dot w-3 h-3 rounded-full bg-gray-500 inline-block cursor-pointer transition";
            dot.addEventListener("click", () => {
                showSlide(i);
                stopAutoSlide();
                startAutoSlide();
            });
            dotsContainer.appendChild(dot);
        }
    }

    function showSlide(index) {
        const wrapper = slider.parentElement;
        const wrapperWidth = wrapper.offsetWidth;
        const totalPages = getTotalPages();

        if (index < 0) index = totalPages - 1;
        if (index >= totalPages) index = 0;

        currentIndex = index;
        const offset = -index * wrapperWidth;
        slider.style.transform = `translateX(${offset}px)`;

        const dots = dotsContainer.querySelectorAll(".dot");
        dots.forEach((dot, i) => {
            dot.classList.toggle("bg-yellow-400", i === index);
            dot.classList.toggle("bg-gray-500", i !== index);
        });
    }

    function startAutoSlide() {
        if (getTotalPages() > 1) {
            slideInterval = setInterval(() => {
                showSlide(currentIndex + 1);
            }, 5000);
        }
    }

    function stopAutoSlide() {
        if (slideInterval) {
            clearInterval(slideInterval);
        }
    }

    renderDots();
    showSlide(0);
    startAutoSlide();

    window.addEventListener("resize", () => {
        renderDots();
        showSlide(currentIndex);
    });
});
</script>
<?php endif; ?>

<!-- Gallery Section -->
<?php if($konf->gallery_section_active ?? true): ?>
<section id="gallery" class="w-full max-w-screen-xl mx-auto px-3 sm:px-4 py-6 flex flex-col items-center gap-6 sm:gap-10">
    <div class="flex flex-col gap-2 text-center">
        <h2 class="text-yellow-400 text-3xl sm:text-5xl font-bold leading-tight tracking-tight">
            Gallery
        </h2>
        <p class="text-neutral-400 text-base sm:text-lg font-normal leading-6 tracking-tight">
            Explore the visual journey of my work, from concept to impactful solutions
        </p>
    </div>

    <?php if(isset($galeri) && $galeri->count() > 0): ?>
    <div id="galleryGrid" class="grid grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 w-full">
    <?php $__currentLoopData = $galeri->where('status', 'Active')->sortBy('sequence'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div>
    <div class="relative group rounded-lg bg-slate-900 outline outline-1 outline-slate-500 overflow-hidden transition-transform duration-300 hover:scale-105 hover:shadow-md cursor-pointer" onclick="openGalleryModal(<?php echo e($row->id_galeri); ?>, '<?php echo e($row->nama_galeri); ?>', 'gallery')">
    <?php if($row->thumbnail): ?>
    <img src="<?php echo e(asset('file/galeri/' . $row->thumbnail)); ?>" alt="<?php echo e($row->nama_galeri ?? 'Gallery image'); ?>" class="w-full h-auto rounded-lg aspect-square object-cover" />
    <?php elseif($row->activeGalleryItems->first()): ?>
    <?php
    $firstItem = $row->activeGalleryItems->first();
    ?>
    <?php if($firstItem->type === 'image'): ?>
    <img src="<?php echo e(asset('file/galeri/' . $firstItem->file_name)); ?>" alt="<?php echo e($row->nama_galeri ?? 'Gallery image'); ?>" class="w-full h-auto rounded-lg aspect-square object-cover" />
    <?php elseif($firstItem->type === 'youtube'): ?>
    <?php
    preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\n?#]+)/', $firstItem->youtube_url, $matches);
    $videoId = $matches[1] ?? null;
    ?>
    <?php if($videoId): ?>
    <img src="https://img.youtube.com/vi/<?php echo e($videoId); ?>/maxresdefault.jpg" alt="<?php echo e($row->nama_galeri ?? 'Gallery image'); ?>" class="w-full h-auto rounded-lg aspect-square object-cover" />
    <?php endif; ?>
    <?php endif; ?>
    <?php else: ?>
    <div class="w-full aspect-square bg-gray-700 rounded-lg flex items-center justify-center">
    <i class="fas fa-image text-gray-500 text-3xl"></i>
    </div>
    <?php endif; ?>
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-opacity duration-300 flex items-center justify-center">
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-center">
                            <p class="text-white text-sm font-semibold mb-2"><?php echo e($row->nama_galeri ?? 'Gallery'); ?></p>
                            <div class="inline-flex items-center px-3 py-2 bg-yellow-400 text-black rounded-lg text-sm font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                View Gallery
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        
        <!-- Gallery Modal -->
        <div id="galleryModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden flex items-center justify-center p-4">
            <div class="bg-slate-800 rounded-2xl max-w-6xl w-full max-h-[90vh] overflow-hidden">
                <div class="flex justify-between items-center p-6 border-b border-slate-600">
                    <h3 id="galleryModalTitle" class="text-xl font-bold text-white">Gallery</h3>
                    <button onclick="closeGalleryModal()" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="galleryModalContent" class="p-6 overflow-y-auto max-h-[calc(90vh-100px)]">
                    <!-- Gallery content will be loaded here -->
                </div>
            </div>
        </div>
    <?php else: ?>
    <!-- No Data State -->
    <div class="flex flex-col items-center justify-center py-16">
        <div class="text-yellow-400 text-6xl mb-4">🖼️</div>
        <h3 class="text-white text-xl font-semibold mb-2">No Gallery Images Yet</h3>
        <p class="text-gray-400 text-center max-w-md">
            We're building our gallery showcase. Stay tuned to see visual examples of our AI projects and solutions in action!
        </p>
    </div>
    <?php endif; ?>
</section>

<script>
function openGalleryModal(galleryId, galleryName) {
    document.getElementById('galleryModalTitle').textContent = galleryName || 'Gallery';
    document.getElementById('galleryModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Show loading
    document.getElementById('galleryModalContent').innerHTML = `
        <div class="flex items-center justify-center py-12">
            <div class="text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-yellow-400 mx-auto mb-4"></div>
                <p class="text-gray-400">Loading gallery...</p>
                <p class="text-gray-500 text-sm mt-2">Gallery ID: ${galleryId}</p>
            </div>
        </div>
    `;
    
    // Auto-detect correct base URL
    const baseUrl = window.location.pathname.includes('/ALI-PORTO') ? '/ALI-PORTO/public' : '';
    
    // Try multiple API endpoints
    const endpoints = [
        `${baseUrl}/api/gallery/${galleryId}/items`,
        `${baseUrl}/gallery/${galleryId}/items`,
        `/api/gallery/${galleryId}/items`,
        `/gallery/${galleryId}/items`
    ];
    
    async function tryFetchGallery() {
        for (const endpoint of endpoints) {
            try {
                const response = await fetch(endpoint, {
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                
                if (!response.ok) {
                    continue;
                }
                
                const data = await response.json();
                
                // Handle different response formats
                let items = [];
                if (data.success && data.items && Array.isArray(data.items)) {
                    items = data.items;
                } else if (data.success && data.data && Array.isArray(data.data)) {
                    items = data.data;
                } else if (Array.isArray(data)) {
                    items = data;
                }
                
                if (items.length > 0) {
                    let content = '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">';
                    
                    items.forEach(item => {
                        content += `
                            <div class="bg-slate-700 rounded-xl overflow-hidden hover:transform hover:scale-105 transition-all duration-300">
                                <div class="aspect-square relative">
                                    ${item.type === 'image' ? 
                                        `<img src="${item.file_url}" alt="${item.title || 'Gallery item'}" class="w-full h-full object-cover cursor-pointer" onclick="openImageModal('${item.file_url}', '${item.title || 'Gallery item'}')">` :
                                        `<iframe class="w-full h-full" src="https://www.youtube.com/embed/${extractYouTubeId(item.file_url)}" frameborder="0" allowfullscreen></iframe>`
                                    }
                                    <div class="absolute top-2 right-2">
                                        <span class="bg-black bg-opacity-60 text-white text-xs px-2 py-1 rounded">${item.type}</span>
                                    </div>
                                </div>
                                ${item.title || item.description ? `
                                    <div class="p-4">
                                        ${item.title ? `<h4 class="text-white font-semibold mb-2">${item.title}</h4>` : ''}
                                        ${item.description ? `<p class="text-gray-400 text-sm">${item.description}</p>` : ''}
                                    </div>
                                ` : ''}
                            </div>
                        `;
                    });
                    
                    content += '</div>';
                    document.getElementById('galleryModalContent').innerHTML = content;
                    return; // Success, exit function
                }
                
            } catch (error) {
                // Silent fail and try next endpoint
            }
        }
        
        // If all endpoints fail, show error
        document.getElementById('galleryModalContent').innerHTML = `
            <div class="text-center py-12">
                <div class="text-red-400 text-6xl mb-4">⚠️</div>
                <h3 class="text-white text-xl font-semibold mb-2">Gallery API Error</h3>
                <p class="text-gray-400 mb-4">Could not load gallery items for Gallery ID: ${galleryId}</p>
                
                <div class="bg-gray-800 p-4 rounded-lg text-left max-w-2xl mx-auto">
                    <h4 class="text-yellow-400 font-semibold mb-2">Tried Endpoints:</h4>
                    <ul class="text-sm text-gray-300 space-y-1">
                        ${endpoints.map(url => `<li>• <code class="bg-gray-700 px-1 rounded">${url}</code></li>`).join('')}
                    </ul>
                    
                    <div class="mt-4">
                        <h5 class="text-green-400 font-semibold mb-2">Solutions:</h5>
                        <ol class="text-sm text-gray-300 space-y-1 list-decimal list-inside">
                            <li>Check if Laravel routes are working</li>
                            <li>Verify gallery API endpoints</li>
                            <li>Check database connection</li>
                        </ol>
                    </div>
                </div>
                
                <div class="mt-6 space-x-4">
                    <button onclick="location.reload()" class="px-4 py-2 bg-yellow-400 text-black rounded hover:bg-yellow-500 transition-colors">
                        Refresh Page
                    </button>
                </div>
            </div>
        `;
    }
    
    tryFetchGallery();
}

function closeGalleryModal() {
    document.getElementById('galleryModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function displayGalleryItems(items) {
    let content = '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">';
    
    items.forEach(item => {
        const imageUrl = item.file_url || `<?php echo e(asset('file/galeri/')); ?>/${item.file_name}`;
        
        content += `
            <div class="bg-slate-700 rounded-xl overflow-hidden hover:transform hover:scale-105 transition-all duration-300">
                <div class="aspect-square relative">
                    ${item.type === 'image' ? 
                        `<img src="${imageUrl}" alt="Gallery item" class="w-full h-full object-cover cursor-pointer" onclick="openImageModal('${imageUrl}', 'Gallery item')">` :
                        `<iframe class="w-full h-full" src="https://www.youtube.com/embed/${extractYouTubeId(item.youtube_url)}" frameborder="0" allowfullscreen></iframe>`
                    }
                    <div class="absolute top-2 right-2">
                        <span class="bg-black bg-opacity-60 text-white text-xs px-2 py-1 rounded">${item.type}</span>
                    </div>
                </div>
                <div class="p-3">
                    <p class="text-sm text-gray-300">Sequence: ${item.sequence}</p>
                    <p class="text-sm text-gray-300">Status: ${item.status}</p>
                </div>
            </div>
        `;
    });
    
    content += '</div>';
    document.getElementById('galleryModalContent').innerHTML = content;
}

function showDetailedError(galleryId, baseUrl, endpoints) {
    document.getElementById('galleryModalContent').innerHTML = `
        <div class="text-center py-12">
            <div class="text-red-400 text-6xl mb-4">⚠️</div>
            <h3 class="text-white text-xl font-semibold mb-2">Error Loading Gallery</h3>
            <p class="text-gray-400 mb-4">Could not load gallery items for Gallery ID: ${galleryId}</p>
            
            <div class="bg-gray-800 p-4 rounded-lg text-left max-w-2xl mx-auto">
                <h4 class="text-yellow-400 font-semibold mb-2">Debug Information:</h4>
                <ul class="text-sm text-gray-300 space-y-1">
                    <li>✅ Database has gallery items for this ID</li>
                    <li>✅ Modal opens correctly</li>
                    <li>❌ API endpoints not responding</li>
                    <li>🔗 Base URL: <code class="bg-gray-700 px-1 rounded">${baseUrl || 'none'}</code></li>
                </ul>
                
                <div class="mt-4">
                    <h5 class="text-blue-400 font-semibold mb-2">Tried Endpoints:</h5>
                    <ol class="text-sm text-gray-300 space-y-1">
                        ${endpoints.map(url => `<li>• <code class="bg-gray-700 px-1 rounded">${url}</code></li>`).join('')}
                    </ol>
                </div>
                
                <div class="mt-4">
                    <h5 class="text-green-400 font-semibold mb-2">Quick Solutions:</h5>
                    <ol class="text-sm text-gray-300 space-y-1 list-decimal list-inside">
                        <li>Use Laravel serve: <code class="bg-gray-700 px-1 rounded">php artisan serve</code></li>
                        <li>Access via: <code class="bg-gray-700 px-1 rounded">http://localhost:8000</code></li>
                        <li>Or clear routes: <code class="bg-gray-700 px-1 rounded">php artisan route:clear</code></li>
                    </ol>
                </div>
            </div>
            
            <div class="mt-6 space-x-4">
                <button onclick="location.reload()" class="px-4 py-2 bg-yellow-400 text-black rounded hover:bg-yellow-500 transition-colors">
                    Refresh Page
                </button>
                <button onclick="window.open('http://localhost:8000', '_blank')" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition-colors">
                    Open Laravel Server
                </button>
            </div>
        </div>
    `;
}

function extractYouTubeId(url) {
    const regex = /(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\n?#]+)/;
    const match = url.match(regex);
    return match ? match[1] : null;
}

function openImageModal(imageUrl, title) {
    // Create image modal for full view
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-90 z-60 flex items-center justify-center p-4';
    modal.innerHTML = `
        <div class="relative max-w-full max-h-full">
            <img src="${imageUrl}" alt="${title}" class="max-w-full max-h-full object-contain">
            <button onclick="this.parentElement.parentElement.remove(); document.body.style.overflow = '';" class="absolute top-4 right-4 text-white bg-black bg-opacity-50 rounded-full p-2 hover:bg-opacity-75 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;
    document.body.appendChild(modal);
    document.body.style.overflow = 'hidden';
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const galleryModal = document.getElementById('galleryModal');
    if (galleryModal) {
        galleryModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeGalleryModal();
            }
        });
    }
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !galleryModal.classList.contains('hidden')) {
            closeGalleryModal();
        }
    });
});
</script>

<?php endif; ?>

<!-- Articles Section -->
<?php if($konf->articles_section_active ?? true): ?>
<section id="articles" class="w-full max-w-screen-2xl mx-auto px-4 sm:px-6 py-8 flex flex-col items-center gap-8 sm:gap-14">
    <div class="flex flex-col gap-3 text-center">
        <h2 class="text-yellow-400 text-3xl sm:text-5xl font-extrabold leading-tight sm:leading-[56px]">
            Latest Article
        </h2>
        <p class="text-neutral-400 text-lg sm:text-2xl font-normal leading-6 sm:leading-7 tracking-tight">
            Weekly insights on AI, technology, and innovation
        </p>
    </div>
    
    <?php if(isset($article) && $article->count() > 0): ?>
    <div class="flex flex-col sm:flex-row gap-6 sm:gap-8">
        <div class="flex flex-col gap-6 sm:gap-8">
            <?php $__currentLoopData = $article->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="p-6 sm:p-9 bg-slate-800 rounded-xl outline outline-1 outline-blue-950 outline-offset--1 flex flex-col sm:flex-row gap-4 sm:gap-8">
                <img src="<?php echo e(!empty($row->gambar_berita) ? asset('file/berita/' . $row->gambar_berita) : asset('file/berita/placeholder.png')); ?>" alt="<?php echo e($row->judul_berita); ?> thumbnail" class="w-full sm:w-48 h-auto sm:h-32 object-cover rounded-xl" />
                <div class="flex flex-col gap-4">
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-3">
                            <span class="text-slate-600 text-sm sm:text-base font-medium leading-normal">
                                <?php echo e(\Carbon\Carbon::parse($row->tanggal_berita)->format('M d, Y')); ?>

                            </span>
                            <div class="px-2 sm:px-3 py-1 sm:py-2 bg-yellow-400/10 rounded-sm">
                                <span class="text-yellow-400 text-xs font-medium font-['Fira_Sans'] uppercase leading-3">
                                    <?php echo e($row->kategori_berita ?? 'AI & Tech'); ?>

                                </span>
                            </div>
                        </div>
                        <h3 class="text-white text-base sm:text-xl font-bold leading-6 sm:leading-7 max-w-full sm:max-w-96">
                            <?php echo e($row->judul_berita); ?>

                        </h3>
                    </div>
                    <p class="text-slate-500 text-sm sm:text-base font-medium leading-normal max-w-full sm:max-w-96">
                        <?php echo \Illuminate\Support\Str::limit(strip_tags($row->isi_berita), 150, '...'); ?>

                        <a href="<?php echo e(route('article.detail', $row->slug_berita)); ?>" class="text-yellow-400 hover:text-yellow-500 font-medium">Read More</a>
                    </p>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php if($article->count() > 0): ?>
        <?php
        $featuredArticle = $article->first();
        ?>
        <div class="p-6 sm:p-12 bg-slate-800 rounded-xl outline outline-1 outline-blue-950 outline-offset--1 flex flex-col gap-6 sm:gap-8">
            <img src="<?php echo e(!empty($featuredArticle->gambar_berita) ? asset('file/berita/' . $featuredArticle->gambar_berita) : asset('file/berita/placeholder.png')); ?>" alt="<?php echo e($featuredArticle->judul_berita); ?> featured thumbnail" class="w-full max-w-[640px] h-auto rounded-xl object-cover" />
            <div class="flex flex-col gap-6 sm:gap-8">
                <div class="flex flex-col gap-4">
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-3">
                            <span class="text-slate-600 text-sm sm:text-base font-medium leading-normal">
                                <?php echo e(\Carbon\Carbon::parse($featuredArticle->tanggal_berita)->format('M d, Y')); ?>

                            </span>
                            <div class="px-2 sm:px-3 py-1 sm:py-2 bg-yellow-400/10 rounded-sm">
                                <span class="text-yellow-400 text-xs font-medium font-['Fira_Sans'] uppercase leading-3">
                                    <?php echo e($featuredArticle->kategori_berita ?? 'AI & Tech'); ?>

                                </span>
                            </div>
                        </div>
                        <h3 class="text-white text-xl sm:text-2xl font-bold leading-loose max-w-full sm:max-w-[641px]">
                            <?php echo e($featuredArticle->judul_berita); ?>

                        </h3>
                    </div>
                    <p class="text-slate-500 text-sm sm:text-base font-medium leading-normal max-w-full sm:max-w-[641px]">
                        <?php echo \Illuminate\Support\Str::limit(strip_tags($featuredArticle->isi_berita), 150, '...'); ?>

                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="<?php echo e(route('article.detail', $featuredArticle->slug_berita)); ?>" class="text-yellow-400 text-base sm:text-xl font-semibold leading-normal tracking-tight hover:text-yellow-500">
                        Read More
                    </a>
                    <svg class="w-5 sm:w-6 h-5 sm:h-6" fill="none" stroke="yellow" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <a href="<?php echo e(url('articles')); ?>" class="px-6 sm:px-8 py-3 sm:py-4 rounded-xl outline outline-1 outline-yellow-400 outline-offset--1 backdrop-blur-[2px] flex items-center gap-2.5">
        <span class="text-yellow-400 text-base font-semibold leading-tight tracking-tight">See More</span>
        <svg class="w-5 sm:w-6 h-5 sm:h-6" fill="none" stroke="yellow" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7" />
        </svg>
    </a>
    <?php else: ?>
    <!-- No Data State -->
    <div class="flex flex-col items-center justify-center py-16">
        <div class="text-yellow-400 text-6xl mb-4">📝</div>
        <h3 class="text-white text-xl font-semibold mb-2">No Articles Yet</h3>
        <p class="text-gray-400 text-center max-w-md">
            We're working on creating insightful articles about AI, technology, and innovation. Check back soon for the latest updates!
        </p>
        <a href="<?php echo e(url('articles')); ?>" class="mt-6 px-6 py-3 rounded-xl outline outline-1 outline-yellow-400 text-yellow-400 hover:bg-yellow-400 hover:text-black transition-all duration-300">
            <span class="font-semibold">Explore Articles</span>
        </a>
    </div>
    <?php endif; ?>
</section>
<?php endif; ?>

<!-- Contact Section -->
<?php if($konf->contact_section_active ?? true): ?>
<section id="contact" class="w-full max-w-screen-2xl mx-auto px-12 sm:px-6 lg:px-8 py-10 sm:py-14 bg-slate-800 rounded-3xl border border-slate-700 -m-1 flex flex-col lg:flex-row gap-8 lg:gap-12">
    <div class="flex flex-col gap-6 sm:gap-8 max-w-full lg:max-w-md">
        <div class="flex flex-col gap-4">
            <h2 class="text-white text-xl sm:text-2xl font-semibold leading-loose">
                Have a project or question in mind? Just send me a message.
            </h2>
            <p class="text-gray-400 text-sm sm:text-base font-light leading-normal">
                Let's discuss how AI and automation can drive innovation and efficiency in your organization.
            </p>
        </div>
        <div class="flex flex-col gap-5">
            <div class="flex items-center gap-4 p-4 bg-slate-900 rounded-xl hover:bg-slate-700 transition-all duration-300">
                <div class="flex-shrink-0 w-12 h-12 p-3 bg-yellow-400 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="black" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                </div>
                <div class="flex flex-col gap-1 min-w-0 flex-1">
                    <span class="text-gray-400 text-sm font-light leading-tight">Call me now</span>
                    <a href="tel:<?php echo e($konf->no_hp_setting); ?>" class="text-white text-base font-normal leading-normal hover:text-yellow-400 transition-colors truncate"><?php echo e($konf->no_hp_setting); ?></a>
                </div>
            </div>
            <div class="flex items-center gap-4 p-4 bg-slate-900 rounded-xl hover:bg-slate-700 transition-all duration-300">
                <div class="flex-shrink-0 w-12 h-12 p-3 bg-yellow-400 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="black" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="flex flex-col gap-1 min-w-0 flex-1">
                    <span class="text-gray-400 text-sm font-light leading-tight">Chat with me</span>
                    <a href="mailto:<?php echo e($konf->email_setting); ?>" class="text-white text-base font-normal leading-normal hover:text-yellow-400 transition-colors truncate"><?php echo e($konf->email_setting); ?></a>
                </div>
            </div>
            <div class="flex items-center gap-4 p-4 bg-slate-900 rounded-xl hover:bg-slate-700 transition-all duration-300">
                <div class="flex-shrink-0 w-12 h-12 p-3 bg-yellow-400 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="black" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div class="flex flex-col gap-1 min-w-0 flex-1">
                    <span class="text-gray-400 text-sm font-light leading-tight">Location</span>
                    <span class="text-white text-base font-normal leading-normal"><?php echo e($konf->alamat_setting); ?></span>
                </div>
            </div>
        </div>
        <div class="p-6 sm:p-7 bg-slate-900 rounded-2xl flex flex-col gap-4 hover:bg-slate-700 transition-all duration-300">
            <span class="text-white text-base font-normal leading-normal">Follow me on social media</span>
            <div class="flex gap-3 justify-center">
                <a href="https://www.instagram.com/<?php echo e($konf->instagram_setting); ?>" target="_blank" class="social-icon p-3 bg-slate-800 rounded-full hover:bg-yellow-400 transition-all duration-300">
                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M7 2C4.243 2 2 4.243 2 7v10c0 2.757 2.243 5 5 5h10c2.757 0 5-2.243 5-5V7c0-2.757-2.243-5-5-5H7zm10 2c1.654 0 3 1.346 3 3v10c0 1.654-1.346 3-3 3H7c-1.654 0-3-1.346-3-3V7c0-1.654 1.346-3 3-3h10zm-5 3a5 5 0 100 10 5 5 0 000-10zm0 2a3 3 0 110 6 3 3 0 010-6zm4.5-2a1.5 1.5 0 100 3 1.5 1.5 0 000-3z" />
                    </svg>
                </a>
                <a href="https://www.tiktok.com/@<?php echo $konf->tiktok_setting; ?>" target="_blank" class="social-icon p-3 bg-slate-800 rounded-full hover:bg-yellow-400 transition-all duration-300">
                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19.321 5.562a5.124 5.124 0 0 1-.443-.258 6.228 6.228 0 0 1-1.137-.966c-.849-.849-1.254-1.99-1.254-3.338h-2.341v10.466c0 2.059-1.68 3.739-3.739 3.739-2.059 0-3.739-1.68-3.739-3.739s1.68-3.739 3.739-3.739c.659 0 1.254.18 1.787.493v-2.402c-.533-.09-1.076-.135-1.787-.135C5.67 5.683 2 9.352 2 13.989s3.67 8.306 8.307 8.306 8.306-3.669 8.306-8.306V9.072c1.181.849 2.628 1.344 4.163 1.344V7.861c-1.27 0-2.435-.413-3.455-1.299z"/>
                    </svg>
                </a>                
                <a href="https://www.youtube.com/<?php echo e($konf->youtube_setting); ?>" target="_blank" class="p-3 bg-slate-800 rounded-full hover:bg-yellow-400 transition-all duration-300 group">
                    <svg class="w-5 h-5 text-yellow-400 group-hover:text-black" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M21.8 8s-.2-1.4-.8-2c-.7-.8-1.5-.8-1.9-.9C16.7 4.8 12 4.8 12 4.8h-.1s-4.7 0-7.1.3c-.4 0-1.2.1-1.9.9-.6.6-.8 2-.8 2S2 9.6 2 11.3v1.3c0 1.7.2 3.3.2 3.3s.2 1.4.8 2c.7.8 1.7.7 2.1.8 1.6.2 6.9.3 6.9.3s4.7 0 7.1-.3c.4 0 1.2-.1 1.9-.9.6-.6.8-2 .8-2s.2-1.6.2-3.3v-1.3c0-1.7-.2-3.3-.2-3.3zM10 14.6V9.4l5.2 2.6-5.2 2.6z" />
                    </svg>
                </a>
                <a href="https://www.linkedin.com/in/<?php echo e($konf->linkedin_setting); ?>" target="_blank" class="p-3 bg-slate-800 rounded-full hover:bg-yellow-400 transition-all duration-300 group">
                    <svg class="w-5 h-5 text-yellow-400 group-hover:text-black" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                    </svg>
                </a>                
                <a href="https://wa.me/<?php echo e($konf->no_hp_setting); ?>" target="_blank" class="p-3 bg-slate-800 rounded-full hover:bg-yellow-400 hover:text-black transition-all duration-300">
                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12.017 2C6.486 2 2 6.484 2 12c0 1.73.443 3.361 1.22 4.78L2.1 21.712l5.056-1.323A9.951 9.951 0 0012.017 22c5.531 0 10.017-4.484 10.017-10S17.548 2 12.017 2zm5.23 14.314c-.251.714-1.233 1.334-2.005 1.491-.549.111-1.268.183-3.685-.825-2.831-1.18-4.673-4.057-4.814-4.247-.142-.19-1.157-1.569-1.157-2.993 0-1.425.731-2.127 1.012-2.421.281-.295.611-.369.815-.369.204 0 .407.002.584.011.189.009.441-.072.69.536.25.608.855 2.176.928 2.334.074.157.123.342.025.548-.099.206-.148.332-.296.51-.148.178-.311.394-.444.53-.133.136-.272.282-.118.553.154.271.685 1.166 1.471 1.888 1.01.928 1.862 1.215 2.128 1.351.266.136.421.114.576-.07.155-.185.662-.8.839-1.077.177-.276.354-.23.597-.138.243.093 1.54.748 1.805.884.266.136.443.204.509.318.066.115.066.663-.184 1.377z"/>
                    </svg>
                </a>
                <a href="mailto:<?php echo e($konf->email_setting); ?>" class="p-3 bg-slate-800 rounded-full hover:bg-yellow-400 hover:text-black transition-all duration-300">
                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2zm0 2v.01L12 13 20 6.01V6H4zm0 12h16V8l-8 7-8-7v10z" />
                    </svg>
                </a>                
            </div>
        </div>
    </div>
    <form action="<?php echo e(route('contact.store')); ?>" method="POST" class="flex flex-col gap-6 sm:gap-8 flex-1">
        <?php echo csrf_field(); ?>
        <h2 class="text-white text-xl sm:text-2xl font-semibold leading-loose">Just say 👋 Hi</h2>
        <div class="flex flex-col gap-4">
            <div class="flex flex-col sm:flex-row gap-4">
                <input type="text" name="full_name" placeholder="Full Name" required class="w-full sm:w-1/2 h-12 bg-slate-800 rounded-md border border-slate-600 px-4 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400" />
                <input type="email" name="email" placeholder="Email Address" required class="w-full sm:w-1/2 h-12 bg-slate-800 rounded-md border border-slate-600 px-4 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400" />
            </div>
            <input type="text" name="subject" placeholder="Subject" class="w-full h-12 bg-slate-800 rounded-md border border-slate-600 px-4 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400" />
            <div class="flex flex-col sm:flex-row gap-4">
                <select name="service" class="w-full h-12 bg-slate-800 rounded-md border border-slate-600 px-4 text-white focus:outline-none focus:ring-2 focus:ring-yellow-400">
                    <option value="">Select Service</option>
                    <option value="ai">Digital Transformation 4.0 Consultant</option>
                    <option value="automation">AI AGENT AUTOMATION Solution</option>
                    <option value="automation">CUSTOM GPT/GEM Solution</option>
                    <option value="automation">Content Creator Endorsement</option>
                </select>
            </div>
            <textarea name="message" placeholder="Message" class="w-full h-32 bg-slate-800 rounded-md border border-slate-600 px-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 resize-none"></textarea>
            <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-yellow-400 rounded-xl flex items-center gap-3 hover:bg-yellow-500 transition-all duration-300 shadow-lg hover:shadow-xl justify-center">
                <span class="text-neutral-900 text-base font-semibold capitalize leading-[40px] sm:leading-[72px]">
                    Send Message
                </span>
                <svg class="w-5 sm:w-6 h-5 sm:h-6" fill="none" stroke="black" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </form>
</section>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<!-- DEBUG: Gallery Data Check -->
<script>
// Force cache bust - current timestamp
console.log('🚀 Gallery Modal Script Loaded at:', new Date().toISOString());

// Auto-detect if we're using XAMPP or Laravel serve and set correct base URL
function getBaseUrl() {
    const currentUrl = window.location.href;
    console.log('Current URL:', currentUrl);
    
    // If accessing via Laravel development server (port 8000)
    if (currentUrl.includes(':8000')) {
        console.log('✅ Detected Laravel serve (port 8000)');
        return ''; // No prefix needed
    }
    
    // If accessing via XAMPP (contains ALI-PORTO)
    if (currentUrl.includes('/ALI-PORTO')) {
        console.log('✅ Detected XAMPP with ALI-PORTO path');
        return '/ALI-PORTO/public'; // Add XAMPP path prefix
    }
    
    // Check if we're in a subdirectory
    const path = window.location.pathname;
    if (path.includes('/public/')) {
        const basePath = path.substring(0, path.indexOf('/public/') + '/public'.length);
        console.log('✅ Detected public directory, base path:', basePath);
        return basePath;
    }
    
    console.log('⚠️ Using default (no prefix)');
    return '';
}

function openGalleryModal(galleryId, galleryName, type = 'gallery') {
    console.log('Opening gallery modal:', { galleryId, galleryName, type });
    
    document.getElementById('galleryModalTitle').textContent = galleryName || 'Gallery';
    document.getElementById('galleryModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Show loading
    document.getElementById('galleryModalContent').innerHTML = `
        <div class="flex items-center justify-center py-12">
            <div class="text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-yellow-400 mx-auto mb-4"></div>
                <p class="text-gray-400">Loading gallery...</p>
                <p class="text-gray-500 text-sm mt-2">Gallery ID: ${galleryId}</p>
            </div>
        </div>
    `;
    
    // Get the correct base URL
    const baseUrl = getBaseUrl();
    console.log('🔗 Using base URL:', baseUrl);
    console.log('🌍 Current location:', window.location.href);
    
    // Try multiple endpoints with correct base URL
    const endpoints = [
        `${baseUrl}/api/gallery/${galleryId}/items`,
        `${baseUrl}/gallery/${galleryId}/items`
    ];
    
    console.log('🎯 Will try these endpoints:', endpoints);
    
    async function tryEndpoints() {
        for (const endpoint of endpoints) {
            try {
                console.log('Trying endpoint:', endpoint);
                const response = await fetch(endpoint, {
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                
                console.log('Response status:', response.status, response.statusText);
                
                if (!response.ok) {
                    console.log('Failed:', endpoint, response.status);
                    continue; // Try next endpoint
                }
                
                const data = await response.json();
                console.log('✅ API Success for', endpoint, ':', data);
                
                // Handle response data
                let items = [];
                if (data.success && data.items && Array.isArray(data.items)) {
                    items = data.items;
                } else if (data.success && data.data && Array.isArray(data.data)) {
                    items = data.data;
                } else if (Array.isArray(data)) {
                    items = data;
                }
                
                console.log('Processed items:', items.length, 'items found');
                
                if (items.length > 0) {
                    displayGalleryItems(items);
                    return; // Success, stop trying other endpoints
                }
                
            } catch (error) {
                console.error('❌ Failed endpoint:', endpoint, error.message);
            }
        }
        
        // If all endpoints fail, show detailed error
        showDetailedError(galleryId, baseUrl, endpoints);
    }
    
    tryEndpoints();
}
</script>
<?php echo $__env->make('layouts.web', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\ALI_PORTFOLIO\resources\views/welcome.blade.php ENDPATH**/ ?>