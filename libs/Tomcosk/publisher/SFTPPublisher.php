<?php
/**
 * @author tomas.igrini
 * Publisher for uploading file to a SFTP (secure FTP)
 *
 */
namespace Tomcosk\publisher;
use Tomcosk\Config as c;
use Tomcosk\libs\SFTPConnection;

class SFTPPublisher extends Publisher
{

	protected $connId = null;

	function __construct() {
		$this->connect();
	}

	/**
	 * @return boolean
	 */
	public function connect() {
		$ftp_server=c::get("ftpHost");
		$ftp_user_name=c::get("ftpLogin");
		$ftp_user_pass=c::get("ftpPass");
		
		try
		{
		    $this->connId = new SFTPConnection($ftp_server, 22);
		    $this->connId->login($ftp_user_name, $ftp_user_pass);
		    $this->log("Connected to server $ftp_server");
		    return true;
		}
		catch (\Exception $e)
		{
		    echo $e->getMessage() . "\n";
   		    $this->log("Not connected to server $ftp_server");
   		    return false;
		}
	}
	
	/* (non-PHPdoc)
	 * @see \publisher\Publisher::publish()
	 */
	public function publish() {

		if ($this->connId->isConnected()) {
			try
			{
				$file = c::get("workDir")."/".c::get("filename");
				$fileLock = c::get("fileLock");
				$path_parts_lock = pathinfo($fileLock);
				$path_parts = pathinfo($file);
			    $this->connId->uploadFile($path_parts_lock["dirname"]."/".$path_parts_lock["basename"], c::get("remoteFileLock"));
			    $this->connId->uploadFile($path_parts["dirname"]."/".$path_parts["basename"], c::get("remoteFile"));
			    $this->connId->delete(c::get("remoteFileLock"));
			    $this->log("Successfully uploaded file");
			}
			catch (\Exception $e)
			{
			    echo $e->getMessage() . "\n";
				$this->connect();
			}
		} else {
			if($this->connect()) {
				$this->publish();
			}

			$this->log("no connection to SFTP server");
		}
	}
}
?>