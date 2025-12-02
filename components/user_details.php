<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<?php
$user = $_SESSION['user'];
if (!isset($_COOKIE['auth']))
  Header("Location: /sign-in");

// validate logged session and user session
if (!isset($_SESSION['logged']) || !isset($_SESSION['user']))
  Header("Location: /sign-in");
?>

<main>
  <div class="flex_default flex_justify_start flex_align_center">
    <h1 style="font-weight: 300; margin: 0 0 20px 10px">Dashboard: <?php echo $user['name'];
    ?></h1>
    <div class="profile_image">
      <?php
      $path_tmp = $user['image_path'];
      $path;
      if ($path_tmp) {
        $path = $path_tmp;
      } else {
        $path = "../images/logo.png";
      }
      ?>
      <img src="<?php echo $path ?>" alt="<?php $user['name'] . "'s profile image" ?>">
    </div>
    <div id="upload_trigger">
      <ion-icon name="image-outline" style="font-size: 30px; cursor: pointer;"></ion-icon>
    </div>
  </div>

  <p style="font-weight: 300; margin: 0 0 20px 10px">Email address: <?php echo $user['email'] ?></p>



  <div class="upload_modal flex_default flex_align_center remove_upload_modal">
    <div class="quit_modal" style="cursor: pointer;">
      <ion-icon name="close-outline"></ion-icon>
    </div>
    <form action="/upload-profile-image" method="post" enctype="multipart/form-data">
      <input type="email" value="<?php echo $user['email'] ?>" id="email" name="email" hidden>
      <input type="file" name="file" id="file">
      <label for="file" class="flex_default flex_justify_center flex_align_center" style="cursor: pointer">
        <ion-icon id="image_upload_icon" name="image-outline" style="font-size: 100px; cursor: pointer;"></ion-icon>
      </label>
      <button type="submit" class="submit_color" id="submit_file_image" style="cursor: pointer;"
        disabled>upload</button>
    </form>
  </div>
</main>
<script src="../javascript/DOM_profile_photo.js"></script>