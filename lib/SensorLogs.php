<?php

namespace OCA\SensorLogger;

use OCA\Files\App;
use OCP\AppFramework\Controller;
use OCP\IConfig;
use \OCP\IDb;
use OCP\IDBConnection;
use OCP\IL10N;

/**
 * Class SensorLogs
 *
 * @package OCA\SensorLogger
 */
class SensorLogs {

	/**
	 * @param $userId
	 * @param IDBConnection $db
	 * @return Log
	 */
	public static function getLastLog($userId, IDBConnection $db) {
		$query = $db->getQueryBuilder();
		$query->select('*')
			->from('sensorlogger_logs')
			->where('user_id = "'.$userId.'"')
			->orderBy('created_at', 'DESC');
		$query->setMaxResults(1);
		$result = $query->execute();

		$data = $result->fetch();

		if($data){
			$data = Log::fromRow($data);
		}
		return $data;
	}

	/**
	 * @param $userId
	 * @param IDBConnection $db
	 * @return array
	 */
	public static function getLogs($userId, IDBConnection $db) {
		$query = $db->getQueryBuilder();
		$query->select('*')
			->from('sensorlogger_logs')
			->where('user_id = "'.$userId.'"')
			->orderBy('id', 'DESC');
		$query->setMaxResults(100);
		$result = $query->execute();
		$data = $result->fetchAll();

		$logs = [];
		if($data) {
			foreach($data as $log) {
				$logs[] = Log::fromRow($log);
			}
		}
		return $logs;
	}

	/**
	 * @param $userId
	 * @param $uuId
	 * @param IDBConnection $db
	 * @return array
	 */
	public static function getLogsByUuId($userId, $uuId, IDBConnection $db) {
		$query = $db->getQueryBuilder();
		$query->select(array('id','device_uuid','humidity','temperature','created_at'))
			->from('sensorlogger_logs')
			->where('device_uuid = "'.$uuId.'"')
			->andWhere('user_id = "'.$userId.'"')
			->orderBy('created_at', 'DESC');
		$query->setMaxResults(1000);
		$result = $query->execute();

		$data = $result->fetchAll();

		return $data;
	}
	
	/*
	public function getUserValue($key, $userId) {
		return $this->config->getUserValue($userId, $this->appName, $key);
	}
	public function setUserValue($key, $userId, $value) {
		$this->config->setUserValue($userId, $this->appName, $key, $value);
	}
	*/
}