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
            'title' => 'PHP Simple Framework',
            'description' => 'Very small custom framework for creating a small projects based on MVC pattern.'
        ];
        $this->view('pages/index', $data);
    }

    public function about()
    {
        session_activity_checker();
        $data = [
            'title' => 'About Us',
            'description' => 'Very small custom framework for creating a small projects based on MVC pattern.'
        ];
        $this->view('pages/about', $data);
    }
}
