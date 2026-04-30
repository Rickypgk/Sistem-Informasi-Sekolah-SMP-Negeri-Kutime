<?php $__env->startSection('title', $galeri->judul); ?>

<?php $__env->startSection('content'); ?>
<section class="max-w-4xl mx-auto px-5 sm:px-6 lg:px-8 py-10">

    
    <nav class="flex items-center gap-1.5 text-xs text-slate-500 mb-6">
        <a href="<?php echo e(route('website.home')); ?>" class="hover:text-indigo-600">Beranda</a>
        <span>/</span>
        <a href="<?php echo e(route('website.galeri')); ?>" class="hover:text-indigo-600">Galeri</a>
        <span>/</span>
        <span class="text-slate-700 truncate max-w-xs"><?php echo e(Str::limit($galeri->judul, 40)); ?></span>
    </nav>

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">

        
        <div class="bg-black w-full">

            
            <?php if($galeri->tipe === 'photo' && $galeri->file_path): ?>
                <img
                    src="<?php echo e($galeri->file_url); ?>"
                    alt="<?php echo e($galeri->judul); ?>"
                    class="w-full max-h-[70vh] object-contain mx-auto block"
                >

            
            <?php elseif($galeri->tipe === 'video' && $galeri->file_path): ?>
                <video
                    src="<?php echo e($galeri->file_url); ?>"
                    controls
                    autoplay
                    class="w-full max-h-[70vh]"
                ></video>

            
            <?php elseif($galeri->tipe === 'link_youtube' && $galeri->embed_url): ?>
                <div class="aspect-video w-full">
                    <iframe
                        src="<?php echo e($galeri->embed_url); ?>"
                        class="w-full h-full"
                        allow="autoplay; encrypted-media; picture-in-picture"
                        allowfullscreen
                    ></iframe>
                </div>

            
            <?php elseif($galeri->tipe === 'link_facebook' && $galeri->embed_url): ?>
                <div class="aspect-video w-full">
                    <iframe
                        src="<?php echo e($galeri->embed_url); ?>"
                        class="w-full h-full"
                        allow="autoplay; encrypted-media"
                        allowfullscreen
                        scrolling="no"
                    ></iframe>
                </div>

            
            <?php else: ?>
                <div class="flex items-center justify-center h-48 text-slate-400 text-sm">
                    Media tidak tersedia
                </div>
            <?php endif; ?>

        </div>

        
        <div class="p-6 sm:p-8">

            
            <div class="flex flex-wrap items-center gap-2 mb-3">
                <span class="px-2.5 py-1 text-xs font-semibold rounded-full
                    <?php if($galeri->tipe === 'photo'): ?> bg-blue-100 text-blue-700
                    <?php elseif($galeri->tipe === 'video'): ?> bg-purple-100 text-purple-700
                    <?php elseif($galeri->tipe === 'link_youtube'): ?> bg-red-100 text-red-700
                    <?php else: ?> bg-indigo-100 text-indigo-700 <?php endif; ?>">
                    <?php echo e($galeri->tipe_label); ?>

                </span>

                <span class="px-2.5 py-1 text-xs rounded-full bg-slate-100 text-slate-600 capitalize">
                    <?php echo e($galeri->kategori); ?>

                </span>

                <span class="text-xs text-slate-400">
                    <?php echo e($galeri->created_at->format('d M Y')); ?>

                </span>
            </div>

            
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-slate-100 mb-4 leading-snug">
                <?php echo e($galeri->judul); ?>

            </h1>

            
            <?php if($galeri->deskripsi): ?>
                <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed mb-5">
                    <?php echo e($galeri->deskripsi); ?>

                </p>
            <?php endif; ?>

            
            <?php if(in_array($galeri->tipe, ['link_youtube', 'link_facebook']) && $galeri->link_url): ?>
                <div class="mb-5 flex items-center gap-2 p-3 bg-slate-50 dark:bg-slate-700/50 rounded-lg">
                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    <a href="<?php echo e($galeri->link_url); ?>" target="_blank"
                       class="text-sm text-indigo-600 hover:underline break-all">
                        Buka di <?php echo e($galeri->tipe === 'link_youtube' ? 'YouTube' : 'Facebook'); ?> →
                    </a>
                </div>
            <?php endif; ?>

            
            <div class="pt-5 border-t border-slate-200 dark:border-slate-700 flex items-center justify-between">
                <a href="<?php echo e(route('website.galeri')); ?>"
                   class="inline-flex items-center gap-1.5 text-sm text-indigo-600 hover:text-indigo-800 font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke Galeri
                </a>
            </div>
        </div>
    </div>

    
    <?php if($lainnya->isNotEmpty()): ?>
    <div class="mt-10">
        <h2 class="text-base font-semibold text-slate-900 dark:text-slate-100 mb-4">
            Media Lainnya
        </h2>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            <?php $__currentLoopData = $lainnya; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('website.galeri.show', $item->id)); ?>"
               class="group relative block bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden transition-all hover:shadow-md hover:-translate-y-1">

                
                <div class="aspect-[4/3] overflow-hidden bg-slate-100 dark:bg-slate-700">
                    <img
                        src="<?php echo e($item->thumbnail_url); ?>"
                        alt="<?php echo e($item->judul); ?>"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                        loading="lazy"
                    >
                </div>

                
                <?php if($item->is_video): ?>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-8 h-8 rounded-full bg-black/50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                    </div>
                </div>
                <?php endif; ?>

                
                <div class="absolute top-1.5 left-1.5">
                    <span class="px-1.5 py-0.5 text-xs rounded bg-black/60 text-white backdrop-blur-sm">
                        <?php echo e($item->tipe_label); ?>

                    </span>
                </div>

                
                <div class="p-2.5">
                    <p class="text-xs font-medium text-slate-800 dark:text-slate-100 line-clamp-1">
                        <?php echo e($item->judul); ?>

                    </p>
                    <p class="text-xs text-slate-400 mt-0.5"><?php echo e($item->created_at->format('d M Y')); ?></p>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PA 3\smpn-kutime\resources\views/website/galeri-detail.blade.php ENDPATH**/ ?>