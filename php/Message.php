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

    public function getChatId(): int
    {
        return $this->chat_id;
    }
    public function getMsgId(): int
    {
        return $this->msg_id;
    }

    public function setMsgId(int $msg_id): void
    {
        $this->msg_id = $msg_id;
    }
    public function getText(): string
    {
        return $this->text;
    }
    public function getUserId(): int
    {
        return $this->user_id;
    }
    public function isValid(): bool
    {
        return $this->valid;
    }
    public function isSuspicious(): bool
    {
        return $this->suspicious;
    }
}