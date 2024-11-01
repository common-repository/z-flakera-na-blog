<?php
class mzg_flaker {
	var $url = "http://api.flaker.pl/api";
	protected $zapytanie; // pelny url do api
	var $api_html = "false"; // czy ma zwrócić w postaci html czy nie?
	var $api_type = "user"; // o co pytamy?
	var $api_login; // login użytkownika o którego pytamy
	var $api_source = "flaker"; // serwis z jakiego mają pochodzić wpisy
	public $contents;
	
	function __construct($login, $tag='all') {
		$this->api_tag = $tag;
		$this->api_login = $login;
		$this->zapytanie = $this->url . "/html:" . $this->api_html . "/type:" . $this->api_type . "/login:" . $this->api_login . "/source:" . $this->api_source . "/tag:" . $this->api_tag . "/since:" . (string) ((int) get_option('mzg_flak_since'));
		}
		
	public function get_zapytanie() {
		return $this->zapytanie;
		}
		
	function connect() {
		$polozenie = $this->get_zapytanie();
		$handle = fopen($polozenie, 'r');
		$this->contents = stream_get_contents($handle);
		return $this->contents;
		}
	}
	
class mzg_json {
	public function mzg_json_decode($json, $assoc = 'false') {
		return json_decode($json, $assoc);
		}
	}
?>
