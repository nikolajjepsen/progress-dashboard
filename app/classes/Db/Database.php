<?php
namespace Progress\Db;

require_once(__DIR__ . '/../../../vendor/autoload.php');
use Tracy\Debugger;
use \PDO;

class Database
{

    /**
     * Carry the instance of the database class.
     * @var static
     */
    private static $instance = null;

    /**
     * The database handler (refered to as $dbh othereise)
     * @var object
     */
    private $pdo;

    /**
     * Set the property "pdo" with the PDO database handler.
     */
    public function __construct()
    {
        Debugger::$logSeverity = E_NOTICE | E_WARNING;
        Debugger::enable(Debugger::DETECT, __DIR__ . '/../../log');

        try {
            $this->pdo = new PDO('mysql:host=localhost;port=3307;dbname=dashboard', 'root', '');   
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->exec("SET NAMES utf8");
        } catch (Exception $e) {
            Debugger::log('Unable to connect to database: ' . $e->getMessage());
        }

        

    }
    /**
     * Create or maintain an instance of the database class
     * @return object The instance of the database.
     */
    public static function get()
    {
        if (is_null(self::$instance))
            self::$instance = new Database();
        return self::$instance;
    }

    /**
     * __call
     * Proxy calls to non-existent methods on this class to PDO instance
     * @param  string $method The method
     * @param  array $args    The arguments
     * @return array          The parameters to be passed as an index array
     */
    public function __call($method, $args)
    {
        $callable = array(
            $this->pdo,
            $method
        );
        if (is_callable($callable)) {
            return call_user_func_array($callable, $args);
        }
    }
}