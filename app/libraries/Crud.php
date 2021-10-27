<?php
    interface Crud{
        public function add();
        public function list($page=null);
        public function edit($id);
        public function delete($id);
    }