<?php

namespace App\Models;

class User extends Model {
    protected $table = "user";
    
    const USER_LEVEL_EXTERNAL = 1;
    const USER_LEVEL_UFFS = 2;
    const USER_CO_ORGANIZER = 3;
    const USER_LEVEL_ADMIN = 4;

    public $id;
    public $login;
    public $cpf;
    public $name;
    public $email;
    public $type;
    private $total_paid;

    public static function getById($theUserId) {
        $user = null;
        $query = SELF::conn()->prepare("SELECT id, login, name, email, type FROM users WHERE id = ?");
        
        if ($query->execute(array($theUserId))) {	
            $data = $query->fetch();
            $user = new User();
            $user->id = $data['id'];
            $user->login = $data['login'];
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->type = $data['type'];
        }
        
        return $user;
    }

    public static function isUsernameAvailable ($username) {
        $sql = "SELECT count(*) as amount FROM users WHERE login=:username";
        $query = SELF::conn()->prepare($sql);
        $query->execute([
            'username' => $username
        ]);
        $data = $query->fetch();
        return !$data['amount'];
    }
    
    public static function findById($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        $query = SELF::conn()->prepare($sql);
        $query->execute(['id' => $id]);
        $user_data = $query->fetch();

        if (!$user_data) return null;
        
        return SELF::newByData($user_data);
    }
    
    public function findAll() {
        return SELF::getInstancesByQuery("SELECT * FROM users ORDER BY name ASC");
    }
    
    public function findByRole($roles) {
        
        $query_result = array();
        $query = SELF::conn()->prepare("SELECT * FROM users WHERE type = :role");

        foreach ($roles as $role) {
            $find_something = $query->execute([
                'role' => $role
            ]);

            if ($find_something){
                while($aRow = $query->fetch()){
                    $query_result[$aRow['id']] = $aRow;
                }
            }
        }

        $users_model = array();
        foreach($query_result as $user) {
            array_push($users_model, User::newByData($user));
        }

        return $users_model;
    }

    public function findPaying () {
        return SELF::getInstancesByQuery("SELECT u.* FROM users AS u
            INNER JOIN payment AS p
            ON u.id = p.fk_user or u.cpf = p.cpf
            GROUP BY u.id
        ");
    }

    public function findNonPaying () {
        return SELF::getInstancesByQuery("SELECT u.* from users as u
            left join payment as p on (u.id = p.fk_user OR u.cpf = p.cpf)
            where p.id is null group by u.id
        ");
    }

    public function findInsiders () {
        return SELF::getInstancesByQuery("SELECT * from users
            where type != ".User::USER_LEVEL_EXTERNAL ."
        ");
    }

    public function findOutsiders () {
        return SELF::getInstancesByQuery("SELECT * from users
            where type = ".User::USER_LEVEL_EXTERNAL ."
        ");
    }

    public function isLevel($theLevel) {
        return $this->type == $theLevel;
    }

    public function hasPermission ($level) {
        return $this->type >= $level;
    }

    public function isExternal() {
        return $this->type == User::USER_LEVEL_EXTERNAL;
    }

    public function isInternal() {
        return !$this->isExternal();
    }
    
    public function getConferencePrice() {
        return $this->type == SELF::USER_LEVEL_EXTERNAL ? CONFERENCE_PRICE_EXTERNAL : CONFERENCE_PRICE;
    }

    public function getBond() {
        return $this->type == 2 ? "Externo" : "UFFS";
    }

    /* This is not so performatic but it will work, improve it if you want */
    public function getTotalPaid() {
        if (isset($this->total_paid)) return $this->total_paid;
        $sql = "SELECT sum(p.amount) as total FROM users AS u
            LEFT JOIN payment AS p
                ON u.id = p.fk_user OR u.cpf = p.cpf
            WHERE u.id = :user_id
            GROUP BY u.id";
        $query = SELF::conn()->prepare($sql);
        $query->execute(['user_id' => $this->id]);
        $data = (object) $query->fetch();
        return (double) $data->total;
    }

    public static function findByUsername ($username) {
        $sql = "SELECT * FROM users WHERE login = :username";
        $query = SELF::conn()->prepare($sql);
        $query->execute(['username' => $username]);
        $user_data = $query->fetch();

        if (!$user_data) {
            return null;
        }
        
        return SELF::newByData($user_data);
    }

    public function create () {
        $sql = "INSERT INTO users SET
            name = :name,
            cpf = :cpf,
            login = :login,
            email = :email,
            type = :type
        ";
        $query = SELF::conn()->prepare($sql);
        $success = $query->execute([
            'name' => $this->name,
            'cpf' => $this->cpf,
            'login' => $this->login,
            'email' => $this->email,
            'type' => $this->type || 1
        ]);
        $this->id = SELF::conn()->lastInsertId();
        return $success;
    }

    public function update() {
        $sql = "UPDATE users SET
            login = :login,
            cpf = :cpf,
            name = :name,
            email = :email,
            type = :type 
            WHERE id = :userId
        ";

        $query = SELF::conn()->prepare($sql);

        $success = $query->execute([
            'login' => $this->login,
            'cpf' => $this->cpf,
            'name' => $this->name,
            'email' => $this->email,
            'type' => $this->type,
            'userId' => $this->id
        ]);

        return $success;
    }

    public static function newByData ($data) {
        $data = (object) $data;
        $user = new SELF();
        $user->id = $data->id;
        $user->login = $data->login;
        $user->cpf = $data->cpf;
        $user->name = $data->name;
        $user->email = $data->email;
        $user->type = $data->type;
        return $user;
    }

    private static function getInstancesByQuery($sql) {
        $list = [];
        $query = SELF::conn()->prepare($sql);
        
        if ($query->execute()) {	
            while ($raw = $query->fetch()) {
                $list[] = SELF::newByData($raw);
            }
        }
        
        return $list;
    } 
}