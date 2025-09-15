<?php $__env->startSection('title', $project->project_name . ' - Portfolio'); ?>

<?php $__env->startSection('isi'); ?>
<div class="project-detail-page">
    <!-- Hero Section -->
    <div class="project-hero">
        <div class="container">
            <div class="hero-content">
                <div class="breadcrumb">
                    <a href="<?php echo e(route('home')); ?>">Home</a>
                    <span>/</span>
                    <a href="<?php echo e(route('portfolio.all')); ?>">Portfolio</a>
                    <span>/</span>
                    <span><?php echo e($project->project_name); ?></span>
                </div>
                
                <h1 class="project-title"><?php echo e($project->project_name); ?></h1>
                <p class="project-category"><?php echo e($project->project_category); ?></p>
                <p class="project-summary"><?php echo e($project->summary_description); ?></p>
                
                <div class="project-meta">
                    <div class="meta-item">
                        <span class="meta-label">Client</span>
                        <span class="meta-value"><?php echo e($project->client_name); ?></span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Location</span>
                        <span class="meta-value"><?php echo e($project->location); ?></span>
                    </div>
                    <?php if($project->url_project): ?>
                    <div class="meta-item">
                        <span class="meta-label">Website</span>
                        <a href="<?php echo e($project->url_project); ?>" target="_blank" class="meta-link">Visit Project</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Project Images Gallery -->
    <?php
        $images = $project->images ? json_decode($project->images, true) : [];
    ?>
    <?php if(!empty($images)): ?>
    <div class="project-gallery">
        <div class="container">
            <div class="gallery-main">
                <img id="mainImage" src="<?php echo e(asset('images/projects/' . ($project->featured_image ?? $images[0]))); ?>" 
                     alt="<?php echo e($project->project_name); ?>" class="main-image">
            </div>
            <?php if(count($images) > 1): ?>
            <div class="gallery-thumbnails">
                <?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <img src="<?php echo e(asset('images/projects/' . $image)); ?>" 
                         alt="<?php echo e($project->project_name); ?> <?php echo e($index + 1); ?>" 
                         class="thumbnail <?php echo e($image == ($project->featured_image ?? $images[0]) ? 'active' : ''); ?>"
                         onclick="changeMainImage('<?php echo e(asset('images/projects/' . $image)); ?>', this)">
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Project Details -->
    <div class="project-details">
        <div class="container">
            <div class="details-content">
                <h2 class="section-title">Project Details</h2>
                <div class="project-description">
                    <?php echo $project->description; ?>

                </div>
            </div>
        </div>
    </div>

    <!-- Navigation to Other Projects -->
    <div class="project-navigation">
        <div class="container">
            <h2 class="section-title">Other Projects</h2>
            <div class="related-projects">
                <?php
                    $relatedProjects = DB::table('project')
                        ->where('id_project', '!=', $project->id_project)
                        ->where('project_category', $project->project_category)
                        ->where('status', 'Active')
                        ->orderBy('sequence', 'asc')
                        ->orderBy('created_at', 'desc')
                        ->take(3)
                        ->get();
                    
                    if($relatedProjects->count() < 3) {
                        $moreProjects = DB::table('project')
                            ->where('id_project', '!=', $project->id_project)
                            ->where('project_category', '!=', $project->project_category)
                            ->where('status', 'Active')
                            ->orderBy('sequence', 'asc')
                            ->orderBy('created_at', 'desc')
                            ->take(3 - $relatedProjects->count())
                            ->get();
                        $relatedProjects = $relatedProjects->concat($moreProjects);
                    }
                ?>
                
                <?php $__currentLoopData = $relatedProjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relatedProject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $relatedImages = $relatedProject->images ? json_decode($relatedProject->images, true) : [];
                        $relatedFeaturedImage = $relatedProject->featured_image ?? ($relatedImages[0] ?? null);
                    ?>
                    <div class="related-project-card">
                        <div class="related-project-image">
                            <?php if($relatedFeaturedImage): ?>
                                <img src="<?php echo e(asset('images/projects/' . $relatedFeaturedImage)); ?>" alt="<?php echo e($relatedProject->project_name); ?>">
                            <?php else: ?>
                                <img src="<?php echo e(asset('images/placeholder/project-placeholder.jpg')); ?>" alt="<?php echo e($relatedProject->project_name); ?>">
                            <?php endif; ?>
                        </div>
                        <div class="related-project-content">
                            <span class="related-project-category"><?php echo e($relatedProject->project_category); ?></span>
                            <h3 class="related-project-title"><?php echo e($relatedProject->project_name); ?></h3>
                            <p class="related-project-summary"><?php echo e(Str::limit($relatedProject->summary_description, 80)); ?></p>
                            <a href="<?php echo e(route('project.public.show', $relatedProject->slug_project)); ?>" class="related-project-link">View Project</a>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            
            <div class="back-to-portfolio">
                <a href="<?php echo e(route('portfolio.all')); ?>" class="back-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5m7-7l-7 7 7 7"/>
                    </svg>
                    Back to All Projects
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.project-detail-page {
    background: #0f172a;
    color: white;
    min-height: 100vh;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Hero Section */
.project-hero {
    padding: 120px 0 80px;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(245, 158, 11, 0.1) 100%);
    position: relative;
}

