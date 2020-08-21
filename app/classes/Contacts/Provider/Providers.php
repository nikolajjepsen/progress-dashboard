<?php
namespace Progress\Contacts\Provider;

require_once(__DIR__ . '/../../../../vendor/autoload.php');
use Tracy\Debugger;
use Progress\Db\Database;
use \PDO;

class Providers
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

    public function listProviders()
    {
        $this->sth = $this->dbh->prepare("SELECT * FROM `data_providers` ORDER BY `name` ASC");
        $this->sth->execute([
            ':enabled' => 1
        ]);

        if ($countries = $this->sth->fetchAll(PDO::FETCH_OBJ)) {
            return $countries;
        }
    }
}
