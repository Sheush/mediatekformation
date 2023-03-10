<?php

namespace App\Controller\Admin;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\FormationType;
use App\Entity\Formation;

/**
 * Contrôleur Admin des formations
 *
 * @author Maxat
 */
class AdminFormationController extends AbstractController
{
    
    /*
     * @var FormationRepository
     */
    private $formationRepository;
    
    /*
     * @var CategorieRepository
     */
    private $categorieRepository;
    
    /**
     * Page twig pour les formations
     */
    private const PAGE_FORMATIONS = "admin/adminFormations.html.twig";
    
    /**
     * Page twig pour éditer une formation
     */
    private const PAGE_FORMATION = "admin/adminFormation.html.twig";
    
    public function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository)
    {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
    }
    
    /**
     * @Route("/adminFormations", name="adminFormations")
     * @return Response
     */
    public function index()
    {
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_FORMATIONS, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }
    
    /**
     * @Route("/adminFormations/tri/{champ}/{ordre}/{table}", name="adminFormations.sort")
     * @param type $champ
     * @param type $ordre
     * @param type $table si $champ dans une autre table
     * @return Response
     */
    public function sort($champ, $ordre, $table=""): Response{
        switch($table){
            case "":
                $formations = $this->formationRepository->findAllOrderBy($champ, $ordre);
                break;
            default:
                $formations = $this->formationRepository->findAllOrderByTable($champ, $ordre, $table);
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_FORMATIONS, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }
    
    /**
     * @Route("/adminFormations/recherche/{champ}/{table}", name="adminFormations.findallcontain")
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        if ($table == ''){
            $formations = $this->formationRepository->findByContainValue($champ, $valeur);
        }
        else{
            $formations = $this->formationRepository->findByContainValueTable($champ, $valeur, $table);
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_FORMATIONS, [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }
    
    /**
     * @Route("/adminFormations/formation", name="adminFormations.add")
     * @return Response 
     */
    public function add(Request $request): Response{
       $formation = new Formation();
       $form = $this->createForm(FormationType::class, $formation);
       $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()){
            $this->formationRepository->add($formation, true);
            $this->addFlash('form_request', 'Formation ajoutée.');
            return $this->redirectToRoute('adminFormations');
        }
        return $this->render(self::PAGE_FORMATION, [
            'formation' => $formation,
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/adminFormations/formation/{id}", name="adminFormations.edit")
     * @param type $id
     * @return Response
     */
    public function edit($id, Request $request): Response{
        $formation = $this->formationRepository->find($id);
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()){
            $this->formationRepository->add($formation, true);
            $this->addFlash('form_request', 'Formation modifiée.');
            return $this->redirectToRoute('adminFormations');
        }
        return $this->render(self::PAGE_FORMATION, [
            'formation' => $formation,
            'form' => $form->createView()
        ]);        
    }
    
    /**
     * @Route("/adminFormations/formation/{id}/delete", name="adminFormations.delete")
     * @param type $id
     * @return Response
     */
    public function delete($id, Request $request): Response{
        if ($this->isCsrfTokenValid('delete' . $id, $request->get('_token'))){
            $formation = $this->formationRepository->find($id);
            $this->formationRepository->remove($formation, true);
            $this->addFlash('form_request', 'Formation supprimée.');
        }
        return $this->redirectToRoute('adminFormations');
    }
}
