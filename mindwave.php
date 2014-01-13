<?php

class MindwaveData{
	
	private $data = array(
		'time' => '',
		'attention' => '',
		'meditation'=>'',
		'delta' => '',
		'theta' => '',
		'lowAlpha' => '',
		'highAlpha' => '',
		'lowBeta' => '',
		'highBeta' => '',
		'lowGamma' => '',
		'highGamma' => '', 
		'poorSignalLevel' => '',
		'status' => '',
		'blinkStrength' => ''
	);
	
	function getArrayData(){
		$x = array();
		foreach($this->data as $k=>$v){
			$x[] = $v;
		}
		
		return $x;
	}
	
	function getArrayHeader(){
		$x = array();
		foreach($this->data as $k=>$v){
			$x[] = $k;
		}
		
		return $x;
	}
	
	function import($data){
		foreach($this->data as $k=>$v){
			$this->data[$k] = $data[$k];
			if($k == 'time'){
				$this->data[$k] = time();
			}
		}
	}
}

function to_array($input){
	$data = (array)json_decode($input);
	if(isset($data['eegPower'])){
		$dataReady = (array)$data['eegPower'];
		$dataESense = (array)$data['eSense'];
		$dataReady['attention'] = $dataESense['attention'];
		$dataReady['meditation'] = $dataESense['meditation'];
		$dataReady['poorSignalLevel'] = $data['poorSignalLevel'];
		if($dataReady['poorSignalLevel'] == 0){
			$dataReady['status'] = 'OK';
		}else{
			$dataReady['status'] = 'POOR';
		}
		
	}
	if(isset($data['blinkStrength'])){
		$dataReady['blinkStrength'] = $data['blinkStrength'];
		$dataReady['status'] = 'NODATA';
	}
	if(isset($data['status'])){
		$dataReady = $data;
	}
	
	$mindWave = new MindwaveData();
	$mindWave->import($dataReady);
	
	$dataResult['header'] = $mindWave->getArrayHeader();
	$dataResult['data'] = $mindWave->getArrayData();
	return $dataResult;
}