.project-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(ellipse at center, rgba(59, 130, 246, 0.1) 0%, transparent 70%);
}

.hero-content {
    position: relative;
    z-index: 2;
    text-align: center;
    max-width: 800px;
    margin: 0 auto;
}

.breadcrumb {
    margin-bottom: 30px;
    font-size: 0.9rem;
    color: #94a3b8;
}

.breadcrumb a {
    color: #f59e0b;
    text-decoration: none;
    transition: color 0.3s ease;
}

.breadcrumb a:hover {
    color: #eab308;
}

.breadcrumb span {
    margin: 0 10px;
}

.project-title {
    font-size: 3rem;
    font-weight: 700;
    color: #f59e0b;
    margin-bottom: 15px;
    line-height: 1.2;
}

.project-category {
    font-size: 1.1rem;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-bottom: 20px;
}

.project-summary {
    font-size: 1.2rem;
    color: #e2e8f0;
    line-height: 1.6;
    margin-bottom: 40px;
}

.project-meta {
    display: flex;
    justify-content: center;
    gap: 40px;
    flex-wrap: wrap;
}

.meta-item {
    text-align: center;
}

.meta-label {
    display: block;
    font-size: 0.9rem;
    color: #94a3b8;
    margin-bottom: 5px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.meta-value {
    font-size: 1.1rem;
    color: white;
    font-weight: 500;
}

.meta-link {
    color: #f59e0b;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
}

.meta-link:hover {
    color: #eab308;
    text-decoration: underline;
}

/* Gallery Section */
.project-gallery {
    padding: 80px 0;
    background: rgba(15, 23, 42, 0.5);
}

.gallery-main {
    margin-bottom: 30px;
    text-align: center;
}

.main-image {
    max-width: 100%;
    height: auto;
    max-height: 600px;
    border-radius: 15px;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
    transition: transform 0.3s ease;
}

.gallery-thumbnails {
    display: flex;
    justify-content: center;
    gap: 15px;
    flex-wrap: wrap;
}

.thumbnail {
    width: 100px;
    height: 70px;
    object-fit: cover;
    border-radius: 8px;
    cursor: pointer;
    opacity: 0.6;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.thumbnail:hover,
.thumbnail.active {
    opacity: 1;
    border-color: #f59e0b;
    transform: scale(1.05);
}

/* Project Details */
.project-details {
    padding: 80px 0;
}

.details-content {
    max-width: 900px;
    margin: 0 auto;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #f59e0b;
    text-align: center;
    margin-bottom: 50px;
    position: relative;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: -15px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, #f59e0b, #eab308);
    border-radius: 2px;
}

.project-description {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #e2e8f0;
}

.project-description h1,
.project-description h2,
.project-description h3 {
    color: #f59e0b;
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.project-description p {
    margin-bottom: 1.5rem;
}

.project-description img {
    max-width: 100%;
    height: auto;
    border-radius: 10px;
    margin: 2rem 0;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

.project-description ul,
.project-description ol {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
}

.project-description li {
    margin-bottom: 0.5rem;
}

.project-description blockquote {
    border-left: 4px solid #f59e0b;
    padding-left: 1.5rem;
    margin: 2rem 0;
    font-style: italic;
    color: #94a3b8;
}

.project-description table {
    width: 100%;
    border-collapse: collapse;
    margin: 2rem 0;
    background: rgba(15, 23, 42, 0.5);
    border-radius: 10px;
    overflow: hidden;
}

.project-description th,
.project-description td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid rgba(245, 158, 11, 0.2);
}

.project-description th {
    background: rgba(245, 158, 11, 0.1);
    font-weight: 600;
    color: #f59e0b;
}

/* Related Projects */
.project-navigation {
    padding: 80px 0;
    background: rgba(15, 23, 42, 0.5);
}

.related-projects {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    margin-bottom: 60px;
}

.related-project-card {
    background: rgba(15, 23, 42, 0.8);
    backdrop-filter: blur(15px);
    border-radius: 15px;
    border: 2px solid rgba(245, 158, 11, 0.2);
    overflow: hidden;
    transition: all 0.3s ease;
}

.related-project-card:hover {
    transform: translateY(-5px);
    border-color: #f59e0b;
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
}

.related-project-image {
    height: 200px;
    overflow: hidden;
}

.related-project-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.related-project-card:hover .related-project-image img {
    transform: scale(1.1);
}

.related-project-content {
    padding: 25px;
}

.related-project-category {
    color: #f59e0b;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 10px;
    display: block;
}

.related-project-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: white;
    margin-bottom: 10px;
    line-height: 1.3;
}

.related-project-summary {
    color: #94a3b8;
    font-size: 0.95rem;
    line-height: 1.5;
    margin-bottom: 20px;
}

.related-project-link {
    background: linear-gradient(45deg, #f59e0b, #eab308);
    color: #0f172a;
    padding: 10px 20px;
    border-radius: 20px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    display: inline-block;
}

.related-project-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(245, 158, 11, 0.4);
    color: #0f172a;
    text-decoration: none;
}

.back-to-portfolio {
    text-align: center;
}

.back-btn {
    background: rgba(15, 23, 42, 0.8);
    border: 2px solid #f59e0b;
    color: #f59e0b;
    padding: 15px 30px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.back-btn:hover {
    background: #f59e0b;
    color: #0f172a;
    transform: translateY(-2px);
    text-decoration: none;
}

.back-btn svg {
    width: 20px;
    height: 20px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .project-title {
        font-size: 2.2rem;
    }
    
    .section-title {
        font-size: 2rem;
    }
    
    .project-meta {
        gap: 20px;
    }
    
    .meta-item {
        min-width: 120px;
    }
    
    .gallery-thumbnails {
        gap: 10px;
    }
    
    .thumbnail {
        width: 80px;
        height: 60px;
    }
    
    .related-projects {
        grid-template-columns: 1fr;
        gap: 20px;
    }
}

@media (max-width: 480px) {
    .project-hero {
        padding: 100px 0 60px;
    }
    
    .project-details,
    .project-navigation {
        padding: 60px 0;
    }
    
    .container {
        padding: 0 15px;
    }
    
    .project-title {
        font-size: 1.8rem;
    }
    
    .project-meta {
        flex-direction: column;
        gap: 15px;
    }
}
</style>

<script>
function changeMainImage(imageSrc, thumbnail) {
    const mainImage = document.getElementById('mainImage');
    const thumbnails = document.querySelectorAll('.thumbnail');
    
    // Update main image
    mainImage.src = imageSrc;
    
    // Update active thumbnail
    thumbnails.forEach(thumb => thumb.classList.remove('active'));
    thumbnail.classList.add('active');
}

// Smooth scroll for internal links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.web', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\ALI_PORTFOLIO\resources\views/project-detail.blade.php ENDPATH**/ ?>