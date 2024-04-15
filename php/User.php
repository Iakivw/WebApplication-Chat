<?php

class User
{
    private int $id;
    private string $login;
    private string $password;
    private bool $privilege;

    function __construct(int $id, string $login, string $password, bool $privilege)
    {
        $this->id=$id;
        $this->login=$login;
        $this->password=$password;
        $this->privilege=$privilege;
    }
}