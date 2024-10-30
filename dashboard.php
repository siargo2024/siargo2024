<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('location: index.php');
}

require_once 'php/session.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="./libs/bootstrap.min.css" />
  <link rel="stylesheet" href="./libs/DataTables/datatables.css" />
</head>

<body>
  <nav class="navbar bg-dark navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">LOGO</a>
      <a style="cursor: pointer;" class="nav-item text-decoration-none text-white" data-bs-toggle="modal" data-bs-target="#add-post-modal">Add Post</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Welcome <?= $email ?></a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="php/logout.php">Logout</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Start Add Post Modal -->
  <div class="modal fade" id="add-post-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
          <button type="button" class="bg-white btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="add-post-form">
            <input type="hidden" name="uid" value="<?= $uid?>">
            <label for="" class="form-label">Post title:</label>
            <input type="text" name="title" class="form-control mb-3" required>

            <label for="" class="form-label">Post Content:</label>
            <textarea name="content" class="form-control mb-3" cols="30" rows="10" required></textarea>

            <input type="submit" id="add-post-btn" class="btn btn-primary w-100" value="Add Post">
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- End Add Post Modal -->
  
  <!-- Start Edit Post Modal -->
  <div class="modal fade" id="edit-post-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">Update Post</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="edit-post-form">
            <input type="hidden" name="edit-post-id" id="edit-post-id">
            <label for="" class="form-label">Post title:</label>
            <input type="text" name="edit-title" class="form-control mb-3" id="edit-title" required>

            <label for="" class="form-label">Post Content:</label>
            <textarea name="edit-content" class="form-control mb-3" id="edit-content" cols="30" rows="10" required></textarea>

            <input type="submit" id="update-post-btn" class="btn btn-warning w-100" value="Update Post">
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- End Edit Post Modal -->

  <div class="container">
    <div class="row">
      <div class="col-12 mt-5">
        <div class="card rounded-0 shadow-sm mt-5">
          <div class="card-header bg-success rounded-0">
            <span class="fs-5 text-white">Posts</span>
          </div>
          <div class="card-body rounded-0 table-responsive" id="data-wrapper">
            <div class="d-flex align-items-center justify-content-center">
              <div class="spinner-border text-secondary" role="status"></div>
              <h2 class="text-secondary ms-2">Loading...</h2>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="./libs/bootstrap.bundle.min.js"></script>
  <script src="./libs/jquery.min.js"></script>
  <script src="./libs/DataTables/datatables.js"></script>
  <script src="./libs/sweetalert2.all.min.js"></script>
  <script>
    $(document).ready(function() {
      // Sweetalert2 function
      function swal(icon, title, text) {
        Swal.fire({
          icon,
          title,
          text
        })
      }

      // Add Post
      $('#add-post-btn').click(function(e) {
        if ($('#add-post-form')[0].checkValidity()) {
          e.preventDefault()

          $(this).attr('disabled', true)
          $(this).val('Adding Post...')

          $.ajax({
            url: 'php/action.php',
            method: 'post',
            data: $('#add-post-form').serialize() + '&action=addPost',
            success: res => {
              // console.log(res)

              if (res === '1') {
                swal('success', 'Successfully Added!', 'Post was added!')
                $('#add-post-modal').modal('hide')
                $('#add-post-form')[0].reset()
                fetchPosts()
              } else {
                swal('error', 'Oops!', 'Something went wrong. Please try again.')
              }
              $('#add-post-btn').attr('disabled', false)
              $('#add-post-btn').val('Add Post')
            }
          })
        }
      })

      // Fetch Posts
      function fetchPosts() {
        $.ajax({
          url: 'php/action.php',
          method: 'post',
          data: {
            action: 'fetchPosts',
            uid: '<?= json_encode($uid) ?>'
          },
          success: res => {
            let posts = JSON.parse(res)

            if (posts.length < 1) {
              $("#data-wrapper").html(`
                <h4 class="text-center text-secondary fst-italic">No Posts.</h4>
              `)
              return
            }

            let postsMapped = posts.map(post => `
              <tr>
                <td>${post.post_title}</td>
                <td>${post.post_content.substr(0, 60)}...</td>
                <td>
                  <a href="#" title="Edit" class="btn btn-warning btn-sm edit-post text-decoration-none" id="edit-post-${post.post_id}" data-bs-toggle="modal" data-bs-target="#edit-post-modal">
                    Edit
                  </a>
                  <a href="#" title="Delete" class="btn btn-danger btn-sm del-post text-decoration-none" id="del-post-${post.post_id}">
                    Delete
                  </a>
                </td>
              </tr>
            `)

            let postsJoined = postsMapped.join('')
            let postTable = `
              <table class="table table-striped table-bordered w-100" id="post-table">
                <thead>
                  <tr>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  ${postsJoined}
                </tbody>
              </table>
            `
            $('#data-wrapper').html(postTable)
            new DataTable('#post-table');
          }
        })
      }
      fetchPosts()

      // Delete Post
      $('body').on('click', '.del-post', function() {
        let id = $(this).attr('id')
        id = id.substr(9)

        Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#198754',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: 'php/action.php',
              method: 'post',
              data: {
                action: 'delPost',
                id
              },
              success: res => {
                if (res === '1') {
                  fetchPosts();
                  Swal.fire({
                    title: "Deleted!",
                    text: "Your post has been deleted.",
                    icon: "success"
                  });
                }
              }
            })
          }

        })
      })

      // Edit Post
      $('body').on('click', '.edit-post', function() {
        let id = $(this).attr('id')
        id = id.substr(10)

        $.ajax({
          url: 'php/action.php',
          method: 'post',
          data: {
            action: 'fetchPost',
            id
          },
          success: res => {
            let post = JSON.parse(res)

            $('#edit-post-id').val(post.post_id)
            $('#edit-title').val(post.post_title)
            $('#edit-content').val(post.post_content)
          }
        })
      })

      // Update Post
      $('#update-post-btn').click(function(e) {
        if ($('#edit-post-form')[0].checkValidity()) {
          e.preventDefault()

          $(this).attr('disabled', true)
          $(this).val('Updating Post...')

          $.ajax({
            url: 'php/action.php',
            method: 'post',
            data: $('#edit-post-form').serialize() + '&action=updatePost',
            success: res => {
              // console.log(res)
              if (res === '1') {
                swal('success', 'Successfully Updated!', 'Post was updated!')
                $('#edit-post-modal').modal('hide')
                $('#edit-post-form')[0].reset()
                fetchPosts()
              } else {
                swal('error', 'Oops!', 'Something went wrong. Please try again.')
              }
              $('#update-post-btn').attr('disabled', false)
              $('#update-post-btn').val('Update Post')
            }
          })
        }
      })
    })
  </script>
</body>

</html>