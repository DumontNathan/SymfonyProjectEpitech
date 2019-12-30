<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SearchFilmController extends AbstractController
{

    private $session;
    
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }


    public function search(Request $request)
    {
        $search = $request->query->get('search');
        $this->session->set('search', $search);
        $client = HttpClient::create();
        $moviesRequest = $client->request('GET', "http://www.omdbapi.com/?apikey=7900e0c4&s={$search}");
        $moviesJson = $moviesRequest->getContent();
        $movies = json_decode($moviesJson, true);

        if($movies['Response'] == 'True')
        {
            return $this->render('film.html.twig', [
                'movies' => $movies['Search'],
                'search' => $search
            ]);
        }
        else
        {
            return $this->render('errorfilm.html.twig', [
                'search' => $search
                ]);
        }

    }

    /**
     * @Route("/film/{filmId}")
     * 
     */
    public function describe(Request $request, $filmId)
    {
        $search = $this->session->get('search');
        $client = HttpClient::create();
        $moviesRequest = $client->request('GET', "http://www.omdbapi.com/?apikey=7900e0c4&i={$filmId}");
        $moviesJson = $moviesRequest->getContent();
        $movies = json_decode($moviesJson, true);
        return $this->render('fullfilm.html.twig', [
        'movies' => $movies,
        'search' => $search
        ]);
    }

}