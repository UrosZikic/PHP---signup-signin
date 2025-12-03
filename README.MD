- Set environment
  - composer require vlucas/phpdotenv
  - create a root folder named 'environment'
    - inside the folder create:
    1. .env file and fill it with your desired variables
    - Next, put the installed 'vendor' folder inside 'environment' folder. This folder was created through composer installation.
    - Next, within 'environment' folder create a folder 'config'
    1. Within config, create a file 'bootstrap.php'
    2. In the bootstrap.php:
    - import autoload.php, a file from the vendor folder. Make sure to provide the proper path (most likely '/../vendor/autoload.php';)
    - use Dotenv\Dotenv
    - $dotenv = Dotenv::createImmutable(**DIR** . '/../');
    - $dotenv->load();
  - Now to access env variables, in the folder you need them, import the bootstrap.php: Make sure you provide the proper path, if you're targeting it from a folder in the root, then it will probably be
    '/vendor/autoload.php';

---

- Set up the database, userbase table and the default admin_user
  -- After you've set up your environment, just open create_userbase.php file in the SQL folder and you'd be good to start.

---

- Filesystem
  - On registration, signin, signout or edit attempt a folder 'filesystem' will be automatically created, each separate attempt will be stored in its own file.

-- hosting set-up:

1. Inside the SQL folder, make sure to update the hostname, username and the password based on your hosting service
2. cookies are by default set to 'localhost', make sure to change this to your host provider's domain of your website
