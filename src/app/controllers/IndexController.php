<?php

use Phalcon\Mvc\Controller;
use MyApp\component\Token;
use MyApp\Models\Playlists;

class IndexController extends Controller
{
    public function indexAction()
    {
        if (!$this->cookies->has('token')) {
            $token = new Token();
            $token->getTokenValue('e84a71e83d3a4c95a1b58f7115895a30', 'e326743aeff64b5992b3ef8270b22510');
        }
    }
    public function submitAction()
    {
        $search = $this->request->getPost('search');
        $type = array();
        if (isset($_POST['albums'])) {
            array_push($type, 'album');
        }
        if (isset($_POST['artists'])) {
            array_push($type, 'artist');
        }
        if (isset($_POST['playlists'])) {
            array_push($type, 'playlist');
        }
        if (isset($_POST['tracks'])) {
            array_push($type, 'track');
        }
        if (isset($_POST['shows'])) {
            array_push($type, 'show');
        }
        if (isset($_POST['episodes'])) {
            array_push($type, 'episode');
        }
        $data = $this->getData->getData($search, $type);
        $this->view->type = $type;
        $this->session->set('data', $data);
    }
    public function addAction()
    {
        $type = $this->request->get('type');
        $id = $this->request->get('id');
        $playlist = new Playlists();
        $data = array(
            'spotify_id' => $id,
            'type' => $type
        );
        $playlist->assign(
            $data,
            [
                'spotify_id',
                'type'
            ]
        );
        $playlist->save();
        $this->response->redirect('index');
    }
}
