<?php

class User
{
    private int $id;
    private string $login;
    private string $password;
    private bool $privilege;

    function __construct(int $id = -1, string $login, string $password, bool $privilege)
    {
        $this->id=$id;
        $this->login=$login;
        $this->password=$password;
        $this->privilege=$privilege;
    }


    public function setId(int $id)
    {
        $this->id=$id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getPrivilege(): bool
    {
        return $this->privilege;
    }

    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function setPrivilege(bool $privilege): void
    {
        $this->privilege = $privilege;
    }
}