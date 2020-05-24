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
        // Test user.
        $users = [
            ['SIGMA[!]', '1234', ['ROLE_ADMIN'], 'lascortinashuelenaporro@gmail.com'],
            ['admin', '1234', ['ROLE_ADMIN'], 'admin@admin.es'],
            ['user', '1234', ['ROLE_USER'], 'user@user.es']
        ];

        foreach ($users as $data) {
            $user = new Player();
            $user->setUsername($data[0]);
            $user->setPassword(password_hash($data[1], PASSWORD_DEFAULT));
            $user->setRoles($data[2]);
            $user->setEmail($data[3]);
            $manager->persist($user);
        }

        // Games por testing.
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

        /*
        // Scores for testing Tetris.
        $scores = [
            ['Tetris JS', 'SIGMA[!]', '420'],
            ['Tetris JS', 'SIGMA[!]', '160458'],
            ['Tetris JS', 'SIGMA[!]', '43146']
        ];

        foreach ($scores as $data) {
            $score = new Score();
            $score->setGame($data[0]);
            $score->setPlayer($data[1]);
            $score->setScore($data[2]);
            $manager->persist($score);
        } */

        $manager->flush();
    }
}
