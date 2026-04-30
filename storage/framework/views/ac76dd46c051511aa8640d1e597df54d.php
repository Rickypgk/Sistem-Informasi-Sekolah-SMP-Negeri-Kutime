<?php $__env->startSection('title', $berita->judul); ?>

<?php $__env->startSection('content'); ?>
<section class="max-w-4xl mx-auto px-5 sm:px-6 lg:px-8 py-10">

    
    <nav class="flex items-center gap-1.5 text-xs text-slate-500 mb-6">
        <a href="<?php echo e(route('website.home')); ?>" class="hover:text-indigo-600">Beranda</a>
        <span>/</span>
        <a href="<?php echo e(route('website.berita')); ?>" class="hover:text-indigo-600">Berita</a>
        <span>/</span>
        <span class="text-slate-700 truncate max-w-xs"><?php echo e(Str::limit($berita->judul, 40)); ?></span>
    </nav>

    <article class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">

        
        <?php if($berita->has_media): ?>
        <div class="w-full bg-black">

            
            <?php if($berita->media_tipe === 'photo' && $berita->media_file): ?>
                <img src="<?php echo e($berita->media_file_url); ?>"
                     alt="<?php echo e($berita->judul); ?>"
                     class="w-full max-h-[70vh] object-contain block mx-auto">

            
            <?php elseif($berita->media_tipe === 'video' && $berita->media_file): ?>
                <video src="<?php echo e($berita->media_file_url); ?>"
                       controls
                       class="w-full max-h-[70vh]"
                       poster="<?php echo e($berita->media_thumbnail ? asset('storage/'.$berita->media_thumbnail) : ''); ?>">
                    Browser Anda tidak mendukung video.
                </video>

            
            <?php elseif($berita->media_tipe === 'link_youtube' && $berita->embed_url): ?>
                <div class="aspect-video w-full">
                    <iframe src="<?php echo e($berita->embed_url); ?>"
                            class="w-full h-full"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                    </iframe>
                </div>

            
            <?php elseif($berita->media_tipe === 'link_facebook' && $berita->embed_url): ?>
                <div class="aspect-video w-full">
                    <iframe src="<?php echo e($berita->embed_url); ?>"
                            class="w-full h-full"
                            allow="autoplay; encrypted-media"
                            allowfullscreen
                            scrolling="no">
                    </iframe>
                </div>

            <?php endif; ?>
        </div>
        <?php endif; ?>

        
        <div class="p-6 sm:p-8">

            
            <div class="flex flex-wrap items-center gap-2 mb-4">
                <?php if($berita->kategori === 'pengumuman'): ?>
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">📢 Pengumuman</span>
                <?php else: ?>
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">📰 Berita</span>
                <?php endif; ?>

                <?php if($berita->is_penting): ?>
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-red-600 text-white">🔴 Penting</span>
                <?php endif; ?>

                
                <?php if($berita->has_media): ?>
                    <?php
                        $badge = match($berita->media_tipe) {
                            'photo'          => ['🖼️ Foto',     'bg-blue-100 text-blue-700'],
                            'video'          => ['🎥 Video',    'bg-purple-100 text-purple-700'],
                            'link_youtube'   => ['▶️ YouTube',  'bg-red-100 text-red-700'],
                            'link_facebook'  => ['📘 Facebook', 'bg-indigo-100 text-indigo-700'],
                            default          => null,
                        };
                    ?>
                    <?php if($badge): ?>
                        <span class="px-2.5 py-1 text-xs font-medium rounded-full <?php echo e($badge[1]); ?>"><?php echo e($badge[0]); ?></span>
                    <?php endif; ?>
                <?php endif; ?>

                <span class="text-xs text-slate-400">
                    <?php echo e($berita->tanggal_publish?->format('d F Y') ?? $berita->created_at->format('d F Y')); ?>

                </span>

                <?php if($berita->user): ?>
                    <span class="text-xs text-slate-400">oleh <?php echo e($berita->user->name); ?></span>
                <?php endif; ?>
            </div>

            
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-slate-100 mb-6 leading-snug">
                <?php echo e($berita->judul); ?>

            </h1>

            
            <?php if(in_array($berita->media_tipe, ['link_youtube','link_facebook']) && $berita->media_link): ?>
            <div class="mb-5 flex items-center gap-2 p-3 bg-slate-50 dark:bg-slate-700/50 rounded-lg">
                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                <a href="<?php echo e($berita->media_link); ?>" target="_blank" rel="noopener noreferrer"
                   class="text-sm text-indigo-600 hover:underline break-all">
                    Buka di <?php echo e($berita->media_tipe === 'link_youtube' ? 'YouTube' : 'Facebook'); ?> →
                </a>
            </div>
            <?php endif; ?>

            
            <div class="prose prose-sm sm:prose max-w-none dark:prose-invert
                        prose-headings:font-semibold prose-a:text-indigo-600
                        prose-img:rounded-lg prose-img:shadow-sm">
                <?php echo nl2br(e($berita->isi)); ?>

            </div>

            
            <div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-700">
                <a href="<?php echo e(route('website.berita')); ?>"
                   class="inline-flex items-center gap-1.5 text-sm text-indigo-600 hover:text-indigo-800 font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke Berita
                </a>
            </div>
        </div>
    </article>

    
    <?php if($terkait->isNotEmpty()): ?>
    <div class="mt-10">
        <h2 class="text-base font-semibold text-slate-900 dark:text-slate-100 mb-4">Berita Terkait</h2>

        <div class="grid sm:grid-cols-3 gap-5">
            <?php $__currentLoopData = $terkait; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('website.berita.show', $item->slug)); ?>"
               class="group flex flex-col bg-white dark:bg-slate-800 rounded-xl border border-slate-200
                      dark:border-slate-700 shadow-sm overflow-hidden transition-all hover:shadow-md hover:-translate-y-1">

                
                <div class="relative h-32 bg-slate-100 dark:bg-slate-700 overflow-hidden">

                    <?php if($item->media_tipe === 'photo' && $item->media_file): ?>
                        <img src="<?php echo e($item->media_file_url); ?>" alt="<?php echo e($item->judul); ?>"
                             class="w-full h-full object-cover group-hover:scale-105 transition" loading="lazy">

                    <?php elseif($item->media_tipe === 'video' && $item->media_file): ?>
                        <?php if($item->media_thumbnail): ?>
                            <img src="<?php echo e($item->media_thumbnail_url); ?>" alt="<?php echo e($item->judul); ?>"
                                 class="w-full h-full object-cover group-hover:scale-105 transition" loading="lazy">
                        <?php else: ?>
                            <video src="<?php echo e($item->media_file_url); ?>" class="w-full h-full object-cover" muted preload="none"></video>
                        <?php endif; ?>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-8 h-8 rounded-full bg-black/50 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                        </div>

                    <?php elseif($item->media_tipe === 'link_youtube'): ?>
                        <img src="<?php echo e($item->media_thumbnail_url); ?>" alt="<?php echo e($item->judul); ?>"
                             class="w-full h-full object-cover group-hover:scale-105 transition" loading="lazy">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-8 h-8 rounded-full bg-red-600 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                        </div>

                    <?php elseif($item->media_tipe === 'link_facebook'): ?>
                        <?php if($item->media_thumbnail): ?>
                            <img src="<?php echo e($item->media_thumbnail_url); ?>" alt="<?php echo e($item->judul); ?>"
                                 class="w-full h-full object-cover group-hover:scale-105 transition" loading="lazy">
                        <?php else: ?>
                            <div class="w-full h-full bg-blue-700 flex items-center justify-center">
                                <svg class="w-8 h-8 text-white/40" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>
                                </svg>
                            </div>
                        <?php endif; ?>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                        </div>

                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12h6m-6 4h6M5 8h14M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="p-3 flex-1 flex flex-col">
                    <span class="text-xs text-slate-400 mb-1"><?php echo e($item->tanggal_publish?->format('d M Y')); ?></span>
                    <h3 class="text-xs font-semibold text-slate-900 dark:text-slate-100 line-clamp-2 mb-2 group-hover:text-indigo-600 transition-colors">
                        <?php echo e($item->judul); ?>

                    </h3>
                    <span class="text-xs text-indigo-600 hover:underline mt-auto">Baca →</span>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PA 3\smpn-kutime\resources\views/website/berita-detail.blade.php ENDPATH**/ ?>