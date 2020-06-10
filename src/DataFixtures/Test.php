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
            ['Nayem', '1234', ['ROLE_ADMIN'], 'nayemms@gmail.com'],
            ['Kevin', '1234', ['ROLE_ADMIN'], 'kevinguzman0816@gmail.com'],
            ['Testator', '1234', ['ROLE_USER'], 'test@xyz.com'],
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
            ['Operators Match', 'games/operators-match/img/cardMatch.jpg'],
            ['Bomberman', 'games/bomberman/img/bomberman.jpeg'],
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
