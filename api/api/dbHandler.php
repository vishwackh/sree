<?php
class Database
{
    private $host;
    private $user;
    private $pass;
    private $dbname;
    private $dbh;

    private $error=array(
			'stat' => false,
			'msg'  => '',
			'code' => '',
			'file' => '',
			'line' => '',
			'sql'  => ''
    	);

    private $stmt;

 	/**
 	 * [__construct : This method will initialize all DB crdentials with class property & create new Database object instance for any kind of DB transaction]
 	 * @param [type] $dbConf [it hold all DB credentials]
 	 */
    public function __construct()
    {

        $dbConf['host']   = "127.0.0.1";
        $dbConf['user']   = "root";
        $dbConf['pass']   ="root";
        $dbConf['dbname'] = "conventionhalldb";


		$this->host   = $dbConf['host'];
		$this->user   = $dbConf['user'];
		$this->pass   = $dbConf['pass'];
		$this->dbname = $dbConf['dbname'];

		// $this->host = '127.0.0.1';
		// $this->user = 'root';
		// $this->pass = 'root';
		// $this->dbname = 'conventionhalldb';

        // Set DSN(Databse Source Name)
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname .';charset=utf8';

        // Set options
        $options = array(
            PDO::ATTR_PERSISTENT    => true,
            PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION
        );

        // Create a new PDO instanace
        try{
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        }
        // Catch any errors
        catch(PDOException $e){
			$this->error['stat'] = true;
			$this->error['msg']  = $e->getMessage();
			$this->error['code'] = $e->getCode();
			$this->error['file'] = $e->getFile();
			$this->error['line'] = $e->getLine();
        }
    }


    // Method for building prepare statements
    public function query($query)
    {
    	$this->error=array(
			'stat' => false,
			'msg'  => '',
			'code' => '',
			'file' => '',
			'line' => '',
			'sql'  => ''
    	);

	    $this->stmt = $this->dbh->prepare($query);
	}


	// Method for binding value to prepare statements
	public function bind($param, $value, $type = null)
	{
		if (is_null($type))
		{
			switch (true)
			{
				case is_int($value):
					$type = PDO::PARAM_INT;
					break;

				case is_bool($value):
					$type = PDO::PARAM_BOOL;
					break;

				case is_null($value):
					$type = PDO::PARAM_NULL;
					break;

				default:
					$type = PDO::PARAM_STR;
			}
		}

		$this->stmt->bindValue($param, $value, $type);
	}


		// Method for binding value to prepare statements
	public function bindParameters($DBParameters, $type = null)
	{

		if (is_null($type))
		{
			foreach ($DBParameters as $key=>$value)
			{

				$param=':'.$key;

				switch (true)
				{
					case is_int($value):
						$type = PDO::PARAM_INT;
						break;

					case is_bool($value):
						$type = PDO::PARAM_BOOL;
						break;

					case is_null($value):
						$type = PDO::PARAM_NULL;
						break;

					default:
						$type = PDO::PARAM_STR;
				}

				$this->stmt->bindValue($param, $value, $type);
			}

		}
	}


	// Method for executing prepare statements
	public function execute()
	{
		try{
			return $this->stmt->execute();
		}catch(PDOException $e){
			$this->error['stat'] = true;
			$this->error['msg']  = $e->getMessage();
			$this->error['code'] = $e->getCode();
			$this->error['file'] = $e->getFile();
			$this->error['line'] = $e->getLine();
		}

	}


	// Method for getting result set after executing prepare statments
	public function resultset()
	{
	    $this->execute();
	    return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
	}


	// Method for getting single result set after executing prepare statments
	public function single()
	{
	    $this->execute();
	    return $this->stmt->fetch(PDO::FETCH_ASSOC);
	}


	// Method for getting result set row count
	public function rowCount()
	{
	    return $this->stmt->rowCount();
	}


	// Method for gettign last insert id after inserting any record
	public function lastInsertId()
	{
	    return $this->dbh->lastInsertId();
	}


	// Method for begin databse transaction
	public function beginTransaction()
	{
	    return $this->dbh->beginTransaction();
	}


	// Method for end databse transaction
	public function endTransaction()
	{
	    return $this->dbh->commit();
	}


	// Method for cancel databse transaction
	public function cancelTransaction()
	{
	    return $this->dbh->rollBack();
	}


	// Method for getting SQL queries & binding values info from prepare statments
	public function debugDumpParams()
	{
	    return $this->stmt->debugDumpParams();
	}

	// Method getting exception details
	public function getExceptionDetails()
	{
		return $this->error;
	}

	// Method getting exception details
	public function getQRY($query, $params)
	{
	    $keys = array();

	    foreach ($params as $key => $value)
	    {
	        if (is_string($key))
	        {    $keys[] = '/:'.$key.'/';    	}
	    	else
	    	{    $keys[] = '/[?]/';    			}
	    }
	    $query = preg_replace($keys, $params, $query, 1, $count);

	    return $query;
	}

	// Method to abstract functionality of select query
	public function selectQuery($query, $params='', $ResultCount='M')// ResultCount: single row or multiple row
	{
	    $data = array();

		$this->query($query);
		if($params != '')
	    {	$this->bindParameters($params);	}
		$this->execute();
	    $ExeceptionDetails = $this->getExceptionDetails();

		if($ExeceptionDetails['stat'])
		{
			$execution['status']=false;
			$execution['response']=$ExeceptionDetails;
			// code to handle the exception
		}
		else
		{
			if($ResultCount=='S')
			{	$data=$this->single();		}
			else
			{	$data=$this->resultset();	}
		}

	    return $data;
	}

	// Method to abstract functionality of insert/update/select query
	public function executeQuery($query, $params)
	{
		$this->query($query);

		$this->bindParameters($params);
		$this->execute();

		return $this->getExceptionDetails();
	}
}
?>