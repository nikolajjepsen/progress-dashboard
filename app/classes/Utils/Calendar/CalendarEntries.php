<?php
namespace Progress\Utils\Calendar;

require_once(__DIR__ . '/../../../../vendor/autoload.php');
use Tracy\Debugger;
use Progress\Db\Database;
use \PDO;

class CalendarEntries
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

    public function __construct() {
        //var_dump(Database::get());
        $this->dbh = Database::get();
    }

    public function getEntries() {
        $this->sth = $this->dbh->prepare("SELECT `title`, `date`, `time` FROM `calendar_entries` ORDER BY id ASC");
        $this->sth->execute();
        if ($entries = $this->sth->fetchAll(PDO::FETCH_OBJ)) {
            return $entries;
        }

        return [];
    }
}
