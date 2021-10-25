<?php
class Pages extends Controller implements MainFunctionalities
{
    public function __construct()
    {
    }

    public function index()
    {
        session_activity_checker();
        $data = [
            'title' => 'Php Simple Framework',
            'description' => 'Simple social network built on Php simple MVC framework'
        ];
        $this->view('pages/index', $data);
    }

    public function about()
    {
        session_activity_checker();
        $data = [
            'title' => 'About Us',
            'description' => 'App to share posts with other users'
        ];
        $this->view('pages/about', $data);
    }
}
