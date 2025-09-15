<?php
    require_once(APPPATH . 'libraries/ZktecoPhp/Libs/Services/Connect.php');
    require_once(APPPATH . 'libraries/ZktecoPhp/Libs/Services/Ping.php');
    require_once(APPPATH . 'libraries/ZktecoPhp/Libs/Services/Util.php');
    require_once(APPPATH . 'libraries/ZktecoPhp/Libs/Services/Attendance.php');
    // require_once(APPPATH . 'libraries/ZktecoPhp/Libs/Services/Socket.php');
    // require_once(APPPATH . 'libraries/ZktecoPhp/Libs/Services/Command.php');
    // require_once(APPPATH . 'libraries/ZktecoPhp/Libs/Services/Response.php');
    require_once(APPPATH . 'libraries/ZktecoPhp/Libs/ZKTeco.php');

    use CodingLibs\ZktecoPhp\Libs\ZKTeco;

    class Zkteco_lib {
        protected $zk;

        public function __construct() {
            // $this->zk = new CodingLibs\ZktecoPhp\Libs\ZKTeco('192.168.3.181');
            // $this->zk->connect();
        }

        public function getAttendance($ip) {
            $this->zk = new CodingLibs\ZktecoPhp\Libs\ZKTeco($ip);
            $this->zk->connect();
            return $this->zk->getAttendances();
        }

        public function disconnect() {
            $this->zk->disconnect();
        }
    }
?>