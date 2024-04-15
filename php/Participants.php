<?php

class participants
{
    private int $chat_id;
    private int $user_id;

    function __construct(int $chat_id, int $user_id)
    {
        $this->chat_id=$chat_id;
        $this->user_id=$user_id;
    }
}