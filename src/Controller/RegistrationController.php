<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\CountryRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validation;

class RegistrationController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly CountryRepository $countryRepository,
    ){}

    #[Route('/registration', name: 'app_registration')]
    public function index(Request $request): Response
    {
        $validator = Validation::createValidator();
        if ($this->getUser() !== null) {
            return $this->redirectToRoute("app_user");
        }
        $form = $this->createForm(UserFormType::class);

        $countries = $this->countryRepository->findAll();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var User $user
             */
            $user = $form->getData();

            $emailExists = $this->userRepository->findOneBy([
                'email' => $user->getEmail()
            ]) !== null;

            $phoneNumberExists = $this->userRepository->findOneBy([
                'phoneNumber' => $user->getPhoneNumber()
            ]) !== null;

            $passwordCorrectFormat = strlen($user->getPassword()) > 3;
            $passwordConfirmCorrect = $user->getPassword() === $user->getPasswordConfirm();

            if (!$emailExists && !$phoneNumberExists && $passwordCorrectFormat && $passwordConfirmCorrect) {
                $user->setPassword(
                    $this->passwordHasher->hashPassword($user, $user->getPassword())
                );
                $user->setPasswordConfirm(null);

                $this->userRepository->save($user, true);
                $this->addFlash('success', 'Informations personnelles enrégistrées avec succès. Connectez-vous avec votre adresse E-mail et votre mot de passe');
                return $this->redirectToRoute("app_login");
            } else {
                $this->addFlash('notice-b', "Veuillez corriger les erreurs suivantes");
                if ($emailExists) {
                    $this->addFlash('notice', "Un enrégistrement existe déjà avec l'adresse email '" . $user->getEmail() . "'");
                }
                if ($phoneNumberExists) {
                    $this->addFlash('notice', "Un enrégistrement existe déjà avec le numéro de téléphone '" . $user->getPhoneNumber() . "'");
                }
                if (!$passwordCorrectFormat) {
                    $this->addFlash('notice', "Le mot de passe doit faire plus de 3 carractères");
                }
                if (!$passwordConfirmCorrect) {
                    $this->addFlash('notice', "Les deux mot de passes ne correspondent pas");
                }
            }

//            $this->redirectToRoute("app_registration");
        }

        return $this->render('registration/index.html.twig', [
            'form' => $form,
            'countries' => $countries
        ]);
    }
}
