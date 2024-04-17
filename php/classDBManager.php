<?php
include 'User.php';
include 'Message.php';
include 'Chat.php';
include 'Participants.php';
const SERVER = 'localhost';
const USER = 'root';
const PASS = '';
const DBNAME = 'chat_bd';

class classDBManager
{
    private string $server;
    private string $user;
    private string $pass;
    private string $dbname;
    private bool $db;
    private mysqli $conn;

    function __construct($server, $user, $pass, $dbname)
    {
        $this->server = $server;
        $this->user = $user;
        $this->pass = $pass;
        $this->dbname = $dbname;
        $this->db = false;
        $this->openConnection();

//        if ($this->db) {
//            $this->createAndInsertTables();
//        }
    }

    function __destruct()
    {
        mysqli_close($this->conn);
    }

    private function openConnection(): void
    {
        $this->conn = new mysqli($this->server, $this->user, $this->pass);

        if ($this->conn->connect_error) {
            return;
        }

        $selectDB = @mysqli_select_db($this->conn, $this->dbname);

        if (!$selectDB) {
            return;
        }

        $this->db = true;
    }

    public function checkConnection(): bool
    {
        return $this->db;
    }

    public function insertTestData($elem_count = 5): bool
    {
        // Plain data doesn't show any patterns
        // TODO:

        $last_user_id = $this->select('user_id', 'users', null, 'user_id DESC', 1);
        if (!$last_user_id) { $last_user_id = 0;}
            else $last_user_id = $last_user_id[0]['user_id'] + 1;
        $res_insert_users = 1;
        for ($i = $last_user_id; $i <= $last_user_id + $elem_count; $i++)
        {
            $res_insert_users *= $this->insert('users', [$i, 'alexei' . $i, 'password' . $i, 0]);
        }

        $last_chat_id = $this->select('chat_id', 'chats', null, 'chat_id DESC', 1);
        if (!$last_chat_id) { $last_chat_id = 0;}
            else $last_chat_id = $last_chat_id[0]['chat_id'] + 1;
        $res_insert_chats = 1;
        for ($i = $last_chat_id; $i <= $last_chat_id + $elem_count; $i++)
        {
            $res_insert_chats *= $this->insert('chats', [$i, 'chat'.$i]);
        }

        $last_message_id = $this->select('msg_id', 'messages', null, 'msg_id DESC', 1);
        if (!$last_message_id) { $last_message_id = 0;}
            else $last_message_id = $last_message_id[0]['msg_id'] + 1;
        $res_insert_messages= 1;
        $res_insert_participants = 1;
        for ($i = $last_message_id; $i <= $last_message_id + $elem_count; $i++) {
            $tmp_chat = $i % ($last_chat_id + $elem_count + 1);
            $tmp_user =  $i % ($last_user_id + $elem_count + 1);
            $res_insert_messages *= $this->insert('messages', [$i, 'msg' . $i, 0, true, $tmp_chat, $tmp_user]);
            $res_insert_participants *= $this->insert('participants', [$tmp_chat, $tmp_user]);
        }

        return $res_insert_users * $res_insert_chats * $res_insert_messages * $res_insert_participants;
    }

    public function createTables(): bool
    {
        $create_users_table = 'create table if not EXISTS users 
(user_id integer primary key, 
login varchar(15) not null, 
password varchar(10) not null,
privilege boolean not null)';

        $create_chats_table = 'create table if not EXISTS chats
(chat_id integer primary key,
name varchar(100) not null)';

        $create_messages_table = 'create table if not EXISTS messages
(msg_id integer primary key,
text text not null,
suspicious boolean not null,
valid boolean not null,
chat_id INTEGER,
user_id INTEGER,
FOREIGN KEY (chat_id) REFERENCES chats(chat_id),
FOREIGN KEY (user_id) REFERENCES users(user_id))';

        $create_participants_table = 'create table if not EXISTS participants
(chat_id INTEGER,
user_id INTEGER,
PRIMARY KEY (chat_id, user_id),
FOREIGN KEY (chat_id) REFERENCES chats(chat_id),
FOREIGN KEY (user_id) REFERENCES users(user_id))';

        $res_create = mysqli_query($this->conn, $create_users_table) *
        mysqli_query($this->conn, $create_chats_table) *
        mysqli_query($this->conn, $create_messages_table) *
        mysqli_query($this->conn, $create_participants_table);

        if (!$res_create)
        {
            return false;
        }

        return $res_create;
    }

