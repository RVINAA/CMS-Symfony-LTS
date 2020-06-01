# CMS-Symfony-LTS
[ᴄᴍs] - ᴘʀᴏʏᴇᴄᴛᴏ ɢ.sᴜᴘ [ ✗ ]

## How to deploy
- Clone this repository.
- Locate your folder inside the shell and execute '*composer install*' to get all the dependencies.
- Configure your .env mysql configuration (It´s a bad choice to give mine .env BUT, I don´t want to see you asking me for the file).
- It´s time to create the proyect database, execute '*bin/console doctrine:database:create*' in your shell.
- After that, you need to make some users, games... execute '*bin/console doctrine:fixtures:load*'.
- Run the server.
