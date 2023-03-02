<?php

namespace App\Controller\Admin;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Categorie;

/**
 * Contrôleur Admin des catégories
 *
 * @author Maxat
 */
class AdminCategorieController extends AbstractController
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
    private const PAGE_CATEGORIES = "admin/adminCategories.html.twig";
    
    public function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository)
    {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
    }
    
    /**
     * @Route("/adminCategories", name="adminCategories")
     * @return Response
     */
    public function index()
    {
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_CATEGORIES, [
            'categories' => $categories
        ]);
    }
    
    /**
     * @Route("/adminCategories/add", name="adminCategories.add")
     * @return Response
     */
    public function add(Request $request): Response{
        if ($this->isCsrfTokenValid('add', $request->get('_token'))){
            $name = $request->get('name');
            $categorie = new Categorie();
            if (($this->categorieRepository->findBy(array('name'=>$name))
                    != null)){
                $this->addFlash('categorie_request', 'Vous ne pouvez créer cette catégorie, '
                        . 'il en existe déjà une du même nom.');
            }
            else{
                $categorie->setName($name);
                $this->categorieRepository->add($categorie, true);
                $this->addFlash('categorie_request', 'Catégorie ajoutée.');
            }
        }
        return $this->redirectToRoute('adminCategories');
    }
    
    /**
     * @Route("/adminCategories/formation/{id}/delete", name="adminCategories.delete")
     * @param type $id
     * @return Response
     */
    public function delete($id, Request $request): Response{
        if ($this->isCsrfTokenValid('delete' . $id, $request->get('_token'))){
            $categorie = $this->categorieRepository->find($id);
            $formations = $categorie->getFormations();
            if ($formations->isEmpty()){
                $this->categorieRepository->remove($categorie, true);
                $this->addFlash('categorie_request', 'Catégorie supprimée.');
            }
            else{
                $this->addFlash('categorie_request', 'Vous ne pouvez supprimer cette catégorie, '
                        . 'celle-ci contient des formations.');
            }
        }
        return $this->redirectToRoute('adminCategories');
    }
}
