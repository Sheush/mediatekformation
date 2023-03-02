<?php
namespace App\Controller\Admin;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\PlaylistType;
use App\Entity\Playlist;

/**
 * Contrôleur Admin des playlists
 *
 * @author emds
 */
class AdminPlaylistController extends AbstractController {
    
    /**
     * 
     * @var PlaylistRepository
     */
    private $playlistRepository;
    
    /**
     * 
     * @var FormationRepository
     */
    private $formationRepository;
    
    /**
     * 
     * @var CategorieRepository
     */
    private $categorieRepository; 
    
    /**
     * Page twig pour les playlists
     */
    private const PAGE_PLAYLISTS = "admin/adminPlaylists.html.twig";
    
    /**
     * Page twig pour une playlist
     */
    private const PAGE_PLAYLIST = "admin/adminPlaylist.html.twig";
    
    function __construct(PlaylistRepository $playlistRepository, 
            CategorieRepository $categorieRepository,
            FormationRepository $formationRespository) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRespository;
    }
    
    /**
     * @Route("/adminPlaylists", name="adminPlaylists")
     * @return Response
     */
    public function index(): Response{
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_PLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories            
        ]);
    }

    /**
    * @Route("/adminPlaylists/tri/{champ}/{ordre}", name="adminPlaylists.sort")
    * @param type $champ
    * @param type $ordre
    * @return Response
    */
    public function sort($champ, $ordre): Response{
        switch($champ){
            default:
                $playlists = $this->playlistRepository->findAllOrderByName($ordre);
            case "name":
                $playlists = $this->playlistRepository->findAllOrderByName($ordre);
                break;
            case "nbformations":
                $playlists = $this->playlistRepository->findAllOrderByNbFormations($ordre);
                break;
        }
     $categories = $this->categorieRepository->findAll();
     return $this->render(self::PAGE_PLAYLISTS, [
        'playlists' => $playlists,
        'categories' => $categories
        ]);
     }         
    
    /**
     * @Route("/adminPlaylists/recherche/{champ}/{table}", name="adminPlaylists.findallcontain")
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        if ($table==''){
                $playlists = $this->playlistRepository->findByContainValue($champ, $valeur);
        }
        else{
            $playlists = $this->playlistRepository->findByContainValueTable($champ, $valeur);
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_PLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories,            
            'valeur' => $valeur,
            'table' => $table
        ]);
    }  
    
    /**
     * @Route("/adminPlaylists/playlist", name="adminPlaylists.add")
     * @return Response
     */
    public function add(Request $request): Response{
       $playlist = new Playlist();
       $form = $this->createForm(PlaylistType::class, $playlist);
       $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()){
            $this->playlistRepository->add($playlist, true);
            $this->addFlash('playlist_request', 'Playlist ajoutée.');
            return $this->redirectToRoute('adminPlaylists');
        }
        $id = $playlist->getId();
        $playlistCategories = $this->categorieRepository->findAllForOnePlaylist($id);
        $playlistFormations = $this->formationRepository->findAllForOnePlaylist($id);
        return $this->render(self::PAGE_PLAYLIST, [
            'playlist' => $playlist,
            'playlistcategories' => $playlistCategories,
            'playlistformations' => $playlistFormations,
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/adminPlaylists/playlist/{id}", name="adminPlaylists.edit")
     * @param type $id
     * @return Response
     */
    public function edit($id, Request $request): Response{
        $playlist = $this->playlistRepository->find($id);
        $form = $this->createForm(PlaylistType::class, $playlist);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()){
            $this->playlistRepository->add($playlist, true);
            $this->addFlash('playlist_request', 'Playlist modifiée.');
            return $this->redirectToRoute('adminPlaylists');
        }
        $playlistCategories = $this->categorieRepository->findAllForOnePlaylist($id);
        $playlistFormations = $this->formationRepository->findAllForOnePlaylist($id);
        return $this->render(self::PAGE_PLAYLIST, [
            'playlist' => $playlist,
            'playlistcategories' => $playlistCategories,
            'playlistformations' => $playlistFormations,
            'form' => $form->createView()
        ]);        
    }
    
    /**
     * @Route("/adminPlaylists/playlist/{id}/delete", name="adminPlaylists.delete")
     * @param type $id
     * @return Response
     */
    public function delete($id, Request $request): Response{
        try {
            if ($this->isCsrfTokenValid('delete' . $id, $request->get('_token'))) {
                $playlist = $this->playlistRepository->find($id);
                $this->playlistRepository->remove($playlist, true);
                $this->addFlash('playlist_request', 'Playlist supprimée.');
            }
            return $this->redirectToRoute('adminPlaylists');
        } catch (\Exception $e) {
            $this->addFlash('playlist_request', 'Vous ne pouvez supprimer cette playlist, celle-ci contient des formations.');
            return $this->redirectToRoute('adminPlaylists');
    }
}      
    
}
