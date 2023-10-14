<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>File Manager</h1>
        <div class="row">
            <div class="col-md-6">
                <?php
                function get_folders()
                {
                    $folders = glob('*', GLOB_ONLYDIR);
                    echo '<ul class="list-group">';
                    foreach ($folders as $folder) {
                        echo '<li class="list-group-item"><a href="index.php?folder=' . $folder . '">' . $folder . '</a></li>';
                    }
                    echo '</ul>';
                }

                function open_folder($folder)
                {
                    $folders = glob($folder . '/*', GLOB_ONLYDIR);
                    echo '<ul class="list-group">';
                    foreach ($folders as $subfolder) {
                        echo '<li class="list-group-item"><a href="index.php?folder=' . $subfolder . '">' . basename($subfolder) . '</a></li>';
                    }
                    echo '</ul>';
                }

                function get_files($folder)
                {
                    $files = glob($folder . '/*.*');
                    echo '<ul class="list-group">';
                    foreach ($files as $file) {
                        $info = pathinfo($file);
                        $basename = $info['basename'];
                        echo '<li class="list-group-item"><a href="index.php?file=' . $file . '">' . $basename . '</a></li>';
                    }
                    echo '</ul>';
                }

                if (isset($_GET['folder'])) {
                    $requestedFolder = $_GET['folder'];

                    // Check if the requested folder exists and is a directory
                    if (is_dir($requestedFolder)) {
                        open_folder($requestedFolder);
                        get_files($requestedFolder);
                    } else {
                        echo '<div class="alert alert-danger">Invalid folder: ' . $requestedFolder . '</div>';
                    }
                } else {
                    get_folders();
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
