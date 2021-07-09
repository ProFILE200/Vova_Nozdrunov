<?php

namespace App\Controller;

use App\Entity\Car;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

class CarController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route ("/show")
     * @param Request $request
     * @return JsonResponse
     */
    public function showCar(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!$data) {
            $cars=$this->em->getRepository(Car::class)->findAll();
        }else{
            $keywords = preg_split("/[\s,=,&]+/", $request->getQueryString());
            for($i=0; $i<sizeof($keywords); $i++)
            {
                $cars[$keywords[$i]] = $keywords[++$i];
            }
            $cars=$this->em->getRepository(Car::class)->findBy($cars);
        }
        return $this->json($cars, $status = 200, $headers = [], $context = []);
    }
}
