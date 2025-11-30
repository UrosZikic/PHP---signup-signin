<main class="flex_default flex_column flex_align_center">
  <?php if (!isset($_COOKIE["auth"])) { ?>
    <h1>Welcome dear user!</h1>
    <h2>Would you like to <a href="/register" style="padding: 0 0.5rem; color: #2a5c85">register</a> your new account or
      continue where you
      stopped (<a href="/sign-in" style="padding: 0 0.5rem; color: #2a5c85">sign-in</a>)</h2>
  <?php } else { ?>
    <h1>Welcome back</h1>
    <a href="/profile" style="padding: 0 0.5rem; color: #2a5c85">Visit your profile</a>
  <?php } ?>
</main>