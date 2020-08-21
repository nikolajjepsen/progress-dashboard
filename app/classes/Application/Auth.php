<?php
namespace Progress\Application;

require_once(__DIR__ . '/../../../vendor/autoload.php');
use Tracy\Debugger;

use \Progress\Db\Database;
use \PDO;

class Auth
{
    /**
     * The database handler
     * @var object
     */
    protected $dbh;

    /**
     * The statement handler
     * @var object
     */
    protected $sth;

    /**
     * Variable to contain the user id for validation of session ids.
     * @var int
     */
    private $userId;

    public function __construct()
    {
        $this->dbh = Database::get();
        Debugger::$logSeverity = E_NOTICE | E_WARNING;
        Debugger::enable(Debugger::DETECT, __DIR__ . '/../../log');
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function getUser($userId = false)
    {
        $this->sth = $this->dbh->prepare("SELECT * FROM `users` WHERE `id` = :id LIMIT 1");
        if ($userId === false) {
            $this->sth->execute([
                ':id' => $this->userId
            ]);
        } elseif (!empty($this->userId)) {
            $this->sth->execute([
                ':id' => $userId
            ]);
        } else {
            return false;
        }

        if ($user = $this->sth->fetch(PDO::FETCH_OBJ)) {
            return $user;
        }

        return false;
    }

    public function login($email, $password)
    {
        if (empty($email) || empty($password)) {
            return false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $this->sth = $this->dbh->prepare("SELECT `id`, `password` FROM `users` WHERE `email` = :email LIMIT 1");
        $this->sth->execute([
            ':email' => $email
        ]);

        if ($userInfo = $this->sth->fetch(PDO::FETCH_OBJ)) {
            $storedHash = $userInfo->password;

            if (password_verify($password, $storedHash)) {
                return $userInfo->id;
            }
        }

        return false;
    }

    public function register($email, $password)
    {
        try {
            $this->sth = $this->dbh->prepare("SELECT COUNT(`id`) as `userCount` FROM `users` WHERE `email` = :email LIMIT 1");
            $this->sth->execute([
                ':email' => $email
            ]);
        } catch (Exception $e) {
            Debugger::log($e->getMessage);
            return false;
        }

        if ($this->sth->fetch(PDO::FETCH_OBJ)->userCount > 0) {
            return false;
        }

        $passwordHash = password_hash($password, PASSWORD_ARGON2I);
        try {
            $this->sth = $this->dbh->prepare("INSERT INTO `users` (`email`, `password`) VALUES (:email, :password)");
            $this->sth->execute([
                ':email' => $email,
                ':password' => $passwordHash
            ]);
        } catch (Exception $e) {
            Debugger::log($e->getMessage());
            return false;
        }

        if ($this->sth->rowCount() > 0) {
            return $this->dbh->lastInsertId;
        }
    }
}
