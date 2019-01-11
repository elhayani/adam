<?php

// src/Controller/LuckyController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Etudiants;
use Symfony\Component\HttpFoundation\Request;
use App\Service\MessageGenerator;

class LuckyController extends AbstractController {

    /**
     * @Route("/lucky/number")
     */
    public function number(MessageGenerator $messageGenerator) {

        $message = $messageGenerator->getHappyMessage();
        $number = random_int(0, 100);
        return $this->render('lucky/number.html.twig', [
                    'number' => $number,
                    'message' => $message,
        ]);
    }

    /**
     * @Route("/lucky/home")
     */
    public function home(Request $request) {
        $doct = $this->getDoctrine()->getManager();
        $etudiant = new Etudiants();
        $form = $this->createFormBuilder($etudiant)
                ->add('nom')
                ->add('prenom')
                ->add('save', SubmitType::class, array('label' => 'Enregistrer'))
                ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $etudiant = $form->getData();
            $etudiant->setDateNaissance(new \DateTime);
            $doct->persist($etudiant);
            $doct->flush();
        }

        $q = $doct->createQuery("select u from App\Entity\Etudiants u where u.id >= 10 and u.id <= 20");
        $etudiants = $q->getResult();
        //var_export($etudiants);
        return $this->render('lucky/home.html.twig', ['etudiants' => $etudiants, 'formEtud' => $form->createView()]);
    }

    /**
     * @Route("/lucky/news")
     */
    public function news() {

        $doct = $this->getDoctrine()->getManager();

        $etud = new Etudiants();
        $etud->setNom('elha');
        $etud->setPrenom('nass');
        $etud->setDateNaissance(new \DateTime());

        $doct->persist($etud);
        $doct->flush();

        return $this->render('lucky/news.html.twig', []);
    }

    /**
     * @Route("/lucky/contact")
     */
    public function contact() {

        $stud = $this->getDoctrine()
                ->getRepository(Etudiants::class)
                ->findAll();

        return $this->render('lucky/contact.html.twig', ['students' => $stud,]);
    }

    /**
     * @Route("/lucky/about")
     */
    public function about() {
        $doct = $this->getDoctrine()->getManager();

        $etud = $this->getDoctrine()
                ->getRepository(Etudiants::class)
                ->find(1);
        ;
        var_export($etud);


        $doct->remove($etud);
        $doct->flush();

        return $this->render('lucky/about.html.twig', []);
    }

    /**
     * @Route("/lucky/ws")
     */
    public function ws() {
        $url = 'http://services-int/TrapServices/api/trap/CreateControl';
        $authorization = 'Bearer';
        $methodIsPost = true;
        $isPutMethode = true;
        $tokenPost = null;
        $dataPost = '{"type_mouvement":16,"montant":"50000,00","produit":"6184","canal":"AGEAS","ref_contrat":293960,"souscripteur":{"nom":"ESPIAND","prenom":"BORIS","date_de_naissance":"1972-11-29"}}';

        $headers = array('Authorization: Bearer', 'Cache-Control: no-cache', 'Content-Type: application/json');
        //$headers = array('Authorization: Bearer', 'Cache-Control: no-cache', 'Content-Type: application/x-xxx-form-urlencoded;charset=UTF-8');
        ob_start();
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);

        if (!empty($authorization)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }
        if ($methodIsPost || $isPutMethode) {
            curl_setopt($curl, CURLOPT_POST, 1);
            if (!empty($tokenPost)) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, $tokenPost . '&grant_type=refresh_token');
            } else {
                if ($dataPost) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $dataPost);
                } else {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, 'grant_type=client_credentials&scope=');
                }
            }
        }
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        curl_setopt($curl, CURLOPT_VERBOSE, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($curl);
        $objRes = json_decode($res);
        $res=$objRes->{'souscripteur'}->{'date_de_naissance'};
        $infos = '';//curl_getinfo($curl);
        /*
        if ($res === false) {
            dump(curl_error($curl));
        }

        $result = ob_get_contents();

        $isGedFile = substr($result,0,10);

        ob_end_clean();
        curl_close($curl);
         *         
         */
        return $this->render('lucky/ws.html.twig', ['res' => $res, 'infos' => $infos]);
    }

}
