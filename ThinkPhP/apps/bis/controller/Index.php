<?php
namespace app\bis\controller;


class Index extends Base
{
    public function index()
    {
        return $this->fetch();
    }
}
