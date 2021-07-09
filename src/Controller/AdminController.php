<?php

namespace App\Controller;

use App\Entity\Car;
use Doctrine\DBAL\Driver\PDO\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    private array $brand = ['NISSAN', 'BMW', 'AUDI', 'RENAULT', 'VOLVO', 'NISSAN', 'OPEL', 'MAZDA', 'MERSEDES', 'BMW'];
    private array $model = ['Murano', 'X6', 'Q8', 'Zoe', 'XC60', 'Skyline', 'MERIVA', '6', 'G500', 'M5'];
    private array $color = ['Black', 'Red', 'Black', 'White', 'Brue', 'Grey', 'Yellow', 'Green', 'Red', 'Black'];
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/admin", name="car_add", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function createCar(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $car = new Car();
        $car->setBrand($data["brand"]);
        $car->setModel($data["model"]);
        $car->setColor($data["color"]);

        $this->em->persist($car);
        $this->em->flush();
        return $this->json('Car added');
    }

    /**
     * @Route("/db", name="posts_patch", methods={"PATCH"})
     * @param Request $request
     * @return JsonResponse
     */
    public function updateCar(Request $request): JsonResponse
    {
         if($request->getMethod() == 'PATCH'){
             for ($i = 0; $i < 10; $i++) {
                $car = new Car();
                $car->setBrand($this->brand["$i"]);
                $car->setModel($this->model["$i"]);
                $car->setColor($this->color["$i"]);
                $this->em->persist($car);
            }
              $this->em->flush();
        };
        return $this->json('');
    }

    /**
     * @Route("/db", name="posts_delete", methods={"DELETE"})
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteCar(Request $request): JsonResponse
    {
        if($request->getMethod() == 'DELETE') {
            $data = json_decode($request->getContent(), true);
            if (!$data) {
                $cars = $this->em->getRepository(Car::class)->findBy($data);
                foreach ($cars as $car) {
                    $this->em->remove($car);
                    $this->em->flush();
                }
            } else {
                $cars = $this->em->getRepository(Car::class)->findAll();
                foreach ($cars as $car) {
                    $this->em->remove($car);
                }
                $this->em->flush();
            }
        }
        return $this->json('DB data deleted');
    }
}



//$db = ['up' => 'chonada=', 'down' => 'sudaidi='];
////        dd($request->getQueryString());
////        dd($db['up']);
//if(($request->getQueryString()  ) !== $db["up"]){
//    throw new \Exception('Poshel nahren');
//}else{
//    return $this->json('good');
//}