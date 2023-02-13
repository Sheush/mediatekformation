<?php

namespace App\tests\repository;

use App\Entity\Playlist;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of PlaylistRepositoryTest
 *
 * @author Maxat
 */
class PlaylistRepositoryTest extends KernelTestCase{
    
    /**
     * Récupère le repository de Playlist
     * @return PlaylistRepository
     */
    public function recupRepository(): PlaylistRepository{
        self::bootKernel();
        $repository = self::getContainer()->get(PlaylistRepository::class);
        return $repository;
    }
    
    /**
     * Création d'une instance de Playlist
     * @return PlaylistRepository
     */
    public function newPlaylist(): Playlist{
        $playlist = (new Playlist())
                ->setName('test');
        return $playlist;
    }
    
    public function testAddPlaylist(){
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $nbPlaylists = $repository->count([]);
        $repository->add($playlist, true);
        $this->assertEquals($nbPlaylists+1, $repository->count([]), 
                "erreur lors de l'ajout");
    }
    
    public function testRemovePlaylist(){
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $repository->add($playlist, true);
        $nbPlaylists = $repository->count([]);
        $repository->remove($playlist, true);
        $this->assertEquals($nbPlaylists - 1, $repository->count([]),
                "erreur lors de la suppression");
    }
}
