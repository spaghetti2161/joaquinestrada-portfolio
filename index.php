<?php
/**
 * Joaquin Estrada — Portfolio
 * Subí imágenes a works/concept-art/ o works/3d/ y aparecen automáticamente.
 */

$IMG_EXTS = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'avif'];

function scan_gallery($dir, $category, $label, $exts) {
    if (!is_dir($dir)) return [];
    $items = [];
    $files = array_diff(scandir($dir), ['.', '..']);
    foreach ($files as $file) {
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (!in_array($ext, $exts)) continue;
        $name  = pathinfo($file, PATHINFO_FILENAME);
        $name  = preg_replace('/^\d+[-_\s]*/', '', $name);
        $title = ucwords(str_replace(['-', '_', '.'], ' ', strtolower($name)));
        $items[] = [
            'src'      => $dir . '/' . $file,
            'title'    => $title ?: $name,
            'category' => $category,
            'label'    => $label,
            'mtime'    => filemtime($dir . '/' . $file),
        ];
    }
    usort($items, function($a, $b) { return $b['mtime'] - $a['mtime']; });
    return $items;
}

function e($s) { return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }

$works_ca  = scan_gallery('works/concept-art', 'concept-art', 'Concept Art', $IMG_EXTS);
$works_3d  = scan_gallery('works/3d',          '3d',          '3D',          $IMG_EXTS);
$all_works = array_merge($works_ca, $works_3d);
$has_works = count($all_works) > 0;
$og_image  = $has_works ? 'https://joaquinestrada.com.ar/' . $all_works[0]['src'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0a0a0a">
    <title>Joaquin Estrada — Concept Artist</title>
    <meta name="description" content="Portfolio of Joaquin Estrada — Concept Artist &amp; Illustrator specializing in 2D and 3D art.">
    <meta name="author" content="Joaquin Estrada">

    <!-- Open Graph -->
    <meta property="og:title" content="Joaquin Estrada — Concept Artist">
    <meta property="og:description" content="Concept Art · Illustration · 3D">
    <meta property="og:url" content="https://joaquinestrada.com.ar">
    <meta property="og:type" content="website">
    <?php if ($og_image): ?>
    <meta property="og:image" content="<?= e($og_image) ?>">
    <?php endif; ?>

    <!-- Favicon: initials JE -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><rect width='32' height='32' fill='%23c8a96e'/><text x='50%25' y='50%25' text-anchor='middle' dominant-baseline='central' fill='%230a0a0a' font-family='Georgia,serif' font-size='13' font-weight='bold'>JE</text></svg>">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- ════════════════════════════════════
     NAV
════════════════════════════════════ -->
<nav id="nav">
    <div class="nav-inner">
        <a href="#" class="nav-logo" aria-label="Joaquin Estrada">JE</a>
        <ul class="nav-links">
            <li><a href="#work">Work</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
        <button class="nav-toggle" id="nav-toggle" aria-label="Open menu" aria-expanded="false">
            <span></span><span></span>
        </button>
    </div>
</nav>

<!-- Mobile nav overlay -->
<div class="nav-mobile" id="nav-mobile" aria-hidden="true">
    <a href="#work">Work</a>
    <a href="#about">About</a>
    <a href="#contact">Contact</a>
</div>

<!-- ════════════════════════════════════
     HERO
════════════════════════════════════ -->
<section id="hero">
    <div class="hero-content">
        <p class="hero-eyebrow">Portfolio</p>
        <h1 class="hero-name">
            <span>Joaquin</span>
            <span>Estrada</span>
        </h1>
        <div class="hero-line" aria-hidden="true"></div>
        <p class="hero-role">Concept Artist &amp; Illustrator</p>
    </div>
    <a href="#work" class="hero-scroll" aria-label="Scroll to work">
        <span class="scroll-dot" aria-hidden="true"></span>
    </a>
</section>

<!-- ════════════════════════════════════
     WORK
════════════════════════════════════ -->
<section id="work">
    <div class="container">

        <div class="section-top reveal">
            <h2 class="section-title">Work</h2>
            <div class="filters">
                <button class="filter-btn active" data-filter="all">All
                    <span class="filter-count" id="count-all"><?= count($all_works) ?></span>
                </button>
                <button class="filter-btn" data-filter="concept-art">Concept Art
                    <span class="filter-count" id="count-concept-art"><?= count($works_ca) ?></span>
                </button>
                <button class="filter-btn" data-filter="3d">3D
                    <span class="filter-count" id="count-3d"><?= count($works_3d) ?></span>
                </button>
            </div>
        </div>

        <?php if ($has_works): ?>
        <div class="gallery-grid" id="gallery">
            <?php foreach ($all_works as $i => $item): ?>
            <div class="gallery-item"
                 data-category="<?= e($item['category']) ?>"
                 data-label="<?= e($item['label']) ?>"
                 data-title="<?= e($item['title']) ?>"
                 data-src="<?= e($item['src']) ?>"
                 tabindex="0"
                 role="button"
                 aria-label="View: <?= e($item['title']) ?>">
                <div class="gallery-thumb">
                    <img
                        src="<?= e($item['src']) ?>"
                        alt="<?= e($item['title']) ?>"
                        loading="<?= $i < 6 ? 'eager' : 'lazy' ?>">
                    <div class="gallery-overlay" aria-hidden="true">
                        <span class="gallery-title"><?= e($item['title']) ?></span>
                        <span class="gallery-cat"><?= e($item['label']) ?></span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon" aria-hidden="true">◻</div>
            <p>No hay trabajos aún.</p>
            <p class="empty-hint">Subí imágenes a <code>works/concept-art/</code> o <code>works/3d/</code> y aparecerán aquí automáticamente.</p>
        </div>
        <?php endif; ?>

    </div>
</section>

<!-- ════════════════════════════════════
     ABOUT
════════════════════════════════════ -->
<section id="about">
    <div class="container">
        <div class="about-inner reveal">
            <h2 class="section-title">About</h2>
            <div class="about-body">
                <div class="about-text">
                    <p class="about-intro">Soy Joaquin Estrada, artista visual con foco en concept art, ilustración y 3D para entretenimiento.</p>
                    <p>Me especializo en diseño de personajes, entornos y criaturas. Actualmente ampliando mi trabajo hacia la producción 3D con ZBrush y Blender.</p>
                    <p>Disponible para proyectos freelance y colaboraciones.</p>
                </div>
                <div class="about-skills">
                    <div class="skill-col">
                        <h3>2D</h3>
                        <ul>
                            <li>Concept Art</li>
                            <li>Character Design</li>
                            <li>Environment Art</li>
                            <li>Photoshop · Procreate</li>
                        </ul>
                    </div>
                    <div class="skill-col">
                        <h3>3D</h3>
                        <ul>
                            <li>ZBrush</li>
                            <li>Blender</li>
                            <li>Sculpting</li>
                            <li>Rendering</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════════════════
     CONTACT
════════════════════════════════════ -->
<section id="contact">
    <div class="container">
        <div class="contact-inner reveal">
            <h2 class="section-title">Contact</h2>
            <p class="contact-sub">Open to freelance projects, collaborations &amp; full-time opportunities.</p>
            <a href="mailto:tu@email.com" class="contact-btn">Get in touch</a>
            <div class="social-links">
                <!-- Reemplazá # con tus links reales -->
                <a href="#" target="_blank" rel="noopener">ArtStation</a>
                <a href="#" target="_blank" rel="noopener">Instagram</a>
                <a href="#" target="_blank" rel="noopener">LinkedIn</a>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════════════════
     FOOTER
════════════════════════════════════ -->
<footer>
    <div class="container">
        <p>&copy; <?= date('Y') ?> Joaquin Estrada &mdash; All rights reserved</p>
    </div>
</footer>

<!-- ════════════════════════════════════
     LIGHTBOX
════════════════════════════════════ -->
<div id="lightbox" role="dialog" aria-modal="true" aria-label="Image viewer" aria-hidden="true">
    <div class="lb-backdrop"></div>

    <button class="lb-close" aria-label="Close">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
            <path d="M18 6L6 18M6 6l12 12"/>
        </svg>
    </button>

    <button class="lb-nav lb-prev" aria-label="Previous">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
            <path d="M15 18l-6-6 6-6"/>
        </svg>
    </button>
    <button class="lb-nav lb-next" aria-label="Next">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
            <path d="M9 18l6-6-6-6"/>
        </svg>
    </button>

    <div class="lb-content">
        <img class="lb-image" src="" alt="">
        <div class="lb-info">
            <span class="lb-title"></span>
            <span class="lb-cat"></span>
        </div>
    </div>
</div>

<script src="js/main.js"></script>
</body>
</html>
