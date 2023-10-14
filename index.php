<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.0/themes/prism.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.2.7/dist/sweetalert2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.0/prism.js"></script>
</head>

<body>
    <div class="container">
        <h1>File Manager</h1>
        <div class="row">
            <div class="col-md-6">
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
                            echo ' <a href="index.php?edit=' . $file . '" class="btn btn-primary btn-sm">Edit</a>';
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
                } elseif (isset($_GET['file'])) {
                    $requestedFile = $_GET['file'];

                    if (file_exists($requestedFile)) {
                        $info = pathinfo($requestedFile);
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

                if (isset($_GET['edit'])) {
                    $fileToEdit = $_GET['edit'];
                    $info = pathinfo($fileToEdit);
                    $fileExtension = strtolower($info['extension']);

                    if (in_array($fileExtension, $textExtensions)) {
                        echo '<button class="btn btn-primary btn-sm" onclick="openEditorModal(\'' . $fileToEdit . '\')">Edit</button>';
                    } else {
                        echo '<div class="alert alert-warning">Editing is not supported for this file type: ' . $fileExtension . '</div>';
                    }
                }

                if (isset($_GET['delete'])) {
                    // ... Previous code for deletion ...
                }
                ?>

                <!-- Modal for Editing Files -->
                <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Edit File Content</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <textarea id="fileContent" class="form-control" rows="10"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" onclick="saveFileContent()">Save Changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.2.7/dist/sweetalert2.all.min.js"></script>
    <script>
        function openEditorModal(fileToEdit) {
            $.get(fileToEdit, function(data) {
                $('#fileContent').val(data);
                $('#editModal').modal('show');
            });
        }

        function saveFileContent() {
            const editedContent = $('#fileContent').val();
            // Implement code to save the edited content to the file.

            // For the sake of this example, display a SweetAlert success message.
            Swal.fire({
                icon: 'success',
                title: 'File Saved',
                text: 'Your changes have been saved successfully.',
                timer: 1500,
                showConfirmButton: false
            });

            $('#editModal').modal('hide');
        }
    </script>
</body>

</html>