<?php

class participants
{
    private ArrayObject $chat_id;
    private ArrayObject $user_id;

    function __construct(ArrayObject $chat_id, ArrayObject $user_id)
    {
        $this->chat_id=$chat_id;
        $this->user_id=$user_id;
    }

    public function getChatId()
    {
        return $this->chat_id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function addChatId(ArrayObject $newChat_id)
    {
        $this->chat_id=array_merge($this->chat_id, $newChat_id);
    }

    public function addUserId(ArrayObject $newUser_id)
    {
        $this->user_id=array_merge($this->user_id, $newUser_id);
    }
}