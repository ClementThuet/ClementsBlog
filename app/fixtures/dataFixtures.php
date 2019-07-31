<?php

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;



class UserFixtureLoader implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setname('Clement');

        $manager->persist($user);
        $manager->flush();
        
        $loader = new Loader();
        $loader->addFixture(new UserDataLoader());
        
        
        $purger = new ORMPurger();
        $executor = new ORMExecutor($em, $purger);
        $executor->execute($loader->getFixtures(), true);
    }
}