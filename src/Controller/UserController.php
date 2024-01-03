<?php

namespace App\Controller;

use App\Entity\Registration;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\RegistrationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserController extends AbstractController
{
    public function __construct(
        private readonly RegistrationRepository $registrationRepository,
        private readonly SluggerInterface $slugger,
    )
    {}

    #[Route('/user', name: 'app_user')]
    public function profile(Request $request): Response
    {
        $personalInformationSent = false;
        $videoSent = false;
        $paid = false;

        /**
         * @var User $user
         */
        $user = $this->getUser();
        $videoError = null;

        if ($user === null) {
            return $this->redirectToRoute("app_login");
        }

        $registration = $this->registrationRepository->findOneBy([
            'owner' => $user
        ]);

        if ($registration !== null) {
            if ($registration->getVideoPath() !== null) {
                $videoSent = true;
            }
            if ($registration->isPaid()) {
                $paid = true;
            }
        }

        if ($user !== null) {
            $personalInformationSent = true;
        }

        $videoForm = $this->createForm(RegistrationFormType::class);
        $videoForm->handleRequest($request);

        if ($videoForm->isSubmitted()) {
            $videoFile = $videoForm->get('video')->getData();
            if ($videoFile) {
                $originalFilename = $user->getFirstname() . " " . $user->getLastname();
                $safeFilename = $this->slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-'. uniqid('', true) . '.' . $videoFile->getClientOriginalExtension();
                try {
                    $videoFile->move(
                        $this->getParameter('videos_directory'),
                        $newFilename
                    );
                    if ($registration === null) {
                        $registration = (new Registration())
                            ->setOwner($user)
                            ->setDate(new \DateTime())
                            ->setVideoPath($newFilename)
                            ->setPaid(false)
                        ;
                        $this->registrationRepository->save($registration, true);
                    }
                    return $this->redirectToRoute("app_user");
                } catch (FileException $e) {
                    $videoError = 'Vidéo trop volumineux';
                }
            }
        }

        return $this->render('user/profile.html.twig', [
            'form' => $videoForm,
            'personalInformationSent' => $personalInformationSent,
            'videoSent' => $videoSent,
            'paid' => $paid,
            'user' => $user,
            'videoError' => $videoError
        ]);
    }
}
