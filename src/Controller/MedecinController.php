<?php
namespace App\Controller;

use App\Entity\User;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Profiler\Profiler;

class MedecinController extends AbstractController
{

    /**
     *
     * @Route("/modifier-profil", name="modifier-profil")
     */
    public function modiferProfil(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user_id = $this->getUser()->getId();
        $user = $repository->findOneById($user_id);

        if($request->getMethod() == 'POST')
        {
            $name = $request->get('name', '');
            $specialite = $request->get('specialite', '');
            $telephone = $request->get('telephone', '');
            $addresse = $request->get('addresse', '');
            $description = $request->get('description', '');

            $file = $request->files->get('image');
            if($file)
            {
                $newFilename = uniqid().'.'.$file->guessExtension();

                try {
                    $file->move(
                        'img/medecin/',
                        $newFilename
                    );
                    $user->setImage($newFilename);

                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
            }

            $user->setName($name);
            $user->setSpecialite($specialite);
            $user->setTelephone($telephone);
            $user->setAddresse($addresse);
            $user->setDescription($description);

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('medecin', ['id' => $user->getId()]);
        }

        return $this->render('medecin/modifier_profil.html.twig', ['user' => $user]);
    }


    /**
     *
     * @Route("/medecins", name="list-medecin")
     */
    public function medecins(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $medecinRepository = $this->getDoctrine()->getRepository(User::class);

        $medecins = $medecinRepository->findAll();

        return $this->render('medecin/medecins.html.twig', ['medecins' => $medecins]);
    }

     /**
     *
     * @Route("/medecins/{id}", name="medecin")
     */
    public function emploi(Request $request, $id = 0)
    {
        $em = $this->getDoctrine()->getManager();
        $medecinRepository = $this->getDoctrine()->getRepository(User::class);

        $medecin = $medecinRepository->findOneById($id);

        if(! $medecin)
        {
            return $this->redirectToRoute('list-medecin');
        }

        return $this->render('medecin/medecin.html.twig', ['medecin' => $medecin]);
    }


}
