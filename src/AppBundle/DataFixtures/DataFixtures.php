<?php
namespace AppBundle\DataFixtures;

use AppBundle\Entity\Courier;
use AppBundle\Entity\Region;
use AppBundle\Entity\Routes;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class DataFixtures extends Fixture
{
    const START_DATE = '01.06.2015';

    public function load(ObjectManager $manager)
    {
        $this->loadRegions($manager);
        $this->loadCourier($manager);
        $this->loadRoutes($manager);
    }

    private function loadRoutes(ObjectManager $manager){
        $couriers = $manager->getRepository('AppBundle:Courier')->findAll();
        $regions = $manager->getRepository('AppBundle:Region')->findAll();
        $date = new \DateTime(self::START_DATE);

        while($date->getTimestamp() < time()) {
            foreach ($regions as $region) {
                foreach ($couriers as $courier) {
                    $routes = new Routes();
                    $routes->setCourier($courier);
                    $routes->setRegion($region);
                    $routes->setDate(clone $date);
                    $manager->persist($routes);
                    $date->modify('+1 days');

                    if($date->getTimestamp() > time()){
                        break 2;
                    }
                }
            }
        }
        $manager->flush();
    }

    private function loadRegions(ObjectManager $manager)
    {
        foreach ($this->getRegionsData() as [$name, $delivery]) {
            $region = new Region();
            $region->setName($name);
            $region->setDelivery($delivery);

            $manager->persist($region);
        }

        $manager->flush();
    }

    private function loadCourier(ObjectManager $manager)
    {
        foreach ($this->getCourierData() as $name) {
            $courier = new Courier();
            $courier->setName($name);

            $manager->persist($courier);
        }

        $manager->flush();
    }

    private function getRegionsData(): array
    {
        return [
            ['Санкт-Петербург', 7],
            ['Уфа', 5],
            ['Нижний Новгород', 3],
            ['Владимир', 3],
            ['Кострома', 4],
            ['Екатеринбург', 2],
            ['Ковров', 2],
            ['Воронеж', 4],
            ['Самара', 4],
            ['Астрахань', 6],
        ];
    }

    private function getCourierData(): array
    {
        return [
            'Иванов Иван Иванович',
            'Бобрицкий Сергей Владимирович',
            'Козлов Сергей Александрович',
            'Мухамедшин Дмитрий Игоревич',
            'Ветлицкий Игорь Витальевич',
            'Владимиров Владимир Владимирович',
            'Петров Петр Иванович',
            'Иванов Сергей Владимирович',
            'Талипов Рустам Вахитович',
            'Дмитриев Андрей Алексеевич',
            'Сухарин Антон Вячеславович',
        ];
    }
}