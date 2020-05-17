<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Game;
use App\Entity\Player;

class Test extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Usuarios Test.
        $users = [
            ['admin', '1234', ['ROLE_ADMIN'], 'admin@admin.es'],
            ['user', '1234', ['ROLE_USER'], 'user@user.es']
        ];

        foreach ($users as $datos) {
            $user = new Player();
            $user->setUsername($datos[0]);
            $user->setPassword(password_hash($datos[1], PASSWORD_DEFAULT));
            $user->setRoles($datos[2]);
            $user->setEmail($datos[3]);
            $manager->persist($user);
        }

        // Juegos Test para hacer la plantilla de la CMS (listado).
        $games = [
            ['Tetris JS', 'games/tetris-js/img/tetris.jpg'],
            // Second game...
        ];
        
        foreach ($games as $data) {
            $game = new Game();
            $game->setName($data[0]);
            $game->setImg($data[1]);
            $manager->persist($game);
        }

        $manager->flush();
    }
}