    public function select($what, $from, $where = null, $order = null, $limit = null): false|array
    {
        $fetched = array();
        $sql = 'SELECT ' . $what . ' FROM ' . $from;
        if ($where != null) $sql .= ' WHERE ' . $where;
        if ($order != null) $sql .= ' ORDER BY ' . $order;
        if ($limit != null) $sql .= ' LIMIT ' . $limit;

        $query = mysqli_query($this->conn, $sql);
        if ($query) {
            $rows = mysqli_num_rows($query);
            for ($i = 0; $i < $rows; $i++) {
                $results = mysqli_fetch_assoc($query);
                $key = array_keys($results);
                $numKeys = count($key);
                for ($x = 0; $x < $numKeys; $x++) {
                    $fetched[$i][$key[$x]] = $results[$key[$x]];
                }
            }
            return $fetched;
        } else {
            return false;
        }
    }

    public function insert($table, $values, $rows = null): bool
    {

        $insert = 'INSERT INTO ' . $table;
        if ($rows != null) {
            $insert .= ' (' . $rows . ')';
        }
        $numValues = count($values);
        for ($i = 0; $i < $numValues; $i++) {
            if (is_string($values[$i])) $values[$i] = '"' . $values[$i] . '"';
        }
        $values = implode(',', $values);
        $insert .= ' VALUES (' . $values . ')';
        $ins = mysqli_query($this->conn, $insert);
        return (bool)$ins;
    }

    public function update($table, $values, $where = null): bool
    {

        $update = 'UPDATE ' . $table . ' SET';
        $numValues = count($values);
        $i = 0;
        foreach ($values as $column => $data) {
            $update .= ' ' . $column . '=';
            if (is_string($data)) $data = '"' . $data . '"';
            $update .= $data;
            $i++;
            if ($i < $numValues) {
                $update .= ', ';
            }
        }

        if ($where != null) $update .= ' WHERE ' . $where;

        $ins = mysqli_query($this->conn, $update);
        return (bool)$ins;
    }

    public function fetchUserById($userId) : User | null
    {
        $result = $this->select('*', 'users', 'user_id = ' . $userId);

        // Проверка наличия результата
        if ($result) {
            $result = $result[0];
            $usr_by_id = new User($result['user_id'], $result['login'],$result['password'], $result['privilege']);
            return $usr_by_id;
        } else {
            return null;
        }
    }

    public function fetchChatsFromUserId($userId): array | null
    {
        $ans = $this->select('chat_id', 'participants', 'user_id = ' . 1);
        if ($ans) {
            $arr = [];
            for ($i = 0; $i < count($ans); $i++) {
                array_push($arr, $ans[$i]['chat_id']);
            };
            return $arr;
        }
        return null;
    }

    public function registration(User $CLuser):bool
    {
        $last_user_id = $this->select('user_id', 'users', null, 'user_id DESC', 1);
        if (!$last_user_id) { $last_user_id = 0;}
            else $last_user_id = $last_user_id[0]['user_id'] + 1;

        // TODO: Inserting anyway?
        if($CLuser->getId()==-1){
            $CLuser->setId($last_user_id);
        }

        return $this->insert('users', [$CLuser->getID(), $CLuser->getLogin(), $CLuser->getPassword(), 0+$CLuser->getPrivilege()]);
    }

    public function sendMessage(Message $msg)
    {
        $last_message_id = $this->select('msg_id', 'messages', null, 'msg_id DESC', 1);
        if (!$last_message_id) { $last_message_id = 0;}
            else $last_message_id = $last_message_id[0]['msg_id'] + 1;


        $usr = $this->fetchUserById($msg->getUserId());
        if (is_null($usr)) { return false; }


        if (!in_array($msg->getChatId(), $this->fetchChatsFromUserId($usr->getId())))
        {
            $this->insert('participants', ['user_id' => $msg->getUserId(), 'chat_id' => $msg->getChatId()]);
        };

            // TODO: Why is not if here?
        $msg->setMsgId($last_message_id);
        return $this->insert('messages', [$msg->getMsgId(), $msg->getText(), 0 + $msg->isValid(), 0 + $msg->isSuspicious(), $msg->getChatId(), $msg->getUserId()]);
    }

    public function userChangeLogin($userId, $new_login): bool
    {
        $this->update('users', ['login' => $new_login], 'user_id = ' . $userId);
    }

    public function userChangePassword($userId, $new_password): bool
    {
        $this->update('users', ['password' => $new_password], 'user_id = ' . $userId);
    }

    public function delete($table, $where = null): bool
    {

        $sql = 'DELETE FROM ' . $table . ' WHERE ' . $where;
        if ($where == null) {
            $sql = 'TRUNCATE TABLE ' . $table;
        }
        $deleted = @mysqli_query($this->conn, $sql);
        return (bool)$deleted;
    }

    public function deleteAll()
    {
        $this->delete('participants');
        $this->delete('messages');
        $this->delete('chats', '1=1');
        $this->delete('users', '1=1');
    }

}


