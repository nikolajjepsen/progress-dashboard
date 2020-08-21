<?php
namespace Progress\Utils\Country;

require_once(__DIR__ . '/../../../../vendor/autoload.php');
use Tracy\Debugger;
use Progress\Db\Database;
use \PDO;

class Countries
{

    /**
     * The database handler
     * @var object
     */
    private $dbh;

    /**
     * The statement handler
     * @var object
     */
    private $sth;

    public function __construct()
    {
        $this->dbh = Database::get();
        Debugger::$logSeverity = E_NOTICE | E_WARNING;
        Debugger::enable(Debugger::DETECT, __DIR__ . '/../../../log');
    }

    public function listCountries()
    {
        $this->sth = $this->dbh->prepare("SELECT * FROM `data_countries` WHERE `enabled` = :enabled ORDER BY `iso` ASC");
        $this->sth->execute([
            ':enabled' => 1
        ]);

        if ($countries = $this->sth->fetchAll(PDO::FETCH_OBJ)) {
            return $countries;
        }
    }

    public function getCountry($id) {
        $this->sth = $this->dbh->prepare("SELECT * FROM `data_countries` WHERE `id` = :id");
        $this->sth->execute([
            ':id' => $id
        ]);

        if ($country = $this->sth->fetch(PDO::FETCH_OBJ)) {
            return $country;
        }
    }
}
