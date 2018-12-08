<?php
namespace AppBundle\Form\DataTransformer;

use AppBundle\Repository\RegionRepository;
use Symfony\Component\Form\DataTransformerInterface;

class RegionTransformer implements DataTransformerInterface
{
    private $regionRepository;

    public function __construct(RegionRepository $regionRepository)
    {
        $this->regionRepository = $regionRepository;
    }

    public function transform($items)
    {
        return $items;
    }

    public function reverseTransform($item)
    {
        if(!$item){
            return null;
        }

        return $this->regionRepository->findOneBy(['name' => $item]);
    }
}