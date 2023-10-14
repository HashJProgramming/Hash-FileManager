<!DOCTYPE html>
<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="assets/css/prism.css">
</head>

<body>
    <div class="container">
        <h1>Hash FileManager</h1>
        <div class="container">
            <div class="section">
                <?php
                function getFileIcon($fileExtension)
                {
                    $icon = 'file'; // Default icon for unknown file types

                    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
                    $textExtensions = ['txt', 'log', 'xml', 'html', 'css', 'js', 'php', 'c', 'cpp', 'java', 'py', 'json', 'md'];

                    if (in_array($fileExtension, $imageExtensions)) {
                        $icon = 'image';
                    } elseif (in_array($fileExtension, $textExtensions)) {
                        $icon = 'file-code';
                    }

                    return $icon;
                }

                function getFolderIcon()
                {
                    return 'folder';
                }

                function get_folders()
                {
                    $folders = glob('*', GLOB_ONLYDIR);
                    echo '<ul class="list-group">';
                    foreach ($folders as $folder) {
                        echo '<li class="list-group-item">';
                        echo '<a href="index.php?folder=' . $folder . '"><i class="fas fa-' . getFolderIcon() . '"></i> ' . $folder . '</a>';
                        echo '</li>';
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
                    $textExtensions = ['txt', 'log', 'xml', 'html', 'css', 'js', 'php', 'c', 'cpp', 'java', 'py', 'json', 'md'];
                    $files = glob($folder . '/*.*');
                    echo '<ul class="list-group">';
                    foreach ($files as $file) {
                        $info = pathinfo($file);
                        $basename = $info['basename'];
                        $fileExtension = strtolower($info['extension']);
                        $icon = getFileIcon($fileExtension);
                        echo '<li class="list-group-item">';
                        echo '<a href="index.php?file=' . $file . '"><i class="fas fa-' . $icon . '"></i> ' . $basename . '</a>';
                        if (in_array($fileExtension, $textExtensions)) {
                            echo '<a href="index.php?file='.$file.'" data-toggle="modal" data-target="#editModal" class="btn btn-primary btn-sm">Edit</a>';
                        }
                        echo ' <a href="index.php?delete=' . $file . '" class="btn btn-danger btn-sm">Delete</a>';
                        echo '</li>';
                    }
                    echo '</ul>';
                }

                if (isset($_GET['folder'])) {
                    $requestedFolder = $_GET['folder'];

                    if (is_dir($requestedFolder)) {
                        open_folder($requestedFolder);
                        get_files($requestedFolder);
                    } else {
                        echo '<div class="alert alert-danger">Invalid folder: ' . $requestedFolder . '</div>';
                    }
                } else if (isset($_GET['file'])) {
                    $requestedFile = $_GET['file'];
                    if (file_exists($requestedFile)) {
                        $info = pathinfo($requestedFile);
                        $textExtensions = ['txt', 'log', 'xml', 'html', 'css', 'js', 'php', 'c', 'cpp', 'java', 'py', 'json', 'md'];
                        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
                        $fileExtension = strtolower($info['extension']);
                        if (in_array($fileExtension, $textExtensions)) {
                            echo '<pre><code class="language-' . $fileExtension . '">' . htmlspecialchars(file_get_contents($requestedFile)) . '</code></pre>';
                        } elseif (in_array($fileExtension, $imageExtensions)) {
                            echo '<img src="' . $requestedFile . '" class="img-fluid" alt="Image">';
                        } else {
                            echo '<div class="alert alert-warning">Unsupported file type: ' . $fileExtension . '</div>';
                        }
                    } else {
                        echo '<div class="alert alert-danger">Invalid file: ' . $requestedFile . '</div>';
                    }
                } else {
                    get_folders();
                }


                if (isset($_GET['delete'])) {
                    // ... code for deletion ...
                }
                ?>

                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2/dist/sweetalert2.all.min.js"></script>
                <script src="assets/js/prism.js"></script>
</body>

</html>