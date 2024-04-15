<?php

class Message
{
    private int $chat_id;
    private int $msg_id;
    private string $text;
    private int $user_id;
    private bool $suspicious;
    private bool $valid;

    function __construct(int $chat_id, int $msg_id, string $text, int $user_id, bool $valid, bool $suspicious)
    {
        $this->chat_id = $chat_id;
        $this->msg_id = $msg_id;
        $this->text = $text;
        $this->user_id = $user_id;
        $this->valid = $valid;
        $this->suspicious = $suspicious;
    }
}