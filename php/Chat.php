<?php

class Chat
{
    private int $chat_id;
    private string $name;

    function __construct(int $chat_id, string $name)
    {
        $this->chat_id = $chat_id;
        $this->name = $name;
    }

    public function getChatId(): int
    {
        return $this->chat_id;
    }

    public function getChatName(): string
    {
        return $this->name;
    }
}