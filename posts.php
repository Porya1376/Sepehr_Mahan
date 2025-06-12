<?php
global $connection;
require 'includes/database.php';

// check token
if (!isset($_COOKIE['remember_token'])) {
    header('location: login.php');
}
$token = $_COOKIE['remember_token'];

// reach to userid from users then reach the posts
$query = "SELECT * FROM users WHERE remember_token = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param('s', $token);

if ($stmt->execute() && $user = $stmt->get_result()->fetch_assoc()) {
    $query = "SELECT * FROM posts WHERE user_id = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param('s', $user['id']);
    $stmt->execute();
    $posts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
} else {
    header('location: login.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post Page</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">

</head>
<body>

<div class="container my-5">
    <button type="button" class="btn btn-sm btn-primary me-2" data-bs-toggle="modal" data-bs-target="#createPostModal">
        Create Post
    </button>

    <h2 class="mb-4 mt-4">All Blog Posts</h2>
    <div class="row g-4">

        <?php
        foreach ($posts as $post) {
            ?>
            <div class="col-md-4" id="cards">
                <div class="card h-100 shadow-sm">
                    <img src="storage/<?= $post['image_path'] ?>" class="card-img-top" alt="Blog Image">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= $post['title'] ?></h5>
                        <p class="card-text"><?= $post['body'] ?></p>
                        <div class="mt-auto">
                            <a href="#" class="btn btn-sm btn-success"
                               onclick="editPost(<?= $post['id'] ?>)">Edit</a>
                            <a href="#" class="btn btn-sm btn-danger"
                               onclick="deletePost(<?= $post['id'] ?>)">Delete</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php }
        ?>

    </div>

</div>

<div class="modal fade" id="createPostModal" tabindex="-1" aria-labelledby="createPostModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="createPostForm" enctype="multipart/form-data" class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="createPostModalLabel">Create New Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" name="title" id="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="body" class="form-label">Body</label>
                    <textarea name="body" id="body" class="form-control" rows="4" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Image</label>
                    <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="createPost()">Create</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="editPostModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="editPostForm" class="modal-content" enctype="multipart/form-data">
            <div class="modal-header">
                <h5 class="modal-title">Edit Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="editPostId">
                <input type="text" name="title" id="editTitle" class="form-control mb-2" placeholder="Title" required>
                <textarea name="body" id="editBody" class="form-control mb-2" rows="4" placeholder="Body" required></textarea>
                <input type="file" name="image" id="editImage" class="form-control" accept="image/*">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="updatePost()">Update</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!--delete-->
<script>
    function deletePost(id) {

        $.post('delete.php', {id: id})
            .done(function (response) {
                const obj = JSON.parse(response);
                alert(obj.message);
                if (obj.success) {
                    $(`[onclick="deletePost(${id})"]`).closest('.col-md-4').remove();
                }
            })
    }
</script>
<!--create-->
<script>
    function createPost() {
        const form = $('#createPostForm')[0];
        const formData = new FormData(form);

        $.ajax({
            url: 'create.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false
        })
            .done(function (response) {
                alert(response.message);
                if (response.success) {
                    $('.row.g-4').prepend(`
                    <div class="col-md-4" id="">
                        <div class="card h-100 shadow-sm">
                            <img src="storage/${response.post.image_path}" class="card-img-top" alt="Blog Image">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">${response.post.title}</h5>
                                <p class="card-text">${response.post.body}</p>
                                <div class="mt-auto">
                                    <a href="#" class="btn btn-sm btn-primary me-2">Edit</a>
                                    <a href="#" class="btn btn-sm btn-danger" onclick="deletePost(${response.post.id})">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
                    $('#createPostForm')[0].reset();
                    const modalEl = document.getElementById('createPostModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    modal.hide();
                }
            })
            .fail(function (xhr) {
                console.error(xhr.responseText);
                alert('Something went wrong while creating the post.');
            });
    }
</script>
<!--edit-->
<script>
    function editPost(id) {
        // Send an AJAX request to get_post.php to retrieve post data
        $.get('get_post.php', { id }, function(post) {
            $('#editPostId').val(post.id);
            $('#editTitle').val(post.title);
            $('#editBody').val(post.body);
            new bootstrap.Modal('#editPostModal').show();
        }, 'json');
    }

    function updatePost() {
        const form = $('#editPostForm')[0];
        const formData = new FormData(form);

        $.ajax({
            url: 'update.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false
        }).done(function (response) {
            try {
                const data = JSON.parse(response);
                alert(data.message);

                if (data.success) {
                    $('#editPostModal').modal('hide');

                    // Update the post card
                    const postCard = $(`[onclick="editPost(${data.post.id})"]`).closest('.col-md-4');
                    postCard.find('.card-title').text(data.post.title);
                    postCard.find('.card-text').text(data.post.body);
                    postCard.find('img').attr('src', `storage/${data.post.image_path}`);

                    $('#editPostForm')[0].reset();
                    const modalEl = document.getElementById('editPostModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    modal.hide();
                }
            } catch (err) {
                alert("Update failed.");
            }
        }).fail(function () {
            alert("Something went wrong.");
        });
    }
</script>


</body>
</html>
