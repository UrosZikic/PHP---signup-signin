<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<?php
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
?>
<main>
  <table>
    <tr>
      <th>Avatar</th>
      <th>Name</th>
      <th>Email</th>
      <th>Status</th>
      <th>action</th>
    </tr>
    <?php
    if ($request === "/get-users") {
      foreach ($users as $user) {
        if ($user['role'] !== 'admin' && $user['soft_deleted'] !== 1) { ?>
          <tr>
            <td><img class="profile_image"
                src="<?php echo $user['image_path'] ? $user['image_path'] : '../images/logo.png' ?>"
                alt="<?php echo $user['name'] . "'s profile image" ?>"></td>
            <td><?php echo $user['name'] ?></td>
            <td><?php echo $user['email'] ?></td>
            <td style="color: var(--success-color)">active</td>
            <td>
              <!-- <a href="/admin-edit-user">
                <ion-icon name="pencil-sharp"></ion-icon>
              </a> -->
              <a href="/admin-delete-user?user=<?php echo $user['email'] ?>">
                <ion-icon name="trash-outline"></ion-icon>
              </a>
            </td>
          </tr>
          <?php
        }
      }
    } else if ($request === "/get-deleted-users") {
      foreach ($users as $user) {
        if ($user['role'] !== 'admin' && $user['soft_deleted'] !== 0) { ?>
            <tr>
              <td><img class="profile_image"
                  src="<?php echo $user['image_path'] ? $user['image_path'] : '../images/logo.png' ?>"
                  alt="<?php echo $user['name'] . "'s profile image" ?>"></td>
              <td><?php echo $user['name'] ?></td>
              <td><?php echo $user['email'] ?></td>
              <td style="color: var(--error-color)">inactive</td>
              <td>
                <a href="/admin-restore-user?email=<?php echo $user['email'] ?>">
                  <ion-icon name="refresh-outline"></ion-icon>
                </a>
                <a href="/admin-delete-user?user=<?php echo $user['email'] ?>">
                  <ion-icon name="trash-outline"></ion-icon>
                </a>
              </td>
            </tr>
          <?php
        }
      }
    } else
      Header("Location: /admin-manager")
        ?>
      </table>
    </main>