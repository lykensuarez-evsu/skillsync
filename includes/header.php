<?php
// Header and Navigation
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($project) ? htmlspecialchars($project['name']) : 'SkillSync'; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="topbar">
        <div class="container topbar-inner">
            <div class="brand">
                <div class="brand-mark">S</div>
                <div>
                    <?php echo isset($project) ? htmlspecialchars($project['name']) : 'SkillSync'; ?>
                    <small><?php echo isset($project) ? htmlspecialchars($project['subtitle']) : 'Internship Matching System'; ?></small>
                </div>
            </div>
            <nav class="nav">
                <a href="#overview">Overview</a>
                <a href="#features">Features</a>
                <a href="#workflow">Workflow</a>
                <a href="#demo">System Demo</a>
            </nav>
        </div>
    </header>

    <main>
